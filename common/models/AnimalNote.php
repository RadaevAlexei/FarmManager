<?php

namespace common\models;

use Yii;
use DateTime;
use yii\db\ActiveRecord;

/**
 * Class AnimalNote
 * @package common\models
 *
 * ЗАМЕТКИ
 *
 * @property integer $id
 * @property integer $animal_id
 * @property integer $user_id
 * @property DateTime $date
 * @property string $description
 */
class AnimalNote extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%animal_note}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'animal_id' => 'Животное',
            'user_id' => 'Пользователь',
            'date' => 'Дата',
            'description' => 'Описание',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['animal_id', 'user_id', 'date'], 'required'],
            [['animal_id', 'user_id',], 'integer'],
            [['description'], 'string'],
            [['date'], 'safe']
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
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
