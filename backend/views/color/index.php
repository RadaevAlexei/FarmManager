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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <?php if (Yii::$app->user->can('animalColorEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                Yii::t('app/color', 'COLOR_ADD'),
                                Url::toRoute(['color/new']),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                    <?php endif; ?>

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
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['color/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
                                            Url::toRoute(['color/delete', 'id' => $model->id]),
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
