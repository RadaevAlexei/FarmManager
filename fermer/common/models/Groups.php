<?php

namespace common\models;


use yii\db\ActiveRecord;

/**
 * Class Groups
 * @package common\models
 */
class Groups extends ActiveRecord
{
    public function attributeLabels()
    {
        //TODO:: Сделать переводами
        return [
            'name' => 'Название группы',
            'employeeId' => 'Ответственный группы',
            'directorId' => 'Исполнительный директор',
            'mainZootechnicianId' => 'Главный зоотехник',
            'accountantId' => 'Бухгалтер',
            'calfEmployeeId' => 'Телятник(ца)',
            'directorSecurityId' => 'Начальник службы безопасности',
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
            [['name', 'employeeId', 'directorId', 'mainZootechnicianId', 'accountantId', 'calfEmployeeId', 'directorSecurityId'], 'required'],
            [['employeeId', 'directorId', 'mainZootechnicianId', 'accountantId', 'calfEmployeeId', 'directorSecurityId'], 'integer']
        ];
    }

    /**
     * К какому сотруднику привязана группа
     * @return array|null|ActiveRecord
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employeeId'])->one();
    }

    /**
     * Связь для получения данных о директоре
     * @return array|null|ActiveRecord
     */
    public function getDirector()
    {
        return $this->hasOne(Employee::className(), ['id' => 'directorId'])->one();
    }

    /**
     * Связь для получения данных о главном зоотехнике
     * @return array|null|ActiveRecord
     */
    public function getZootechnician()
    {
        return $this->hasOne(Employee::className(), ['id' => 'mainZootechnicianId'])->one();
    }

    /**
     * Связь для получения данных о бухгалтере
     * @return array|null|ActiveRecord
     */
    public function getAccountant()
    {
        return $this->hasOne(Employee::className(), ['id' => 'accountantId'])->one();
    }

    /**
     * Связь для получения данных о телятнике(це)
     * @return array|null|ActiveRecord
     */
    public function getCalfEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'calfEmployeeId'])->one();
    }

    /**
     * Связь для получения данных о начальнике службы безопасности
     * @return array|null|ActiveRecord
     */
    public function getDirectorSecurity()
    {
        return $this->hasOne(Employee::className(), ['id' => 'directorSecurityId'])->one();
    }
}