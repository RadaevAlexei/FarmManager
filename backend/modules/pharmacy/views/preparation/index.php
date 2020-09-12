<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\pharmacy\models\search\PreparationSearch;
use \backend\modules\pharmacy\models\Preparation;

$this->title = Yii::t('app/preparation', 'PREPARATION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel PreparationSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">

                    <?php if (Yii::$app->user->can('managePharmacyEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                Yii::t('app/preparation', 'PREPARATION_ADD'),
                                Url::toRoute(['preparation/new']),
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
                                'attribute' => 'category',
                                'content' => function (Preparation $model) {
                                    return $model->getCategoryName();
                                }
                            ],
                            [
                                'attribute' => 'classification',
                                'content' => function (Preparation $model) {
                                    return $model->getClassificationName();
                                }
                            ],
                            [
                                'attribute' => 'beta',
                                'content' => function (Preparation $model) {
                                    return $model->getBetaClassificationName();
                                }
                            ],
                            [
                                'attribute' => 'danger_class',
                                'content' => function (Preparation $model) {
                                    return $model->getDangerClassName();
                                }
                            ],
                            [
                                'attribute' => 'measure',
                                'content' => function (Preparation $model) {
                                    return $model->getMeasureName();
                                }
                            ],
                            'volume',
                            'price',
                            [
                                'label' => 'Период выведения молока, Дни/Часы',
                                'content' => function (Preparation $model) {
                                    return implode("/", [$model->period_milk_day, $model->period_milk_hour]);
                                }
                            ],
                            [
                                'label' => 'Период выведения мяса, Дни/Часы',
                                'content' => function (Preparation $model) {
                                    return implode("/", [$model->period_meat_day, $model->period_meat_hour]);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/preparation', 'ACTIONS'),
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('managePharmacyEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['preparation/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
                                            Url::toRoute(['preparation/delete', 'id' => $model->id]),
                                            [
                                                'class' => 'btn btn-danger',
                                                'data' => ['confirm' => 'Вы действительно хотите удалить препарат?']
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
