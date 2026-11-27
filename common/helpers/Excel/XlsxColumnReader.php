<?php

namespace common\helpers\Excel;

use RuntimeException;
use SimpleXMLElement;
use XMLReader;
use ZipArchive;

/**
 * Читает выбранные колонки листа xlsx построчно.
 * PhpSpreadsheet собирает XML листа целиком, и на большой выгрузке libxml падает с out of memory.
 */
class XlsxColumnReader
{
    const SPREADSHEET_NS = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
    const RELATIONSHIP_NS = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';
    const PACKAGE_REL_NS = 'http://schemas.openxmlformats.org/package/2006/relationships';
    const MAX_ENTRY_BYTES = 268435456;

    /**
     * @param string $filename
     * @param string $sheetName
     * @param int $startRow
     * @param string[] $columns
     * @return array[]
     */
    public function read($filename, $sheetName, $startRow, array $columns)
    {
        $zip = new ZipArchive();
        if ($zip->open($filename) !== true) {
            throw new RuntimeException('Файл не является книгой Excel.');
        }

        $tempFiles = [];
        try {
            $sheetPath = $this->resolveSheetPath($zip, $sheetName);
            $rawRows = $this->readRawRows($zip, $sheetPath, $startRow, $columns, $tempFiles);
            $strings = $this->readSharedStrings($zip, $this->sharedStringIndexes($rawRows), $tempFiles);

            return $this->resolveRows($rawRows, $columns, $strings);
        } finally {
            $zip->close();
            $this->deleteTempFiles($tempFiles);
        }
    }

    /**
     * @param ZipArchive $zip
     * @param string $sheetName
     * @return string
     */
    private function resolveSheetPath(ZipArchive $zip, $sheetName)
    {
        $workbook = $this->zipContents($zip, 'xl/workbook.xml');
        $rels = $this->zipContents($zip, 'xl/_rels/workbook.xml.rels');
        $relationshipId = $this->relationshipId($workbook, $sheetName);
        $target = $this->relationshipTarget($rels, $relationshipId);

        return $this->packagePath($target);
    }

    /**
     * @param string $workbookXml
     * @param string $sheetName
     * @return string
     */
    private function relationshipId($workbookXml, $sheetName)
    {
        $workbook = $this->loadXml($workbookXml);
        if ($workbook === null) {
            throw new RuntimeException('Не удалось прочитать книгу Excel.');
        }

        foreach ($this->childElements($this->childElements($workbook)->sheets) as $sheet) {
            if ($this->attribute($sheet, 'name') !== $sheetName) {
                continue;
            }
            $id = $this->attribute($sheet, 'id', self::RELATIONSHIP_NS);
            if ($id === '') {
                throw new RuntimeException('У листа «' . $sheetName . '» нет связи.');
            }

            return $id;
        }

        throw new RuntimeException('Лист «' . $sheetName . '» не найден.');
    }

    /**
     * @param string $relsXml
     * @param string $relationshipId
     * @return string
     */
    private function relationshipTarget($relsXml, $relationshipId)
    {
        $rels = $this->loadXml($relsXml);
        if ($rels === null) {
            throw new RuntimeException('Не удалось прочитать связи книги Excel.');
        }

        foreach ($this->childElements($rels, self::PACKAGE_REL_NS) as $relationship) {
            if ($this->attribute($relationship, 'Id') === $relationshipId) {
                return $this->attribute($relationship, 'Target');
            }
        }

        throw new RuntimeException('Лист не найден среди связей книги.');
    }

    /**
     * @param ZipArchive $zip
     * @param string $sheetPath
     * @param int $startRow
     * @param string[] $columns
     * @param string[] $tempFiles
     * @return array
     */
    private function readRawRows(ZipArchive $zip, $sheetPath, $startRow, array $columns, array &$tempFiles)
    {
        $wanted = array_fill_keys($columns, true);
        $sheetFile = $this->extractEntry($zip, $sheetPath, $tempFiles);
        $reader = $this->openReader($sheetFile);
        $rows = [];
        $sequential = 0;

        try {
            while ($reader->read()) {
                if ($reader->nodeType !== XMLReader::ELEMENT || $reader->localName !== 'row') {
                    continue;
                }

                $sequential++;
                $attribute = $reader->getAttribute('r');
                $rowNumber = ($attribute === null || $attribute === '') ? $sequential : (int)$attribute;
                $outerXml = $reader->readOuterXml();
                if ($rowNumber < $startRow) {
                    continue;
                }

                $rows[] = $this->parseRow($outerXml, $wanted);
            }
        } finally {
            $reader->close();
        }

        return $rows;
    }

