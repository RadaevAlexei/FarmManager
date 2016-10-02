<?php

namespace common\models;


use yii\db\ActiveRecord;

/**
 * Class Calf
 * @package common\models
 */
class Calf extends ActiveRecord
{
    /*public $id;
    public $number;
    public $nickname;
    public $group;
    public $birthday;
    public $gender;
    public $birthWeight;
    public $previousWeighingDate;
    public $previousWeighing;
    public $lastWeighingDate;
    public $lastWeighing;
    public $color;
    public $motherId;
    public $fatherId;*/

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