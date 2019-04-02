<?php

namespace backend\modules\scheme\models;

use common\models\TypeField;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class ActionHistory
 * @package backend\modules\scheme\models
 *
 * ЗНАЧЕНИЯ ДЕЙСТВИЙ
 *
 * @property integer $id
 * @property integer $scheme_id
 * @property integer $scheme_day_id
 * @property integer $groups_action_id
 * @property integer $action_id
 * @property string $text_value
 * @property integer $number_value
 * @property double $double_value
 * @property string $action_list_values
 * @property integer $scheme_day
 */
class ActionHistory extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_EXECUTED = 'executed';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%action_history}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'scheme_id'        => Yii::t('app/action-value', 'ACTION_VALUE_SCHEME'),
            'scheme_day_id'    => Yii::t('app/action-value', 'ACTION_VALUE_DAY'),
            'groups_action_id' => Yii::t('app/action-value', 'ACTION_VALUE_GROUPS_ACTION'),
            'action_id'        => Yii::t('app/action-value', 'ACTION_VALUE_ACTION'),
            'text_value'       => Yii::t('app/action-value', 'ACTION_VALUE_TEXT_VALUE'),
            'number_value'     => Yii::t('app/action-value', 'ACTION_VALUE_NUMBER_VALUE'),
            'double_value'     => Yii::t('app/action-value', 'ACTION_VALUE_DOUBLE_VALUE'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                ['appropriation_scheme_id', 'groups_action_id', 'action_id', 'scheme_day', 'created_at', 'updated_at'],
                'required'
            ],
            [['text_value'], 'string'],
            [
                [
                    'appropriation_scheme_id',
                    'groups_action_id',
                    'action_id',
                    'number_value',
                    'scheme_day',
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [['double_value'], 'double'],
            [['scheme_day_at', 'execute_at'], 'safe']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppropriationScheme()
    {
        return $this->hasOne(AppropriationScheme::class, ['id' => 'appropriation_scheme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupsAction()
    {
        return $this->hasOne(GroupsAction::class, ['id' => 'groups_action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Action::class, ['id' => 'action_id']);
    }

    /**
     * @param $type
     * @param $value
     */
    public function setValueByType($type, $value)
    {
        $column = "";
        switch ($type) {
            case TypeField::TYPE_NUMBER:
                $column = TypeField::TYPE_NUMBER_STRING;
                break;
            case TypeField::TYPE_TEXT:
                $column = TypeField::TYPE_TEXT_STRING;
                break;
            case TypeField::TYPE_LIST:
                $column = TypeField::TYPE_LIST_STRING;
                break;
        }

        if (!empty($column)) {
            $userId = Yii::$app->getUser()->getIdentity()->getId();

            $this->updateAttributes([
                $column      => $value,
                "status"     => ActionHistory::STATUS_EXECUTED,
                "updated_at" => $userId,
                "execute_at" => (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s')
            ]);
        }
    }
}