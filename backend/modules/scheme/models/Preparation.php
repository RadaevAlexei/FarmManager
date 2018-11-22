<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Preparation
 * @package backend\modules\scheme\models
 *
 * ПРЕПАРАТЫ
 *
 * @property string $name           - Название препарата
 * @property \DateTime $receipt_date  - Дата поступления
 * @property integer $packing       - Фасовка
 * @property double $volume         - Объём
 * @property double $price          - Стоимость
 *
 */
class Preparation extends ActiveRecord
{
	/**
	 * Какое количество препаратов будем выводить на странице
	 */
	const PAGE_SIZE = 10;

	/**
	 * @return string
	 */
	public static function tableName()
	{
		return '{{%preparation}}';
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return [
			'name'         => Yii::t('app/preparation', 'PREPARATION_NAME'),
			'receipt_date' => Yii::t('app/preparation', 'PREPARATION_RECEIPT_DATE'),
			'packing'      => Yii::t('app/preparation', 'PREPARATION_PACKING'),
			'volume'       => Yii::t('app/preparation', 'PREPARATION_VOLUME'),
			'price'        => Yii::t('app/preparation', 'PREPARATION_PRICE')
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
			[['packing'], 'integer'],
			[['receipt_date'], 'safe'],
			[['volume', 'price'], 'double'],
			[['name', 'packing', 'volume'], 'required'],
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * @return array|ActiveRecord[]
	 */
	public static function getAllList()
	{
		return Diagnosis::find()->all();
	}
}