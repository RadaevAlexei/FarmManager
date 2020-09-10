<?php

use \yii\grid\GridView;
use \common\models\search\CowshedSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;

$this->title = Yii::t('app/cowshed', 'COWSHED_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel CowshedSearch */

?>

<div class="form-group">
    <?= Html::a(
        Yii::t('app/cowshed', 'COWSHED_ADD'),
        Url::toRoute(['cowshed/new']),
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
            'header'   => Yii::t('app/cowshed', 'ACTIONS'),
            'template' => '<div class="btn-group"> {detail} {update} {delete} </div>',
            'buttons'  => [
                'detail'   => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        Url::toRoute(['cowshed/detail', 'id' => $model->id]),
                        ['class' => 'btn btn-success']
                    );
                },
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['cowshed/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['cowshed/delete', 'id' => $model->id]),
                        ['class' => 'btn btn-danger']
                    );
                },
            ],
        ],
    ]
]);