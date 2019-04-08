<?php

namespace backend\models\forms;

use yii\base\Model;

/**
 * UploadForm is the model behind the upload form.
 */
class AnimalDiagnosisForm extends Model
{
    public $health_status = 1;
    public $animal_id;
    public $diagnosis;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['health_status', 'animal_id', 'diagnosis'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'diagnosis' => 'Диагноз',
        ];
    }
}