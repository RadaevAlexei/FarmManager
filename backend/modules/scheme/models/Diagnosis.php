<?php

namespace backend\modules\scheme\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Diagnosis
 * @package common\models
 *
 * @property $id integer
 * @property $name string
 */
class Diagnosis extends ActiveRecord
{
	/**
	 * Какое количество диагнозов будем выводиться на странице
	 */
	const PAGE_SIZE = 10;

	/**
	 * @return string
	 */
	public static function tableName()
	{
		return '{{%diagnosis}}';
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('app/diagnosis', 'DIAGNOSIS_NAME')
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
			[['name'], 'required'],
			[['name'], 'string', 'max' => 255]
		];
	}
}