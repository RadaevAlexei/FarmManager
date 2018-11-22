<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 22.11.2018
 * Time: 22:52
 */

namespace common\models;

/**
 * ФАСОВКА
 *
 * Class Packing
 * @package common\models
 */
class Packing
{
	// Флакон
	const BOTTLE = 1;

	// Канистра
	const CANISTER = 2;

	// Штука
	const THING = 3;

	// Шприц
	const SYRINGE = 4;

	/**
	 * Получение списка различных видов фасовок
	 * @return array
	 */
	public static function getList()
	{
		return [
			self::BOTTLE   => \Yii::t('app/packing', 'PACKING_BOTTLE'),
			self::CANISTER => \Yii::t('app/packing', 'PACKING_CANISTER'),
			self::THING    => \Yii::t('app/packing', 'PACKING_THING'),
			self::SYRINGE  => \Yii::t('app/packing', 'PACKING_SYRINGE'),
		];
	}

	/**
	 * @param $type
	 *
	 * @return mixed
	 */
	public static function getType($type)
	{
		return self::getList()[$type];
	}
}