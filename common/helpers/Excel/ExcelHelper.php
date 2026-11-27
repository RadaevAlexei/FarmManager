<?php

namespace common\helpers\Excel;

use Yii;
use common\helpers\DateHelper;
use common\models\Animal;
use common\models\AnimalGroup;
use common\models\Bull;
use common\models\Cow;
use common\models\Cowshed;
use common\models\Farm;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\helpers\ArrayHelper;

/**
 * Класс для работы с Excel
 * Class ExcelHelper
 * @package common\helpers
 */
class ExcelHelper
{
    const UPDATE_BATCH_SIZE = 300;
    /**
     * Читает лист worksheet. Стили и условное форматирование не загружаются:
     * в выгрузках бывают пустые диапазоны, из-за них PhpSpreadsheet на PHP 8 падает.
     *
     * @param string $filename
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private static function loadWorksheet($filename)
    {
        $reader = IOFactory::createReader(IOFactory::identify($filename));
        $reader->setLoadSheetsOnly('worksheet');
        $reader->setReadFilter(new WorksheetReadFilter());
        $reader->setReadDataOnly(true);

        return $reader->load($filename);
    }

    /**
     * @param string $filename
     * @return bool
     */
    public static function import($filename = '')
    {
        $result_import = true;

        $spreadsheet = self::loadWorksheet($filename);
        $animals = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($animals as $animal_data) {
            self::saveAnimalInDB($animal_data);
        }

        return $result_import;
    }

    /**
     * Сохранение животного в Базу Данных
     *
     * @param $animal_data
     */
    private static function saveAnimalInDB($animal_data)
    {
        $new_animal = new Animal([
            "scenario" => Animal::SCENARIO_CREATE_EDIT
        ]);

        $nickname = ArrayHelper::getValue($animal_data, "E");
        $label = ArrayHelper::getValue($animal_data, "F");
        $birthday_str = ArrayHelper::getValue($animal_data, "I");

        if (empty($birthday_str)) {
            return;
        }

        $birthday = DateHelper::convertFromTo("d.m.y", $birthday_str, "Y-m-d");
        $sex = self::identifySex($nickname);
        $physical_state = self::identifyPhysicalState($animal_data);
        $rectal_examination = self::identifyRectalExamination($animal_data);
        $status = self::identifyStatus($animal_data);

        $cowshed_name = ArrayHelper::getValue($animal_data, "C");
        $cowshed_id = self::getCowshedId($cowshed_name);

        $farm_name = ArrayHelper::getValue($animal_data, "H");
        $farm_id = self::getFarmId($farm_name);

        $new_animal->attributes = [
            "nickname" => $nickname,
            "label" => $label,
            "birthday" => $birthday,
            "sex" => $sex,
            "physical_state" => $physical_state,
            "status" => $status,
            "rectal_examination" => $rectal_examination,
            "cowshed_id" => $cowshed_id,
            "farm_id" => $farm_id,
        ];

        $new_animal->save();
    }

    /**
     * @param $filename
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function updateFields($filename)
    {
        $rows = self::readUpdateRows($filename);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $groupIds = self::ensureGroups(self::uniqueGroupNames($rows));
            foreach (array_chunk($rows, self::UPDATE_BATCH_SIZE) as $batch) {
                self::updateBatch($batch, $groupIds);
            }
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * Строки файла: бирка (F), ошейник (E), группа (D). Кличка не используется.
     * Лист читается построчно: полная загрузка PhpSpreadsheet на этой выгрузке не помещается в память.
     *
     * @param string $filename
     * @return array
     */
    private static function readUpdateRows($filename)
    {
        $table = (new XlsxColumnReader())->read($filename, 'worksheet', 3, ['D', 'E', 'F']);
        $rows = [];

        foreach ($table as $cells) {
            $label = $cells['F'];
            if (!self::isDigits($label)) {
                continue;
            }

            $rows[] = [
                'label' => (string)$label,
                'collar' => $cells['E'],
                'group' => $cells['D'],
            ];
        }

        return $rows;
    }

    /**
     * @param array $rows
     * @return array
     */
    private static function uniqueGroupNames(array $rows)
    {
        $names = [];
        foreach ($rows as $row) {
            if ($row['group'] === null || $row['group'] === '') {
                continue;
            }
            $name = (string)$row['group'];
            $names[$name] = $name;
        }

        return array_values($names);
    }

    /**
     * Бирка в файле должна состоять только из цифр. "13360." в обновление не берётся.
     *
     * @param mixed $value
     * @return bool
     */
    private static function isDigits($value)
    {
        if ($value === null || $value === '') {
            return false;
        }

        return preg_match('/^\d+$/', (string)$value) === 1;
    }