    /**
     * @param string $xml
     * @param array $wanted
     * @return array
     */
    private function parseRow($xml, array $wanted)
    {
        $row = $this->loadXml($xml);
        if ($row === null) {
            return [];
        }

        $cells = [];
        $fallbackIndex = 0;
        foreach ($this->childElements($row) as $name => $cell) {
            if ($name !== 'c') {
                continue;
            }
            $column = $this->columnFromRef($this->attribute($cell, 'r'), $fallbackIndex);
            if (!isset($wanted[$column])) {
                continue;
            }
            $cells[$column] = [
                'type' => $this->attribute($cell, 't'),
                'value' => $this->cellRawValue($cell),
            ];
        }

        return $cells;
    }

    /**
     * @param SimpleXMLElement $cell
     * @return string|null
     */
    private function cellRawValue(SimpleXMLElement $cell)
    {
        $children = $this->childElements($cell);
        if (isset($children->is)) {
            return $this->elementText($children->is);
        }
        if (isset($children->v) && (string)$children->v !== '') {
            return (string)$children->v;
        }

        return null;
    }

    /**
     * @param array $rawRows
     * @return int[]
     */
    private function sharedStringIndexes(array $rawRows)
    {
        $indexes = [];
        foreach ($rawRows as $cells) {
            foreach ($cells as $cell) {
                if ($cell['type'] !== 's' || $cell['value'] === null || $cell['value'] === '') {
                    continue;
                }
                $index = (int)$cell['value'];
                $indexes[$index] = $index;
            }
        }

        return array_values($indexes);
    }

    /**
     * @param ZipArchive $zip
     * @param int[] $indexes
     * @param string[] $tempFiles
     * @return array
     */
    private function readSharedStrings(ZipArchive $zip, array $indexes, array &$tempFiles)
    {
        if (!$indexes) {
            return [];
        }
        if ($zip->locateName('xl/sharedStrings.xml') === false) {
            throw new RuntimeException('В файле нет таблицы строк.');
        }

        $wanted = array_fill_keys($indexes, true);
        $file = $this->extractEntry($zip, 'xl/sharedStrings.xml', $tempFiles);
        $reader = $this->openReader($file);
        $strings = [];
        $index = 0;

        try {
            while ($reader->read()) {
                if ($reader->nodeType !== XMLReader::ELEMENT || $reader->localName !== 'si') {
                    continue;
                }
                $outerXml = $reader->readOuterXml();
                if (isset($wanted[$index])) {
                    $strings[$index] = $this->elementText($this->loadXml($outerXml));
                }
                $index++;
            }
        } finally {
            $reader->close();
        }

        return $strings;
    }

    /**
     * @param array $rawRows
     * @param string[] $columns
     * @param array $strings
     * @return array[]
     */
    private function resolveRows(array $rawRows, array $columns, array $strings)
    {
        foreach ($rawRows as $index => $cells) {
            $values = [];
            foreach ($columns as $column) {
                $values[$column] = isset($cells[$column])
                    ? $this->phpValue($cells[$column], $strings)
                    : null;
            }
            $rawRows[$index] = $values;
        }

        return $rawRows;
    }

    /**
     * @param array $cell
     * @param array $strings
     * @return int|float|string|bool|null
     */
    private function phpValue(array $cell, array $strings)
    {
        $type = $cell['type'];
        $raw = $cell['value'];

        if ($type === 's') {
            $index = (int)$raw;

            return array_key_exists($index, $strings) ? $strings[$index] : '';
        }
        if ($type === 'inlineStr' || $type === 'str') {
            return $raw === null ? '' : $raw;
        }
        if ($type === 'b') {
            return $raw === '1';
        }
        if ($type === 'e' || $raw === null || $raw === '') {
            return null;
        }
        if (is_numeric($raw)) {
            return $this->numberValue($raw);
        }

        return $raw;
    }

    /**
     * Целые значения Excel приходят строкой. Короткие становятся int, длинные бирки не обрезаются.
     *
     * @param string $raw
     * @return int|float|string
     */
    private function numberValue($raw)
    {
        if (preg_match('/^\d+$/', $raw)) {
            return strlen($raw) < 19 ? (int)$raw : $raw;
        }

        $number = (float)$raw;
        if (floor($number) == $number && $number >= PHP_INT_MIN && $number <= PHP_INT_MAX) {
            return (int)$number;
        }

        return $number;
    }

    /**
     * @param SimpleXMLElement|null $element
     * @return string
     */
    private function elementText($element)
    {
        if (!$element instanceof SimpleXMLElement) {
            return '';
        }

        $children = $this->childElements($element);
        if (isset($children->r) && count($children->r) > 0) {
            $text = '';
            foreach ($children->r as $run) {
                $runChildren = $this->childElements($run);
                if (isset($runChildren->t)) {
                    $text .= (string)$runChildren->t;
                }
            }

            return $text;
        }

        return isset($children->t) ? (string)$children->t : '';
    }

