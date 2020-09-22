<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use backend\modules\scheme\models\search\ActionSearch;
use backend\modules\scheme\models\Action;
use \common\models\TypeField;
use \yii\helpers\ArrayHelper;

$this->title = Yii::t('app/action', 'ACTION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $dataProvider ActiveDataProvider
 * @var $searchModel ActionSearch
 */

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <?php if (Yii::$app->user->can('schemeManageEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                Yii::t('app/action', 'ACTION_ADD'),
                                Url::toRoute(['action/new']),
                                [
                                    'class' => 'btn btn-primary'
                                ]
                            ) ?>
                        </div>
                    <?php endif; ?>

                    <?php echo GridView::widget([
                        "dataProvider" => $dataProvider,
                        "filterModel" => $searchModel,
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name',
                            [
                                'attribute' => 'type',
                                'value' => function (Action $model) {
                                    return TypeField::getType($model->type);
                                }
                            ],
                            [
                                'attribute' => 'action_list_id',
                                'format' => 'raw',
                                'value' => function (Action $model) {
                                    return Html::a(ArrayHelper::getValue($model, "actionList.name"),
                                        Url::to(['action-list/edit', 'id' => $model->action_list_id]));
                                }
                            ],
                            [
                                'attribute' => 'preparation_id',
                                'value' => function (Action $model) {
                                    return ArrayHelper::getValue($model, "preparation.name");
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/action', 'ACTIONS'),
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'update' => Yii::$app->user->can('schemeManageEdit'),
                                    'delete' => Yii::$app->user->can('schemeManageEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['action/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
                                            Url::toRoute(['action/delete', 'id' => $model->id]),
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


