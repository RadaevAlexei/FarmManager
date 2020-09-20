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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">

                    <?php if (Yii::$app->user->can('seedBullEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                'Создать быка',
                                Url::toRoute(['seed-bull/new']),
                                ['class' => 'btn btn-primary']
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
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('seedBullEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['seed-bull/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
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
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

