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

    /*public function rules()
    {
        return [
            [
                [
                    'number',
                    'birthday',
                    'gender',
                    'birthWeight'
                ],
                'required'
            ],
            ["gender", "string", ],
            [
                [
                    'id',
                    'nickname',
                    'group',
                    'previousWeighingDate',
                    'previousWeighing',
                    'lastWeighingDate',
                    'lastWeighing',
                    'color',
                    'motherId',
                    'fatherId',
                ],
                'safe'
            ],
        ];
    }*/
}