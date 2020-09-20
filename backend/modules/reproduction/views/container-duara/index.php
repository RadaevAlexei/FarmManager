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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">

                    <?php if (Yii::$app->user->can('containerDuaraEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                'Добавить сосуд',
                                Url::toRoute(['container-duara/new']),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                    <?php endif; ?>

                    <?php echo GridView::widget([
                        "dataProvider" => $dataProvider,
                        "filterModel" => $searchModel,
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Действия',
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('containerDuaraEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['container-duara/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
                                            Url::toRoute(['container-duara/delete', 'id' => $model->id]),
                                            [
                                                'class' => 'btn btn-danger',
                                                'data' => ['confirm' => 'Вы действительно хотите удалить сосуд?']
                                            ]
                                        );
                                    },
                                ],
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
