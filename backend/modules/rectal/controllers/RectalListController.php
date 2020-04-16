<?php

namespace backend\modules\rectal\controllers;

use Yii;
use backend\controllers\BackendController;

/**
 * Class RectalListController
 * @package backend\modules\rectal\controllers
 */
class RectalListController extends BackendController
{
    /**
     * Страничка списка животных попадающих под ректальное исследование
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}