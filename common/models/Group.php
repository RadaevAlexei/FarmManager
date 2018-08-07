<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class Group
 * @package common\models
 *
 * @property string $name
 * @property integer $employeeId
 * @property integer $directorId
 * @property integer $mainZootechnicianId
 * @property integer $accountantId
 * @property integer $calfEmployeeId
 * @property integer $directorSecurityId
 */
class Group extends ActiveRecord
{
    /**
     * Количество групп на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Сценарий при создании и редактировании групп
     */
    const SCENARIO_CREATE_EDIT = "create_edit";

    /**
     * Сценарий при фильтрации
     */
    const SCENARIO_FILTER = "filter";

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'name'                => 'Название группы',
            'employeeId'          => 'Ответственный группы',
            'directorId'          => 'Исполнительный директор',
            'mainZootechnicianId' => 'Главный зоотехник',
            'accountantId'        => 'Бухгалтер',
            'calfEmployeeId'      => 'Телятник(ца)',
            'directorSecurityId'  => 'Начальник службы безопасности',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'unique'],
            [['name'], 'trim'],
            [
                [
                    'name',
                    'employeeId',
                    'directorId',
                    'mainZootechnicianId',
                    'accountantId',
                    'calfEmployeeId',
                    'directorSecurityId'
                ],
                'required',
                'on' => self::SCENARIO_CREATE_EDIT
            ],
            [
                [
                    'employeeId',
                    'directorId',
                    'mainZootechnicianId',
                    'accountantId',
                    'calfEmployeeId',
                    'directorSecurityId'
                ],
                'integer',
                'on' => self::SCENARIO_CREATE_EDIT
            ]
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_EDIT => [
                'name',
                'employeeId',
                'directorId',
                'mainZootechnicianId',
                'accountantId',
                'calfEmployeeId',
                'directorSecurityId'
            ],
            self::SCENARIO_FILTER      => [
                'name',
                'employeeId',
                'directorId',
                'mainZootechnicianId',
                'accountantId',
                'calfEmployeeId',
                'directorSecurityId'
            ],
        ];
    }

    /**
     * К какому сотруднику привязана группа
     * @return array|null|ActiveRecord
     */
    public function getEmployee()
    {
        return $this->hasOne(User::className(), ['id' => 'employeeId'])->one();
    }

    /**
     * Связь для получения данных о директоре
     * @return array|null|ActiveRecord
     */
    public function getDirector()
    {
        return $this->hasOne(User::className(), ['id' => 'directorId'])->one();
    }

    /**
     * Связь для получения данных о главном зоотехнике
     * @return array|null|ActiveRecord
     */
    public function getZootechnician()
    {
        return $this->hasOne(User::className(), ['id' => 'mainZootechnicianId'])->one();
    }

    /**
     * Связь для получения данных о бухгалтере
     * @return array|null|ActiveRecord
     */
    public function getAccountant()
    {
        return $this->hasOne(User::className(), ['id' => 'accountantId'])->one();
    }

    /**
     * Связь для получения данных о телятнике(це)
     * @return array|null|ActiveRecord
     */
    public function getCalfEmployee()
    {
        return $this->hasOne(User::className(), ['id' => 'calfEmployeeId'])->one();
    }

    /**
     * Связь для получения данных о начальнике службы безопасности
     * @return array|null|ActiveRecord
     */
    public function getDirectorSecurity()
    {
        return $this->hasOne(User::className(), ['id' => 'directorSecurityId'])->one();
    }
}