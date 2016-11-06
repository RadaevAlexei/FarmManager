<?php

namespace common\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Employee
 * @package common\models
 */
class Employee extends ActiveRecord
{
    /**
     * Мужской пол
     */
    const GENDER_MALE = 0;
    /**
     * Женский пол
     */
    const GENDER_FEMALE = 1;

    /**
     * @return array
     */
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'firstName' => 'Фамилия',
            'lastName' => 'Имя',
            'middleName' => 'Отчество',
            'birthday' => 'Дата Рождения',
            'gender' => 'Пол',
            'functionId' => 'Должность'
        ];
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function getFunction()
    {
        return $this->hasOne(Functions::className(), ['id' => 'functionId']);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName', 'middleName', 'gender', 'functionId', 'birthday'], 'required'],

            [['firstName', 'lastName', 'middleName'], 'string', 'min' => 2, 'max' => 20],
            [['firstName', 'lastName', 'middleName'], 'trim'],
            ['gender', 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE]],
            ['functionId', 'in', 'range' => Functions::find()->select('id')->asArray()->column()],
//            ['birthday', 'date'],
        ];
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        $this->birthday = strtotime($this->birthday);
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }
}