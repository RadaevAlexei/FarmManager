<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 20.11.2018
 * Time: 0:42
 */

namespace backend\modules\scheme\controllers;

use backend\controllers\BackendController;

/**
 * Class DiagnosisController
 * @package backend\modules\scheme\controllers
 */
class DiagnosisController extends BackendController
{
	/**
	 * Страничка со списком диагнозов
	 */
	public function actionIndex()
	{
		return $this->render('index', [

		]);
	}
}