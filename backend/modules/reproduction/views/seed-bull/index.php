<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\reproduction\models\search\SeedBullSearch;
use backend\modules\reproduction\models\SeedBull;
use \yii\helpers\ArrayHelper;

$this->title = 'Список быков';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel SeedBullSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

    <div class="form-group">
        <?= Html::a(
            'Создать быка',
            Url::toRoute(['seed-bull/new']),
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

<?php echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel" => $searchModel,
    'tableOptions' => [
        'class' => 'table table-striped',
    ],
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '',
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'nickname',
        [
            'attribute' => 'birthday',
            'content' => function (SeedBull $model) {
                return (new DateTime($model->birthday))->format('d.m.Y');
            }
        ],
        'number_1',
        'number_2',
        'number_3',
        [
            'attribute' => 'contractor',
            'content' => function (SeedBull $model) {
                return ArrayHelper::getValue($model->supplier, "name");
            }
        ],
        [
            'attribute' => 'breed',
            'content' => function (SeedBull $model) {
                return $model->getBreedName();
            }
        ],
        [
            'attribute' => 'color_id',
            'content' => function (SeedBull $model) {
                return ArrayHelper::getValue($model->color, "name");
            }
        ],
        'price',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'template' => '<div class="btn-group">{update} {delete} </div>',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['seed-bull/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['seed-bull/delete', 'id' => $model->id]),
                        [
                            'class' => 'btn btn-danger',
                            'data' => ['confirm' => 'Вы действительно хотите удалить быка?']
                        ]
                    );
                },
            ],
        ],
    ]
]);