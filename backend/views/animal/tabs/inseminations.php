<?php

use \yii\helpers\Url;
use \yii\helpers\Html;
use \backend\modules\reproduction\models\Insemination;
use common\models\Animal;
use \yii\helpers\ArrayHelper;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;

/**
 * @var ArrayDataProvider $dataProvider
 * @var Animal $animal
 * @var array $usersList
 * @var array $seedBullList
 * @var array $containerDuaraList
 * @var mixed $addRectal
 */

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">История осеменений</h3>
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->user->can('animalEdit')) : ?>
                        <?= Html::button('Добавить осеменение', [
                            'class'    => 'btn btn-warning',
                            'disabled' => !ArrayHelper::getValue($addRectal, "can-insemination") || $animal->canAddCalving(),
                            'data'     => [
                                'toggle' => 'modal',
                                'target' => '#add-insemination-form-button',
                            ]
                        ]) ?>
                    <?php endif; ?>

                    <?php echo GridView::widget([
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        "dataProvider" => $dataProvider,
                        'summary'      => false,
                        'columns'      => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            [
                                'attribute' => 'date',
                                'content'   => function (Insemination $model) {
                                    return (new DateTime($model->date))->format('d.m.Y');
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'content'   => function (Insemination $model) {
                                    if ($model->status == Insemination::STATUS_SEMINAL) {
                                        return "<span class='label label-success'>" . $model->getStatusLabel() . "</span>";
                                    }

                                    return "";
                                }
                            ],
                            [
                                'attribute' => 'user_id',
                                'content'   => function (Insemination $model) {
                                    return ArrayHelper::getValue($model, "user.lastName");
                                }
                            ],
                            'count',
                            [
                                'attribute' => 'type_insemination',
                                'content'   => function (Insemination $model) {
                                    return $model->getTypeInsemination();
                                }
                            ],
                            'comment',
                            [
                                'attribute' => 'seed_bull_id',
                                'content'   => function (Insemination $model) {
                                    return Html::a(
                                        ArrayHelper::getValue($model, "seedBull.nickname"),
                                        Url::toRoute(['reproduction/seed-bull/edit/', 'id' => $model->seed_bull_id]),
                                        ["target" => "_blank"]
                                    );
                                }
                            ],
                            [
                                'attribute' => 'container_duara_id',
                                'content'   => function (Insemination $model) {
                                    return Html::a(
                                        ArrayHelper::getValue($model, "containerDuara.name"),
                                        Url::toRoute(['reproduction/container-duara/edit/', 'id' => $model->container_duara_id]),
                                        ["target" => "_blank"]
                                    );
                                }
                            ],
                            [
                                'class'    => 'yii\grid\ActionColumn',
                                'header'   => 'Действия',
                                'template' => '<div class="btn-group">{edit} {delete}</div>',
                                'visibleButtons' => [
                                    'edit' => Yii::$app->user->can('animalEdit'),
                                    'delete' => Yii::$app->user->can('animalEdit'),
                                ],
                                'buttons'  => [
                                    'edit'   => function ($url, Insemination $model) {
                                        return Html::button('<span class="fas fa-edit"></span>', [
                                            'id'    => 'edit-insemination-button',
                                            'class' => 'btn btn-sm btn-warning',
                                            'data'  => [
                                                'toggle' => 'modal',
                                                'url'    => Url::toRoute([
                                                    'animal/edit-insemination-form',
                                                    'inseminationId' => $model->id
                                                ])
                                            ]
                                        ]);
                                    },
                                    'delete' => function ($url, Insemination $model) {
                                        return Html::a(
                                            '<span class="fas fa-trash"></span>',
                                            Url::toRoute(['animal/delete-insemination', 'id' => $model->id]),
                                            [
                                                'class' => 'btn btn-sm btn-danger',
                                                'data'  => ['confirm' => 'Вы действительно хотите удалить осеменение?'],
                                            ]
                                        );
                                    },
                                ],
                            ]
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно добавления осеменения -->
<div class="modal fade" id="add-insemination-form-button" tabindex="-1" role="dialog"
     aria-labelledby="addInseminationLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="addInseminationLabel">Добавление осеменения</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->render('/animal/forms/add-insemination', compact(
                    'animal',
                    'usersList',
                    'seedBullList',
                    'containerDuaraList'
                )) ?>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования осеменения -->
<div class="modal fade" id="edit-insemination-modal" tabindex="-1" role="dialog"
     aria-labelledby="editInseminationLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="editInseminationLabel">Редактирование осеменения</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
