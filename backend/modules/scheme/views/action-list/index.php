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

    <div class="form-group">
        <?php if (Yii::$app->user->can('schemeManageEdit')) : ?>
            <?= Html::a(
                Yii::t('app/action-list', 'ACTION_LIST_ADD'),
                Url::toRoute(['action-list/new']),
                [
                    'class' => 'btn btn-primary'
                ]
            ) ?>
        <?php endif; ?>
    </div>

<?php echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel" => $searchModel,
    'tableOptions' => [
        'style' => 'display:block; width:100%; overflow-x:auto',
        'class' => 'table table-striped',
    ],
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
                'update' => Yii::$app->user->can('schemeManageEdit'),
                'delete' => Yii::$app->user->can('schemeManageEdit'),
            ],
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['action-list/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                        "class" => "btn btn-danger remove-action-list",
                        'data' => [
                            'url' => Url::toRoute(['action-list/delete', 'id' => $model->id]),
                        ]
                    ]);
                },
            ],
        ],
    ]
]);
