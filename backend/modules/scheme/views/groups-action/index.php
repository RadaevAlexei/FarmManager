<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\scheme\models\search\GroupsActionSearch;
use \backend\modules\scheme\assets\GroupsActionAsset;
use \backend\modules\scheme\models\GroupsAction;

GroupsActionAsset::register($this);
$this->title = Yii::t('app/groups-action', 'GROUPS_ACTION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $dataProvider ActiveDataProvider
 * @var $searchModel GroupsActionSearch
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
                                Yii::t('app/groups-action', 'GROUPS_ACTION_ADD'),
                                Url::toRoute(['groups-action/new']),
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
                                'label' => 'Действия',
                                'format' => 'raw',
                                'value' => function (GroupsAction $model) {
                                    $actions = "";
                                    foreach ($model->actions as $action) {
                                        $actions .= Html::tag("li", $action->name);
                                    }
                                    return Html::tag('ul', $actions);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/groups-action', 'ACTIONS'),
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('schemeManageEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['groups-action/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::button('<span class="fas fa-sm fa-trash"></span>', [
                                            "class" => "btn btn-danger remove-groups-action",
                                            'data' => [
                                                'url' => Url::toRoute(['groups-action/delete', 'id' => $model->id]),
                                            ]
                                        ]);
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