    /**
     * Ошейник — целое число. Пустое значение становится null.
     * false: в ячейке есть нецифровые символы, животное обновлять нельзя.
     *
     * @param mixed $value
     * @return int|null|false
     */
    private static function collarValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_int($value)) {
            return $value;
        }
        if (is_float($value)) {
            return floor($value) == $value ? (int)$value : false;
        }

        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }
        if (!preg_match('/^\d+$/', $value)) {
            return false;
        }

        return (int)$value;
    }

    /**
     * Группа в файле задана названием места, не id.
     *
     * @param array $names
     * @return array name => id
     */
    private static function ensureGroups(array $names)
    {
        if (!$names) {
            return [];
        }

        $ids = self::mapGroupIds($names);
        $missing = [];
        foreach ($names as $name) {
            if (!isset($ids[$name])) {
                $missing[] = [$name];
            }
        }

        if ($missing) {
            Yii::$app->db->createCommand()
                ->batchInsert(AnimalGroup::tableName(), ['name'], $missing)
                ->execute();
            $ids = self::mapGroupIds($names);
        }

        return $ids;
    }

    /**
     * @param array $names
     * @return array
     */
    private static function mapGroupIds(array $names)
    {
        $groups = AnimalGroup::find()
            ->select(['id', 'name'])
            ->where(['name' => $names])
            ->asArray()
            ->all();

        return ArrayHelper::map($groups, 'name', 'id');
    }

    /**
     * Одна запись на пачку.
     * Пустой ошейник записывается как null, нечисловой ошейник исключает животное из обновления.
     * Пустое место размещения группу не затирает.
     * Несколько животных с одной биркой обновляются каждое по своему id.
     *
     * @param array $batch
     * @param array $groupIds
     */
    private static function updateBatch(array $batch, array $groupIds)
    {
        $labels = [];
        $batchByLabel = [];
        foreach ($batch as $row) {
            $label = (string)$row['label'];
            if ($label === '') {
                continue;
            }
            $labels[] = $label;
            $batchByLabel[$label] = $row;
        }

        if (!$labels) {
            return;
        }

        $animals = Animal::find()
            ->select(['id', 'label', 'collar', 'animal_group_id'])
            ->where(['label' => $labels])
            ->asArray()
            ->all();

        $collars = [];
        $groups = [];
        $ids = [];
        foreach ($animals as $animal) {
            $label = (string)$animal['label'];
            if (!isset($batchByLabel[$label])) {
                continue;
            }
            $row = $batchByLabel[$label];
            $collar = self::collarValue($row['collar']);
            if ($collar === false) {
                continue;
            }

            $id = (int)$animal['id'];
            $changed = false;
            $currentCollar = ($animal['collar'] === null || $animal['collar'] === '') ? null : (int)$animal['collar'];
            if ($currentCollar !== $collar) {
                $collars[$id] = $collar;
                $changed = true;
            }

            $groupName = ($row['group'] === null || $row['group'] === '') ? '' : (string)$row['group'];
            if ($groupName !== '' && isset($groupIds[$groupName]) && (int)$animal['animal_group_id'] !== (int)$groupIds[$groupName]) {
                $groups[$id] = (int)$groupIds[$groupName];
                $changed = true;
            }

            if ($changed) {
                $ids[] = $id;
            }
        }

        if (!$ids) {
            return;
        }

        $sets = [];
        $params = [];
        if ($collars) {
            $case = 'collar = CASE id';
            foreach ($collars as $id => $value) {
                if ($value === null) {
                    $case .= ' WHEN ' . $id . ' THEN NULL';
                    continue;
                }
                $case .= ' WHEN ' . $id . ' THEN :c' . $id;
                $params[':c' . $id] = $value;
            }
            $sets[] = $case . ' ELSE collar END';
        }
        if ($groups) {
            $case = 'animal_group_id = CASE id';
            foreach ($groups as $id => $value) {
                $case .= ' WHEN ' . $id . ' THEN ' . $value;
            }
            $sets[] = $case . ' ELSE animal_group_id END';
        }

        $table = Yii::$app->db->quoteTableName(Animal::tableName());
        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets)
            . ' WHERE id IN (' . implode(',', $ids) . ')';
        Yii::$app->db->createCommand($sql, $params)->execute();
    }

    /**
     * @param $data
     * @throws \yii\db\Exception
     */
    public static function updateAnimalGroup($data)
    {
        $animalGroupName = ArrayHelper::getValue($data, "D");
        if (!empty($animalGroupName)) {
            $animalGroupExist = AnimalGroup::find()->where(['=', 'name', $animalGroupName])->exists();
            if (!$animalGroupExist) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $newAnimalGroup = new AnimalGroup([
                        'name' => $animalGroupName
                    ]);
                    $newAnimalGroup->save();

                    $transaction->commit();
                } catch (\Exception $exception) {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * @param $data
     */
    public static function updateField($data)
    {
        $label = ArrayHelper::getValue($data, 'F');
        if (!empty($label)) {
            /** @var Animal $animal */
            $animal = Animal::find()->where(['label' => $label])->one();
            if ($animal) {
                $updateParameters = [];

                $collar = ArrayHelper::getValue($data, 'E');
                if (!empty($collar)) {
                    $updateParameters = ['collar' => $collar];
                }

                $animalGroupName = ArrayHelper::getValue($data, 'D');
                if (!empty($animalGroupName)) {
                    $animalGroup = AnimalGroup::find()->where(['=', 'name', $animalGroupName])->one();
                    $animalGroupId = ArrayHelper::getValue($animalGroup, "id");
                    $updateParameters = array_merge(
                        $updateParameters,
                        ['animal_group_id' => $animalGroupId]
                    );
                }

                $animal->updateAttributes($updateParameters);
            }
        }
    }

    /**
     * Получение ID-коровника если он существует, если не существует то сначала создаём его и потом возвращаем его ID
     *
     * @param string $name
     * @return mixed|null
     */
    private static function getCowshedId($name = "")
    {
        if (empty($name)) {
            return null;
        }

        /** @var Cowshed $cowshed */
        $cowshed = Cowshed::find()
            ->where(['name' => $name])
            ->one();

        $result_id = null;

        if ($cowshed) {
            $result_id = $cowshed->id;
        } else {
            /** @var Cowshed $new_cowshed */
            $new_cowshed = new Cowshed([
                "scenario" => Cowshed::SCENARIO_CREATE_EDIT
            ]);

            $new_cowshed->attributes = [
                "name" => $name
            ];

            $new_cowshed->save();

            $result_id = $new_cowshed->id;
        }

        return $result_id;
    }

    /**
     * Получение ID-фермы если он существует, если не существует то сначала создаём его и потом возвращаем его ID
     *
     * @param string $name
     * @return mixed|null
     */
    private static function getFarmId($name = "")
    {
        if (empty($name)) {
            return null;
        }

        /** @var Farm $farm */
        $farm = Farm::find()
            ->where(['name' => $name])
            ->one();

        $result_id = null;

        if ($farm) {
            $result_id = $farm->id;
        } else {
            /** @var Farm $new_farm */
            $new_farm = new Farm([
                "scenario" => Farm::SCENARIO_CREATE_EDIT
            ]);

            $new_farm->attributes = [
                "name" => $name
            ];

            $new_farm->save();

            $result_id = $new_farm->id;
        }

        return $result_id;
    }

    /**
     * Определение пола животного
     * @param $nickname
     * @return int
     */
    private static function identifySex($nickname)
    {
        if (empty($nickname)) {
            return null;
        }

        return ($nickname === "бычок") ? Bull::ANIMAL_SEX_TYPE : Cow::ANIMAL_SEX_TYPE;
    }

    /**
     * Определение физиологического состояния
     *
     * @param $animal_data
     * @return int|null
     */
    private static function identifyPhysicalState($animal_data)
    {
        $physical_state = null;

        $nickname = ArrayHelper::getValue($animal_data, "E");

        if ($nickname === "бычок") {
            $physical_state = Animal::PHYSICAL_STATE_BULL; // бычок
        } else {
            $nematode = ArrayHelper::getValue($animal_data, "AR");
            $calving_count = (int)ArrayHelper::getValue($animal_data, "P");

            if ($nematode === "НЕТЕЛЬ") {
                $physical_state = Animal::PHYSICAL_STATE_CALF_NEMATODE;  // нетель
            } else {
                if (!empty($calving_count) && ($calving_count == 1)) {
                    $physical_state = Animal::PHYSICAL_STATE_CALF_FIRST_AID; // первотёлка
                } else {
                    if (!empty($calving_count) && ($calving_count > 1)) {
                        $physical_state = Animal::PHYSICAL_STATE_COW;            // корова
                    } else {
                        $age_month = (double)ArrayHelper::getValue($animal_data, "K");
                        if ($age_month <= 6) {
                            $physical_state = Animal::PHYSICAL_STATE_CALF;           // тёлочка
                        } else {
                            if (($age_month > 6) && ($age_month <= 12)) {
                                $physical_state = Animal::PHYSICAL_STATE_CALF_PREDSLUCH; // тёлочка предслучного возраста
                            } else {
                                $physical_state = Animal::PHYSICAL_STATE_CALF_SLUCH;     // тёлочка случного возраста
                            }
                        }
                    }
                }
            }
        }

        return $physical_state;
    }

    /**
     * Определение статуса
     *
     * @param $animal_data
     * @return int|null
     */
    private static function identifyStatus($animal_data)
    {
        $status = null;

        $insemination_count = (int)ArrayHelper::getValue($animal_data, "W");
        $rectal_examination = (int)ArrayHelper::getValue($animal_data, "Y");

        if ($rectal_examination == 5) {
            $status = Cow::STATUS_HUNT;
        } else {
            if (($insemination_count >= 1) && ($rectal_examination == 3)) {
                $status = Cow::STATUS_INSEMINATED;
            } else {
                if ($insemination_count == 0) {
                    $status = Cow::STATUS_NOT_INSEMINATED;
                }
            }
        }

        return $status;
    }

    /**
     * Определение Ректального исследования
     *
     * @param $animal_data - данные о животном
     * @return int|null - статус ректального исследования
     */
    private static function identifyRectalExamination($animal_data)
    {
        $rectal_examination = (int)ArrayHelper::getValue($animal_data, "Y");
        return (in_array($rectal_examination,
            Animal::$AVAILABLE_RECTAL_EXAMINATION_STATUSES)) ? $rectal_examination : null;
    }
}