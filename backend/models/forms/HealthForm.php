<?php

namespace backend\models\forms;

use yii\base\Model;

/**
 * UploadForm is the model behind the upload form.
 */
class HealthForm extends Model
{
    public $health_status;
    public $diagnosis;
    public $animal_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['health_status', 'diagnosis', 'animal_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'health_status' => 'Состояние здоровья',
            'diagnosis'     => 'Диагноз',
        ];
    }
}