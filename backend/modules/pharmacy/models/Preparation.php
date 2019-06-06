<?php

namespace backend\modules\pharmacy\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Preparation
 * @package backend\modules\pharmacy\models
 *
 * @property string $name
 * @property integer $category
 * @property integer $period_milk
 * @property integer $period_meat
 * @property integer $danger_class
 * @property integer $classification
 * @property integer $beta
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
            'name'           => 'Название препарата',
            'category'       => 'Категория',
            'period_milk'    => 'Период выведения молока',
            'period_meat'    => 'Период выведения мяса',
            'danger_class'   => 'Класс опасности',
            'classification' => 'Вид антибиотика',
            'beta'           => 'Вид бета-лактамных',
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
            [['category', 'danger_class', 'period_milk', 'period_meat', 'classification', 'beta'], 'integer'],
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