<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \common\models\search\AnimalGroupSearch;

$this->title = 'Список групп животных';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel AnimalGroupSearch
 * @var $dataProvider ActiveDataProvider
 */

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <?php if (Yii::$app->user->can('animalGroupEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                'Добавить группу',
                                Url::toRoute(['animal-group/new']),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                    <?php endif; ?>

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
                            'name',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Действия',
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'update' => Yii::$app->user->can('animalGroupEdit'),
                                    'delete' => Yii::$app->user->can('animalGroupEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['animal-group/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
                                            Url::toRoute(['animal-group/delete', 'id' => $model->id]),
                                            [
                                                'class' => 'btn btn-danger',
                                                'data' => ['confirm' => 'Вы действительно хотите удалить группу?']
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

