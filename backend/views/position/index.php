<?php

use \yii\grid\GridView;
use \common\models\search\PositionSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;

$this->title = Yii::t('app/position', 'POSITION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel PositionSearch */

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">

                <div class="card-body">
                    <?php if (Yii::$app->user->can('userPositionEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                Yii::t('app/position', 'POSITION_ADD'),
                                Url::toRoute(['position/new']),
                                [
                                    'class' => 'btn btn-primary'
                                ]
                            ) ?>
                        </div>
                    <?php endif; ?>


                    <?php echo GridView::widget([
                        "dataProvider" => $dataProvider,
                        "filterModel" => $searchModel,
                        'formatter' => [
                            'class' => 'yii\i18n\Formatter',
                            'nullDisplay' => '',
                        ],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name',
                            'short_name',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/position', 'ACTIONS'),
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('userPositionEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-edit"></span>',
                                            Url::toRoute(['position/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-trash"></span>',
                                            Url::toRoute(['position/delete', 'id' => $model->id]),
                                            ['class' => 'btn btn-danger']
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
