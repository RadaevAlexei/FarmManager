<?php

namespace backend\modules\pharmacy\models;

use common\models\Measure;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class Preparation
 * @package backend\modules\pharmacy\models
 *
 * @property string $name
 * @property integer $category
 * @property integer $period_milk_day
 * @property integer $period_milk_hour
 * @property integer $period_meat_day
 * @property integer $period_meat_hour
 * @property integer $danger_class
 * @property integer $classification
 * @property integer $beta
 * @property double $price
 * @property integer $measure
 * @property double $volume
 */
class Preparation extends ActiveRecord
{
    /**
     * Какое количество препаратов будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%preparation}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name'             => 'Название препарата',
            'category'         => 'Категория',
            'period_milk_day'  => 'Молоко/Дни',
            'period_milk_hour' => 'Молоко/Часы',
            'period_meat_day'  => 'Мясо/Дни',
            'period_meat_hour' => 'Мясо/Часы',
            'danger_class'     => 'Класс опасности',
            'classification'   => 'Классификация',
            'beta'             => 'Вид бета-лактамных',
            'price'            => 'Цена за ед.',
            'measure'          => 'Единица измерения',
            'volume'           => 'Объём',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'unique'],
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [
                [
                    'category',
                    'danger_class',
                    'period_milk_day',
                    'period_milk_hour',
                    'period_meat_day',
                    'period_meat_hour',
                    'classification',
                    'beta',
                    'price',
                    'volume',
                ],
                'double'
            ],
            [['measure'], 'integer'],
        ];
    }

    /**
     * Получение списка препаратов
     * @return array|ActiveRecord[]
     */
    public static function getAllList()
    {
        return self::find()->all();
    }

    /**
     * @return array
     */
    public static function getCategoryList()
    {
        return [
            1 => 'Антибактериальные средства',
            2 => 'НПВС',
            3 => 'Витамины',
            4 => 'Седативные средства',
            5 => 'Дезинфицирующие средства',
            6 => 'Инсептициды',
            7 => 'Противопаразитарные',
            8 => 'Вакцины',
            9 => 'Прочее',
        ];
    }

    /**
     * Классификация антибиотиков по химической структуре
     *
     * @return array
     */
    public static function getClassificationList()
    {
        return [
            1  => 'Бета-лактамные',
            2  => 'Макролиды',
            3  => 'Тетрациклины',
            4  => 'Производные диоксиаминофенилпропана(левомицетин)',
            5  => 'Аминогликозиды',
            6  => 'Полимиксины',
            7  => 'Полиеновые антибиотики (противогрибковые)',
            8  => 'Линкозамиды',
            9  => 'Фузидин',
            10 => 'Рифампицины (ансамакролиды)',
            11 => 'Гликопептиды (ванкомицин и тейкопланин)',
            12 => 'Ристомицин и др',
        ];
    }

    /**
     * Подтипы бета-лактамных антибиотиков
     *
     * @return array
     */
    public static function getBetaClassificationList()
    {
        return [
            1 => 'Пенициллины',
            2 => 'Цефалоспорины',
            3 => 'Монобактамы',
            4 => 'Карбопенемы'
        ];
    }

    /**
     * @return array
     */
    public static function getDangerClass()
    {
        return [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => "Не регламентируется в качестве <br> опасного материала",
        ];
    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return self::getCategoryList()[$this->category];
    }

    /**
     * @return mixed
     */
    public function getMeasureName()
    {
        return Measure::getList()[$this->measure];
    }

    /**
     * @return mixed
     */
    public function getClassificationName()
    {
        return self::getClassificationList()[$this->classification];
    }

    /**
     * @return mixed
     */
    public function getBetaClassificationName()
    {
        return self::getBetaClassificationList()[$this->beta];
    }

    /**
     * @return mixed
     */
    public function getDangerClassName()
    {
        return self::getDangerClass()[$this->danger_class];
    }
}