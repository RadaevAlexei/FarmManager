<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use backend\modules\scheme\models\search\ActionListSearch;
use \common\models\TypeList;
use \backend\modules\scheme\models\ActionList;
use \backend\modules\scheme\assets\ActionListAsset;

ActionListAsset::register($this);

$this->title = Yii::t('app/action-list', 'ACTION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $dataProvider ActiveDataProvider
 * @var $searchModel ActionListSearch
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
                                Yii::t('app/action-list', 'ACTION_LIST_ADD'),
                                Url::toRoute(['action-list/new']),
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
                                'attribute' => 'type',
                                'value' => function (ActionList $model) {
                                    return TypeList::getType($model->type);
                                }
                            ],
                            [
                                'label' => 'Элементы списка',
                                'format' => 'raw',
                                'value' => function (ActionList $model) {
                                    $items = "";
                                    foreach ($model->items as $item) {
                                        $items .= Html::tag("li", $item->name);
                                    }
                                    return Html::tag('ul', $items);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/action-list', 'ACTIONS'),
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('schemeManageEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['action-list/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::button('<span class="fas fa-sm fa-trash"></span>', [
                                            "class" => "btn btn-danger remove-action-list",
                                            'data' => [
                                                'url' => Url::toRoute(['action-list/delete', 'id' => $model->id]),
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


