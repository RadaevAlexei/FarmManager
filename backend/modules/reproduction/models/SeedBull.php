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
 * @property \DateTime $birthday
 * @property string $number_1
 * @property string $number_2
 * @property string $number_3
 * @property string $contractor
 * @property string $breed
 * @property string $color_id
 * @property Color $color
 * @property double $price
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
            'birthday' => 'Дата Рождения',
            'number_1' => 'Номер 1',
            'number_2' => 'Номер 2',
            'number_3' => 'Номер 3',
            'contractor' => 'Поставщик семени',
            'breed' => 'Порода',
            'color_id' => 'Масть',
            'price' => 'Цена за ед.',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['nickname'], 'unique'],
            [['nickname', 'number_1', 'number_2', 'number_3'], 'trim'],
            [['nickname', 'number_1', 'contractor'], 'required'],
            [['nickname'], 'string', 'max' => 255],
            [['contractor', 'breed', 'color_id'], 'integer'],
            [['birthday'], 'safe'],
            [['price'], 'double'],
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