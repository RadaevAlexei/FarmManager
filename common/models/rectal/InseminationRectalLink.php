<?php

namespace common\models\rectal;

use backend\modules\reproduction\models\Insemination;
use common\models\Animal;
use Yii;
use Throwable;
use DateTime;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class InseminationRectalLink
 * @package common\models\rectal
 *
 * @property integer $id
 * @property integer $prev_id
 * @property integer $animal_id
 * @property integer $insemination_id
 * @property integer $rectal_id
 */
class InseminationRectalLink extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%insemination_rectal_link}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'prev_id'         => 'Prev ID',
            'animal_id'       => 'Животное',
            'insemination_id' => 'Осеменение',
            'rectal_id'       => 'Ректальное исследование'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['animal_id', 'insemination_id', 'rectal_id'], 'required'],
            [['prev_id', 'animal_id', 'insemination_id', 'rectal_id'], 'integer'],
        ];
    }

    /**
     * Животное
     * @return ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    /**
     * Осеменение
     * @return ActiveQuery
     */
    public function getInsemination()
    {
        return $this->hasOne(Insemination::class, ['id' => 'insemination_id']);
    }

    /**
     * Данные по ректальному исследованию
     * @return ActiveQuery
     */
    public function getRectal()
    {
        return $this->hasOne(Rectal::class, ['id' => 'rectal_id']);
    }

}