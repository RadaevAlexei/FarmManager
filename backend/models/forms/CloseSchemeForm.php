<?php

namespace backend\models\forms;

/**
 * Class CloseSchemeForm
 * @package backend\models\forms
 */
class CloseSchemeForm extends HealthForm
{
    public $appropriation_scheme_id;

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['appropriation_scheme_id'], 'integer'],
            ]
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'appropriation_scheme_id' => 'Применение схемы'
            ]
        );
    }
}