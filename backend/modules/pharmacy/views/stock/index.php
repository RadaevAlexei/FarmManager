<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\pharmacy\models\search\StockSearch;

$this->title = 'Список складов';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel StockSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<div class="form-group">
    <?= Html::a(
        'Добавить склад',
        Url::toRoute(['stock/new']),
        ['class' => 'btn btn-primary']
    ) ?>
</div>

<div class="box box-info">
    <div class="box-body">

        <?php echo GridView::widget([
            "dataProvider" => $dataProvider,
            "filterModel"  => $searchModel,
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'header'   => 'Действия',
                    'template' => '<div class="btn-group">{update} {delete} </div>',
                    'buttons'  => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                Url::toRoute(['stock/edit', 'id' => $model->id]),
                                ['class' => 'btn btn-warning']
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Url::toRoute(['stock/delete', 'id' => $model->id]),
                                [
                                    'class' => 'btn btn-danger',
                                    'data'  => ['confirm' => 'Вы действительно хотите удалить склад?']
                                ]
                            );
                        },
                    ],
                ],
            ]
        ]); ?>

    </div>
</div>