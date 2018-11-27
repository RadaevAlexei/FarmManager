<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\scheme\models\search\GroupsActionSearch;

$this->title = Yii::t('app/groups-action', 'GROUPS_ACTION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $dataProvider ActiveDataProvider
 * @var $searchModel GroupsActionSearch
 */

?>

    <div class="form-group">
        <?= Html::a(
            Yii::t('app/groups-action', 'GROUPS_ACTION_ADD'),
            Url::toRoute(['groups-action/new']),
            [
                'class' => 'btn btn-primary'
            ]
        ) ?>
    </div>

<?php echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel"  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => Yii::t('app/groups-action', 'ACTIONS'),
            'template' => '<div class="btn-group">{update} {delete} </div>',
            'buttons'  => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['groups-action/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['groups-action/delete', 'id' => $model->id]),
                        ['class' => 'btn btn-danger']
                    );
                },
            ],
        ],
    ]
]);