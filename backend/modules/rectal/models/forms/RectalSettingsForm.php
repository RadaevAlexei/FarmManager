<?php

namespace backend\modules\rectal\models\forms;

use yii\base\Model;

/**
 * Class RectalSettingsForm
 * @package backend\modules\rectal\models\forms
 *
 * @property integer $gynecologist
 * @property integer $chief_veterinarian
 */
class RectalSettingsForm extends Model
{
    public $gynecologist;
    public $chief_veterinarian;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['gynecologist', 'chief_veterinarian'], 'required'],
            [['gynecologist', 'chief_veterinarian'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'gynecologist'       => 'Ветеринарный врач гинеколог',
            'chief_veterinarian' => 'Главный ветеринарный врач',
        ];
    }
}