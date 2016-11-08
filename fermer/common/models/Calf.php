<?php

namespace common\models;

use yii\db\ActiveRecord;


/**
 * Class Calf
 * @package common\models
 */
class Calf extends ActiveRecord
{
    /**
     * Получение информации о масти
     * @return array|null|ActiveRecord
     */
    public function getSuit()
    {
        return $this->hasOne(Color::className(), ['id' => 'color'])->one();
    }

    public function getCalfGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'groupId'])->one();
    }

    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'number' => 'Уникальный номер',
            'nickname' => 'Кличка',
            'groupId' => 'Группа',
            'birthday' => 'Дата Рождения',
            'gender' => 'Пол',
            'birthWeight' => 'Вес при рождении',
            'previousWeighingDate' => 'Дата предыдущего взвешивания',
            'previousWeighing' => 'Предыдущее взвешивание',
            'currentWeighingDate' => 'Дата последнего взвешивания',
            'currentWeighing' => 'Последнее взвешивание',
            'color' => 'Масть',
            'motherId' => 'Мать',
            'fatherId' => 'Отец',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->birthday = strtotime($this->birthday);
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }

    public function rules()
    {
        return [
            ['number', 'unique'],
            [
                [
                    'number',
                    'birthday',
                    'gender',
                    'birthWeight'
                ],
                'required'
            ],
            ['birthWeight', 'double'],
            [
                [
                    'nickname',
                    'groupId',
                    'previousWeighingDate',
                    'previousWeighing',
                    'currentWeighingDate',
                    'currentWeighing',
                    'color',
                    'motherId',
                    'fatherId',
                ],
                'safe'
            ],
        ];
    }
}