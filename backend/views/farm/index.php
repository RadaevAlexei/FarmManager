<?php

use \yii\grid\GridView;
use \common\models\search\FarmSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;

$this->title = Yii::t('app/farm', 'FARM_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel FarmSearch */

?>

    <div class="form-group">
        <?php if (Yii::$app->user->can('farmEdit')) : ?>
            <?= Html::a(
                Yii::t('app/farm', 'FARM_ADD'),
                Url::toRoute(['farm/new']),
                [
                    'class' => 'btn btn-primary'
                ]
            ) ?>
        <?php endif; ?>
    </div>

<?php echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel" => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app/farm', 'ACTIONS'),
            'template' => '<div class="btn-group">{update} {delete} </div>',
            'visibleButtons' => [
                'update' => Yii::$app->user->can('farmEdit'),
                'delete' => Yii::$app->user->can('farmEdit'),
            ],
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['farm/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['farm/delete', 'id' => $model->id]),
                        ['class' => 'btn btn-danger']
                    );
                },
            ],
        ],
    ]
]);
