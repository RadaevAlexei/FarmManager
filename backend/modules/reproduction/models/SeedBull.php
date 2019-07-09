<?php

namespace backend\modules\reproduction\models;

use Yii;
use common\models\Breed;
use common\models\Color;
use common\models\ContractorSeed;
use yii\db\ActiveRecord;

/**
 * Class SeedBull
 * @package backend\modules\reproduction\models
 *
 * @property integer $id
 * @property string $nickname
 * @property string $number_1
 * @property string $number_2
 * @property string $number_3
 * @property string $contractor
 * @property string $breed
 * @property string $color_id
 */
class SeedBull extends ActiveRecord
{
    /**
     * Какое количество семени быков будем выводить на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%seed_bull}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'nickname' => 'Кличка',
            'number_1' => 'Номер 1',
            'number_2' => 'Номер 2',
            'number_3' => 'Номер 3',
            'contractor' => 'Поставщик семени',
            'breed' => 'Порода',
            'color_id' => 'Масть',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'unique'],
            [['name', 'number_1', 'number_2', 'number_3'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['contractor', 'breed', 'color_id'], 'integer'],
        ];
    }

    /**
     * Получение списка семени быков
     * @return array|ActiveRecord[]
     */
    public static function getAllList()
    {
        return self::find()->all();
    }

    /**
     * @return mixed
     */
    public function getContractorName()
    {
        return ContractorSeed::getName($this->contractor);
    }

    /**
     * @return mixed
     */
    public function getBreedName()
    {
        return Breed::getName($this->breed);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::class, ['id' => 'color_id']);
    }

}