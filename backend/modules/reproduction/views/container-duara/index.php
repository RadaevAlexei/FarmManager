<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use backend\modules\reproduction\models\search\ContainerDuaraSearch;

$this->title = 'Список сосудов';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel ContainerDuaraSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<div class="form-group">
    <?php if (Yii::$app->user->can('containerDuaraEdit')) : ?>
        <?= Html::a(
            'Добавить сосуд',
            Url::toRoute(['container-duara/new']),
            ['class' => 'btn btn-primary']
        ) ?>
    <?php endif; ?>
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
                    'visibleButtons' => [
                        'update' => Yii::$app->user->can('containerDuaraEdit'),
                        'delete' => Yii::$app->user->can('containerDuaraEdit'),
                    ],
                    'buttons'  => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                Url::toRoute(['container-duara/edit', 'id' => $model->id]),
                                ['class' => 'btn btn-warning']
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Url::toRoute(['container-duara/delete', 'id' => $model->id]),
                                [
                                    'class' => 'btn btn-danger',
                                    'data'  => ['confirm' => 'Вы действительно хотите удалить сосуд?']
                                ]
                            );
                        },
                    ],
                ],
            ]
        ]); ?>

    </div>
</div>
