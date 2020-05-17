<?php

namespace backend\modules\livestock\models\forms;

use yii\base\Model;

/**
 * Class LivestockSettingsForm
 * @package backend\modules\livestock\models\forms
 *
 * @property integer $organization_type
 * @property string $department
 * @property integer $livestock_specialist_id
 * @property integer $user_id
 * @property mixed $dateFrom
 * @property mixed $dateTo
 */
class LivestockSettingsForm extends Model
{
    public $organization_type = 0;
    public $department = 'МТФ';
    public $livestock_specialist_id;
    public $user_id;
    public $dateFrom;
    public $dateTo;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['organization_type', 'department', 'user_id', 'livestock_specialist_id'], 'required'],
            [['organization_type', 'user_id', 'livestock_specialist_id'], 'integer'],
            [['department', 'dateFrom', 'dateTo'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'organization_type'       => 'Организация',
            'department'              => 'Отделение',
            'livestock_specialist_id' => 'Зоотехник',
            'user_id'                 => 'Заведующий фермой (бригадир)',
            'dateFrom'                => 'Начало периода',
            'dateTo'                  => 'Конец периода',
        ];
    }
}