<?php

namespace backend\modules\scheme\models;

use Yii;
use common\models\Animal;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * Class AnimalHistory
 * @package backend\modules\scheme\models
 *
 *
 * @property integer $id
 * @property integer $animal_id
 * @property integer $user_id
 * @property \DateTime $date
 * @property string $action_type
 * @property string $action_text
 */
class AnimalHistory extends ActiveRecord
{
    const ACTION_TYPE_APPROPRIATION_SCHEME = 'appropriation_scheme';
    const ACTION_TYPE_DELETE_SCHEME = 'delete_scheme';
    const ACTION_TYPE_EXECUTE_ACTION = 'execute_action';
    const ACTION_TYPE_SET_HEALTH_STATUS = 'set_health_status';
    const ACTION_TYPE_SET_DIAGNOSIS = 'set_diagnosis';
    const ACTION_TYPE_CLOSE_SCHEME = 'close_scheme';
    const ACTION_TYPE_CREATE_INSEMINATION = 'create_insemination';
    const ACTION_TYPE_EDIT_INSEMINATION = 'edit_insemination';
    const ACTION_TYPE_DELETE_INSEMINATION = 'delete_insemination';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%animal_history}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'animal_id'   => 'Animal',
            'user_id'     => 'User',
            'date'        => 'Date',
            'action_type' => 'Type',
            'action_text' => 'Text',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['animal_id', 'user_id', 'date', 'action_type', 'action_text'], 'required'],
            [['action_type', 'action_type'], 'string'],
            [['animal_id', 'user_id',], 'integer'],
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