<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Action
 * @package backend\modules\scheme\models
 *
 * @property string $name
 * @property integer $type
 */
class Action extends ActiveRecord
{
    /**
     * Какое количество действий будет выводиться на странице
     */
    const PAGE_SIZE = 10;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%action}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/action', 'ACTION_NAME'),
            'type' => Yii::t('app/action', 'ACTION_TYPE')
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            [['name', 'type'], 'required'],
            ['name', 'string', 'max' => 255],
            ['type', 'integer']
        ];
    }
}