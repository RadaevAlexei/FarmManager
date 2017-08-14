<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property string $firstName
 * @property string $lastName
 * @property string $middleName
 * @property integer $birthday
 * @property integer $gender
 * @property integer $position_id
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Количество пользователей на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Удаленный пользователь
     */
    const STATUS_DELETED = 0;

    /**
     * Активный пользователь
     */
    const STATUS_ACTIVE = 10;

    /**
     * Мужской пол
     */
    const GENDER_MALE = 0;
    /**
     * Женский пол
     */
    const GENDER_FEMALE = 1;

    /**
     * Сценарий при создании и редактировании пользователя
     */
    const SCENARIO_CREATE_EDIT = "create_edit";

    /**
     * Сценарий при фильтрации
     */
    const SCENARIO_FILTER = "filter";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => self::getUserStatuses()],

            [['firstName', 'lastName', 'middleName'], 'string'],
            [['firstName', 'lastName', 'middleName'], 'trim'],
            ['gender', 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE]],
            ['position_id', 'in', 'range' => Position::getAllPositions()],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username'   => 'Логин',
            'email'      => 'Почта',
            'status'     => 'Статус',
            'gender'     => 'Пол',
            'birthday'   => 'Дата Рождения',
            'position_id'   => 'Должность',
            'firstName'  => 'Имя',
            'lastName'   => 'Фамилия',
            'middleName' => 'Отчество',
        ];
    }

    /**
     * Получаем label пола пользователя
     * @return string
     */
    public function getGenderLabel()
    {
        return ($this->gender == self::GENDER_MALE) ? "Мужской" : "Женский";
    }

    /**
     * Список всех возможных статусов пользователя
     * @return array
     */
    public static function getUserStatuses()
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_DELETED
        ];
    }

    /**
     * Список полов
     * @return array
     */
    public static function getGenderList()
    {
        return [
            self::GENDER_MALE   => "Мужской",
            self::GENDER_FEMALE => "Женский"
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE_EDIT => [
                'username',
                'password'
            ],
            self::SCENARIO_FILTER      => [
                'username',
                'email',
                'fio',
                'birthday',
                'gender',
                'position_id',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Получение должности
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }

    /**
     * Получение название должности по связи
     * @return mixed
     */
    public function getPosName()
    {
        return $this->position_id->name;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
