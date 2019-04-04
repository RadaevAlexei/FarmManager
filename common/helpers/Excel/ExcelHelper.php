<?php

namespace common\helpers\Excel;

use common\helpers\DateHelper;
use common\models\Animal;
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
    /**
     * @param string $filename
     * @return bool
     */
    public static function import($filename = '')
    {
        $result_import = true;

        $filter_subset = new WorksheetReadFilter();

        $input_file_type = IOFactory::identify($filename);
        
        $sheet_name = "worksheet";
        $reader = IOFactory::createReader($input_file_type);
        $reader->setLoadSheetsOnly($sheet_name);
        $reader->setReadFilter($filter_subset);
        $spreadsheet = $reader->load($filename);
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
            "nickname"           => $nickname,
            "label"              => $label,
            "birthday"           => $birthday,
            "sex"                => $sex,
            "physical_state"     => $physical_state,
            "status"             => $status,
            "rectal_examination" => $rectal_examination,
            "cowshed_id"         => $cowshed_id,
            "farm_id"            => $farm_id,
        ];

        $new_animal->save();
    }

    /**
     * @param $filename
     *
     * @return bool
     */
    public static function updateFields($filename)
    {
        $filter_subset = new WorksheetReadFilter();

        $input_file_type = IOFactory::identify($filename);

        $sheet_name = "worksheet";
        $reader = IOFactory::createReader($input_file_type);
        $reader->setLoadSheetsOnly($sheet_name);
        $reader->setReadFilter($filter_subset);
        $spreadsheet = $reader->load($filename);
        $animals = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($animals as $animal_data) {
            self::updateField($animal_data);
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
            if ($animal)  {
                $collar = ArrayHelper::getValue($data, 'E');
                if (!empty($collar)) {
                    $animal->updateAttributes(['collar' => $collar]);
                }
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
            } else if (!empty($calving_count) && ($calving_count == 1)) {
                $physical_state = Animal::PHYSICAL_STATE_CALF_FIRST_AID; // первотёлка
            } else if (!empty($calving_count) && ($calving_count > 1)) {
                $physical_state = Animal::PHYSICAL_STATE_COW;            // корова
            } else {
                $age_month = (double)ArrayHelper::getValue($animal_data, "K");
                if ($age_month <= 6) {
                    $physical_state = Animal::PHYSICAL_STATE_CALF;           // тёлочка
                } else if (($age_month > 6) && ($age_month <= 12)) {
                    $physical_state = Animal::PHYSICAL_STATE_CALF_PREDSLUCH; // тёлочка предслучного возраста
                } else {
                    $physical_state = Animal::PHYSICAL_STATE_CALF_SLUCH;     // тёлочка случного возраста
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
        } else if (($insemination_count >= 1) && ($rectal_examination == 3)) {
            $status = Cow::STATUS_INSEMINATED;
        } else if ($insemination_count == 0) {
            $status = Cow::STATUS_NOT_INSEMINATED;
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
        return (in_array($rectal_examination, Animal::AVAILABLE_RECTAL_EXAMINATION_STATUSES)) ? $rectal_examination : null;
    }
}