    /**
     * @param string $ref
     * @param int $fallbackIndex
     * @return string
     */
    private function columnFromRef($ref, &$fallbackIndex)
    {
        if ($ref !== '' && preg_match('/^[A-Z]+/', $ref, $matches)) {
            return $matches[0];
        }

        $fallbackIndex++;

        return $this->columnName($fallbackIndex);
    }

    /**
     * @param int $index
     * @return string
     */
    private function columnName($index)
    {
        $name = '';
        while ($index > 0) {
            $index--;
            $name = chr(65 + ($index % 26)) . $name;
            $index = (int)($index / 26);
        }

        return $name;
    }

    /**
     * Атрибут без префикса нельзя читать как $element['name']: рядом с r:id SimpleXML возвращает пустую строку.
     *
     * @param SimpleXMLElement $element
     * @param string $name
     * @param string|null $namespace
     * @return string
     */
    private function attribute(SimpleXMLElement $element, $name, $namespace = null)
    {
        $attributes = $namespace === null ? $element->attributes() : $element->attributes($namespace);
        if (!$attributes instanceof SimpleXMLElement || !isset($attributes[$name])) {
            return '';
        }

        return (string)$attributes[$name];
    }

    /**
     * @param SimpleXMLElement $element
     * @param string|null $namespace
     * @return SimpleXMLElement
     */
    private function childElements(SimpleXMLElement $element, $namespace = null)
    {
        $children = $element->children($namespace === null ? self::SPREADSHEET_NS : $namespace);
        if (count($children) > 0) {
            return $children;
        }

        return $element->children();
    }

    /**
     * @param ZipArchive $zip
     * @param string $entryName
     * @return string
     */
    private function zipContents(ZipArchive $zip, $entryName)
    {
        $contents = $zip->getFromName($entryName);
        if ($contents === false) {
            throw new RuntimeException('В файле нет книги Excel.');
        }

        return $contents;
    }

    /**
     * @param string $target
     * @return string
     */
    private function packagePath($target)
    {
        $target = str_replace('\\', '/', $target);
        if (isset($target[0]) && $target[0] === '/') {
            return ltrim($target, '/');
        }

        $parts = [];
        foreach (explode('/', 'xl/' . $target) as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }
            if ($part === '..') {
                array_pop($parts);
                continue;
            }
            $parts[] = $part;
        }

        return implode('/', $parts);
    }

    /**
     * @param ZipArchive $zip
     * @param string $entryName
     * @param string[] $tempFiles
     * @return string
     */
    private function extractEntry(ZipArchive $zip, $entryName, array &$tempFiles)
    {
        $stat = $zip->statName($entryName);
        if ($stat !== false && isset($stat['size']) && $stat['size'] > self::MAX_ENTRY_BYTES) {
            throw new RuntimeException('Лист слишком большой.');
        }

        $source = $zip->getStream($entryName);
        if ($source === false) {
            throw new RuntimeException('Не удалось извлечь данные из файла Excel.');
        }

        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        if ($path === false) {
            fclose($source);
            throw new RuntimeException('Не удалось создать временный файл.');
        }
        $tempFiles[] = $path;

        $target = fopen($path, 'wb');
        if ($target === false) {
            fclose($source);
            throw new RuntimeException('Не удалось создать временный файл.');
        }

        $copied = stream_copy_to_stream($source, $target);
        fclose($source);
        fclose($target);
        if ($copied === false) {
            throw new RuntimeException('Не удалось извлечь данные из файла Excel.');
        }

        return $path;
    }

    /**
     * @param string $path
     * @return XMLReader
     */
    private function openReader($path)
    {
        $reader = new XMLReader();
        if (!$reader->open($path, 'UTF-8', LIBXML_NONET | LIBXML_COMPACT)) {
            throw new RuntimeException('Не удалось прочитать XML книги.');
        }

        return $reader;
    }

    /**
     * @param string $xml
     * @return SimpleXMLElement|null
     */
    private function loadXml($xml)
    {
        $previous = libxml_use_internal_errors(true);
        $loaderDisabled = null;
        if (PHP_VERSION_ID < 80000 && function_exists('libxml_disable_entity_loader')) {
            $loaderDisabled = libxml_disable_entity_loader(true);
        }

        $element = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NONET);
        libxml_clear_errors();

        if ($loaderDisabled !== null) {
            libxml_disable_entity_loader($loaderDisabled);
        }
        libxml_use_internal_errors($previous);

        return $element instanceof SimpleXMLElement ? $element : null;
    }

    /**
     * @param string[] $tempFiles
     */
    private function deleteTempFiles(array $tempFiles)
    {
        foreach ($tempFiles as $tempFile) {
            if (is_file($tempFile)) {
                unlink($tempFile);
            }
        }
    }
}
