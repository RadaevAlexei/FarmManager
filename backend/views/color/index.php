<?php

use \yii\grid\GridView;
use \common\models\search\ColorSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;

$this->title = Yii::t('app/color', 'COLOR_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel ColorSearch */

?>

    <div class="form-group">
        <?php if (Yii::$app->user->can('animalColorEdit')) : ?>
            <?= Html::a(
                Yii::t('app/color', 'COLOR_ADD'),
                Url::toRoute(['color/new']),
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
        'short_name',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app/color', 'ACTIONS'),
            'template' => '<div class="btn-group"> {update} {delete} </div>',
            'visibleButtons' => [
                'update' => Yii::$app->user->can('animalColorEdit'),
                'delete' => Yii::$app->user->can('animalColorEdit'),
            ],
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="fas fa-edit"></span>',
                        Url::toRoute(['color/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="fas fa-trash"></span>',
                        Url::toRoute(['color/delete', 'id' => $model->id]),
                        ['class' => 'btn btn-danger']
                    );
                },
            ],
        ],
    ]
]);
