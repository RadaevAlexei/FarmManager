<?php

namespace backend\modules\scheme\models\links;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class SchemeDayLink
 * @package backend\modules\scheme\models
 *
 * @property integer $scheme_id
 * @property integer $scheme_day_id
 */
class SchemeDayLink extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%scheme_day_link}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['scheme_id', 'scheme_day_id'], 'required'],
            [['scheme_id', 'scheme_day_id'], 'integer']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScheme()
    {
        return $this->hasOne(Scheme::class, ['id' => 'scheme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchemeDay()
    {
        return $this->hasOne(SchemeDay::class, ['id' => 'scheme_day_id']);
    }
}