<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\scheme\models\search\DiagnosisSearch;

$this->title = Yii::t('app/diagnosis', 'DIAGNOSIS_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider ActiveDataProvider */
/** @var $searchModel DiagnosisSearch */

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <?php if (Yii::$app->user->can('diagnosisEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                Yii::t('app/diagnosis', 'DIAGNOSIS_ADD'),
                                Url::toRoute(['diagnosis/new']),
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
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/diagnosis', 'ACTIONS'),
                                'template' => '<div class="btn-group"> {update} {delete} </div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('diagnosisEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['diagnosis/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
                                            Url::toRoute(['diagnosis/delete', 'id' => $model->id]),
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
