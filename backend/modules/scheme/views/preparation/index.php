<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use common\models\Packing;
use \backend\modules\scheme\models\Preparation;
use \backend\modules\scheme\models\search\PreparationSearch;

$this->title = Yii::t('app/preparation', 'PREPARATION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel PreparationSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

    <div class="form-group">
        <?= Html::a(
            Yii::t('app/preparation', 'PREPARATION_ADD'),
            Url::toRoute(['preparation/new']),
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

<?php echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel" => $searchModel,
    'tableOptions' => [
//        'style' => 'display:block; width:100%; overflow-x:auto',
        'class' => 'table table-striped',
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app/preparation', 'ACTIONS'),
            'template' => '<div class="btn-group">{update} {delete} </div>',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['preparation/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
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
]);