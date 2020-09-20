<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\scheme\models\search\SchemeSearch;
use \backend\modules\scheme\models\Scheme;
use \yii\helpers\ArrayHelper;

$this->title = Yii::t('app/scheme', 'SCHEME_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider ActiveDataProvider */
/** @var $searchModel SchemeSearch */

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <?php if (Yii::$app->user->can('schemeManageEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                Yii::t('app/scheme', 'SCHEME_ADD'),
                                Url::toRoute(['scheme/new']),
                                [
                                    'class' => 'btn btn-primary'
                                ]
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
                                'attribute' => 'approve',
                                'content' => function (Scheme $model) {
                                    $class = ($model->approve === Scheme::APPROVED) ? "success" : "danger";
                                    return "<span class='label label-$class'>" . Scheme::getStatusScheme($model->approve) . "</span>";
                                }
                            ],
                            [
                                'attribute' => 'diagnosis_id',
                                'value' => function (Scheme $model) {
                                    return ArrayHelper::getValue($model, "diagnosis.name");
                                }
                            ],
                            [
                                'attribute' => 'created_by',
                                'value' => function (Scheme $model) {
                                    return ArrayHelper::getValue($model, "createdBy.username");
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => function (Scheme $model) {
                                    if (!empty($model->created_at)) {
                                        return date('d.m.Y H:i:s', $model->created_at);
                                    } else {
                                        return null;
                                    }
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/scheme', 'ACTIONS'),
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('schemeManageEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, Scheme $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['scheme/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, Scheme $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
                                            Url::toRoute(['scheme/delete', 'id' => $model->id]),
                                            [
                                                'class' => 'btn btn-danger',
                                                'data' => ['confirm' => 'Вы действительно хотите удалить схему лечения?']
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

