<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class BackendController
 * @package backend\controllers
 */
class BackendController extends Controller
{
    /**
     * @property int $pageSize Page pagination size
     */
    protected $pageSize = 10;

    /**
     * Controller actions
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    'access-control' => [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $status
     * @param string $value
     */
    protected function setFlash(string $status, string $value)
    {
        return Yii::$app->session->setFlash($status, $value);
    }

    /**
     * Get data provider
     *
     * @param mixed $query provider Query
     *
     * @return ActiveDataProvider
     */
    protected function getDataProvider($query)
    {
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
        ]);
    }
}
