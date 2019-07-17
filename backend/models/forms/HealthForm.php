<?php

namespace backend\models\forms;

use yii\base\Model;

/**
 * UploadForm is the model behind the upload form.
 */
class HealthForm extends Model
{
    public $health_status;
    public $health_status_comment;
    public $animal_id;
    public $date_health;
    public $comment;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['health_status', 'animal_id'], 'integer'],
            [['comment', 'health_status_comment'], 'string'],
            [['date_health'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'health_status'         => 'Состояние здоровья',
            'date_health'           => 'Дата',
            'comment'               => 'Комментарий',
            'health_status_comment' => 'Комментарий'
        ];
    }
}