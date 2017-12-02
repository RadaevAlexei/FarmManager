<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class Animal
 * @package common\models
 *
 * @property string number
 * @property string farm_birth_id
 * @property integer birthday
 * @property integer сowshed_id
 * @property integer milker_id
 * @property string nickname
 * @property integer calving_count
 * @property integer inseminator_id
 * @property integer bull_semenator_id
 * @property integer insemination_count
 * @property integer rectal_examination
 * @property integer physical_state
 * @property integer rectal_examination_date_fact
 *
 *
 *
 * @property integer group_id
 * @property integer gender
 * @property double birthWeight
 * @property integer suit_id
 * @property integer mother_id
 * @property integer father_id
 */
class Animal extends ActiveRecord
{
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'number'                       => 'Уникальный номер',
            'farm_birth_id'                => 'Происхождение',
            'birthday'                     => 'Дата Рождения',
            'сowshed_id'                   => 'Коровник',
            'milker_id'                    => 'Дояр',
            'nickname'                     => 'Кличка',
            'calving_count'                => 'Количество отёлов',
            'inseminator_id'               => 'Техник-осеменатор',
            'bull_semenator_id'            => 'Бык осеменатор',
            'insemination_count'           => 'Количество осеменений',
            'rectal_examination'           => 'Ректальное исследование',
            'physical_state'               => 'Физическое состояние',
            'rectal_examination_date_fact' => 'Дата РИ, Факт',


            'groupId'     => 'Группа',
            'gender'      => 'Пол',
            'birthWeight' => 'Вес при рождении',
            'suit_id'     => 'Масть',
            'mother_id'   => 'Мать',
            'father_id'   => 'Отец',
        ];
    }

    /**
     * @return array
     */
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
                'required',
            ],
            ['birthWeight', 'double'],
            [
                [
                    'nickname',
                    'group_id',
                    'color',
                    'mother_id',
                    'father_id',
                ],
                'safe'
            ],
        ];
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%animal}}';
    }
}