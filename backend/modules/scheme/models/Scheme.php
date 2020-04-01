<?php

namespace backend\modules\scheme\models;

use common\models\Animal;
use Yii;
use backend\modules\scheme\models\links\SchemeDayLink;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Scheme
 *
 * @package backend\modules\scheme\models
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $diagnosis_id
 *
 * @property Diagnosis $diagnosis
 * @property User $createdBy
 * @property Scheme $schemeDays
 * @property bool $approve
 * @property integer $status
 */
class Scheme extends ActiveRecord
{
    /**
     * Утвержденная схема
     */
    const APPROVED = 1;

    /**
     * Не утвержденная схема
     */
    const NOT_APPROVED = 0;

    /**
     * Какое количество схем будет выводиться на странице
     */
    const PAGE_SIZE = 10;

    /**
     * Статусы Схем
     */
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%scheme}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/scheme', 'SCHEME_NAME'),
            'created_by' => Yii::t('app/scheme', 'SCHEME_CREATED_BY'),
            'created_at' => Yii::t('app/scheme', 'SCHEME_CREATED_AT'),
            'diagnosis_id' => Yii::t('app/scheme', 'SCHEME_DIAGNOSIS'),
            'approve' => 'Подтвержденность схемы',
            'status' => 'Статус схемы',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ]
            ]
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
            [['created_by', 'diagnosis_id', 'status'], 'integer'],
            [['name', 'diagnosis_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['created_at'], 'safe'],
            [['approve'], 'boolean'],
        ];
    }

    /**
     * @return array
     */
    public static function getListStatusesScheme()
    {
        return [
            self::NOT_APPROVED => 'Не утвержденная',
            self::APPROVED => 'Утвержденная'
        ];
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function getStatusScheme($value)
    {
        return self::getListStatusesScheme()[$value];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosis()
    {
        return $this->hasOne(Diagnosis::class, ['id' => 'diagnosis_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Получение списка дней схемы
     * @return \yii\db\ActiveQuery
     */
    public function getSchemeDays()
    {
        return $this->hasMany(SchemeDay::class, ['id' => 'scheme_day_id'])
            ->viaTable(SchemeDayLink::tableName(), ['scheme_id' => 'id'])
            ->orderBy(['number' => SORT_ASC]);
    }

    /**
     * @return bool
     */
    public function canApproveButton()
    {
        if ($this->approve) {
            return false;
        }

        /** @var SchemeDay[] $days */
        $days = $this->schemeDays;

        foreach ($days as $day) {
            /** @var GroupsAction[] $groupsAction */
            $groupsAction = $day->groupsAction;
            foreach ($groupsAction as $group) {
                /** @var Action[] $actions */
                $actions = $group->actions;
                if ($actions) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Проверяем, есть ли по данной схеме невыполненные действия (статус = 'new')
     * @return bool
     */
    public function existNewActions()
    {
        return ActionHistory::find()
            ->alias('h')
            ->select(['a.scheme_id'])
            ->innerJoin(['a' => AppropriationScheme::tableName()], 'h.appropriation_scheme_id = a.id')
            ->innerJoin(['an' => Animal::tableName()], 'a.animal_id = an.id')
            ->innerJoin(['s' => self::tableName()], 'a.scheme_id = s.id')
            ->andWhere(['=', 'a.scheme_id', $this->id])
            ->andWhere(['=', 'a.status', AppropriationScheme::STATUS_IN_PROGRESS])
            ->andWhere(['=', 's.status', self::STATUS_ACTIVE])
            ->exists();
    }

    public function animalsOnNotExecutedSchemes()
    {
        return ActionHistory::find()
            ->alias('h')
            ->select(['an.id', 'an.label', 'an.nickname'])
            ->innerJoin(['a' => AppropriationScheme::tableName()], 'h.appropriation_scheme_id = a.id')
            ->innerJoin(['an' => Animal::tableName()], 'a.animal_id = an.id')
            ->innerJoin(['s' => self::tableName()], 'a.scheme_id = s.id')
            ->andWhere(['=', 'a.scheme_id', $this->id])
            ->andWhere(['=', 'a.status', AppropriationScheme::STATUS_IN_PROGRESS])
            ->andWhere(['=', 's.status', self::STATUS_ACTIVE])
            ->groupBy(['an.label'])
            ->asArray()
            ->all();
    }

    /**
     * @return array
     */
    public static function getAllList()
    {
        $list = Scheme::find()->where(['approve' => 1])->all();
        return ArrayHelper::map($list, "id", "name");
    }
}
