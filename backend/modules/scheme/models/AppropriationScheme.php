<?php

namespace backend\modules\scheme\models;

use common\models\Animal;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class AppropriationScheme
 * @package backend\modules\scheme\models
 *
 * @property integer $animal_id
 * @property integer $scheme_id
 * @property integer $status
 * @property \DateTime $started_at
 */
class AppropriationScheme extends ActiveRecord
{
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
            'scheme_id'  => Yii::t('app/appropriation-scheme', 'APPROPRIATION_SCHEME_NAME'),
            'status'     => Yii::t('app/appropriation-scheme', 'APPROPRIATION_SCHEME_STATUS'),
            'started_at' => Yii::t('app/appropriation-scheme', 'APPROPRIATION_SCHEME_STARTED_AT'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['started_at']
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['animal_id', 'scheme_id', 'status'], 'integer'],
            [['animal_id', 'scheme_id', 'status', 'started_at'], 'required'],
            [['started_at'], 'datetime']
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
}