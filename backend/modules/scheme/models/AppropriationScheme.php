<?php

namespace backend\modules\scheme\models;

use common\models\Animal;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class AppropriationScheme
 * @package backend\modules\scheme\models
 *
 * @property integer $id
 * @property integer $animal_id
 * @property integer $scheme_id
 * @property integer $status
 * @property \DateTime $started_at
 */
class AppropriationScheme extends ActiveRecord
{
    const STATUS_IN_PROGRESS = 0;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%appropriation_scheme}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'animal_id'  => Yii::t('app/appropriation-scheme', 'APPROPRIATION_SCHEME_ANIMAL'),
            'scheme_id'  => 'Схема лечения',
            'status'     => Yii::t('app/appropriation-scheme', 'APPROPRIATION_SCHEME_STATUS'),
            'started_at' => 'Дата применения схемы',
        ];
    }

    /**
     * @return array
     */
    /*public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['started_at']
                ],
                'value' => new Expression('NOW()'),
            ]
        ];
    }*/

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['animal_id', 'scheme_id', 'status'], 'integer'],
            [['animal_id', 'scheme_id', 'status', 'started_at'], 'required'],
            [['started_at'], 'safe']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScheme()
    {
        return $this->hasOne(Scheme::class, ['id' => 'scheme_id']);
    }

    /**
     *
     */
    public function removeFromScheme()
    {
        self::findOne([
            'animal_id' => $this->animal_id,
            'scheme_id' => $this->scheme_id,
        ])->delete();
    }
}