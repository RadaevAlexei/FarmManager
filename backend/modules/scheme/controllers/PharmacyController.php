<?php

namespace backend\modules\scheme\controllers;

/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 20.11.2018
 * Time: 0:36
 */

use \backend\controllers\BackendController;

/**
 * Class SchemeController
 */
class PharmacyController extends BackendController
{
	/**
	 * Страничка аптеки
	 */
	public function actionIndex()
	{
		return $this->render('index', [

		]);
	}
}