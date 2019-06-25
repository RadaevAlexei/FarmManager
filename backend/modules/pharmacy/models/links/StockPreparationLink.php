<?php

namespace backend\modules\pharmacy\models\links;

use Yii;
use backend\modules\pharmacy\models\Preparation;
use backend\modules\pharmacy\models\Stock;
use yii\db\ActiveRecord;

/**
 * Class StockPreparationLink
 * @package backend\modules\scheme\models\links
 *
 * @property integer $stock_id
 * @property integer $preparation_id
 */
class StockPreparationLink extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%stock_preparation_link}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['stock_id', 'preparation_id'], 'required'],
            [['stock_id', 'preparation_id'], 'integer']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id_action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreparation()
    {
        return $this->hasOne(Preparation::class, ['id' => 'preparation_id']);
    }
}