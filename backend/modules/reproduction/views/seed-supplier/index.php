<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\reproduction\models\search\SeedSupplierSearch;

$this->title = 'Список поставщиков';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel SeedSupplierSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<div class="form-group">
    <?= Html::a(
        'Добавить поставщика',
        Url::toRoute(['seed-supplier/new']),
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
                                Url::toRoute(['seed-supplier/edit', 'id' => $model->id]),
                                ['class' => 'btn btn-warning']
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Url::toRoute(['seed-supplier/delete', 'id' => $model->id]),
                                [
                                    'class' => 'btn btn-danger',
                                    'data'  => ['confirm' => 'Вы действительно хотите удалить поставщика?']
                                ]
                            );
                        },
                    ],
                ],
            ]
        ]); ?>

    </div>
</div>