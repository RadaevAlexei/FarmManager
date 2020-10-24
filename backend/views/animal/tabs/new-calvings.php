<?php

use \yii\helpers\Url;
use \yii\helpers\Html;
use common\models\Animal;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;
use \hail812\adminlte3\widgets\Alert;
use common\models\Calving;
use yii\helpers\ArrayHelper;

/**
 * @var Animal $animal
 * @var ArrayDataProvider $dataProviderCalvings
 * @var ArrayDataProvider $dataProviderDistributedCalvings
 */


?>


<div class="container-fluid">
    <div class="row pb-2">
        <div class="col-md-12">
            <?php if (Yii::$app->user->can('animalEdit')) : ?>
                <?= Html::button('Добавить отёл', [
                    'class' => 'btn btn-success',
                    'data' => [
                        'toggle' => 'modal',
                        'target' => '#add-calving-form-button',
                    ]
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($dataProviderDistributedCalvings->getModels()) : ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-danger">

                    <div class="card-header">
                        <h3 class="card-title">Не распределенные отёлы</h3>
                    </div>

                    <div class="card-body">

                        <?= Alert::widget([
                            'title' => 'Внимание!',
                            'type' => 'warning',
                            'body' => 'В этих отёлах необходимо добавить номер отёла!',
                        ]) ?>

                        <?= GridView::widget([
                            "dataProvider" => $dataProviderDistributedCalvings,
                            'tableOptions' => [
                                'class' => 'table table-sm table-striped',
                            ],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'label' => 'Провёл',
                                    'value' => function ($model) {
                                        return ArrayHelper::getValue($model, 'lastname');
                                    }
                                ],
                                [
                                    'label' => 'Дата отёла',
                                    'value' => function ($model) {
                                        return (new DateTime($model['date']))->format('d.m.Y');
                                    }
                                ],
                                [
                                    'label' => 'Статус',
                                    'value' => function ($model) {
                                        $status = ArrayHelper::getValue($model, 'status');
                                        return Calving::getStatusLabel($status);
                                    }
                                ],
                                [
                                    'label' => 'Позиция во время отёла',
                                    'value' => function ($model) {
                                        $position = ArrayHelper::getValue($model, 'position');
                                        return Calving::getPositionLabel($position);
                                    }
                                ],
                                [
                                    'label' => 'Примечание',
                                    'value' => function ($model) {
                                        return ArrayHelper::getValue($model, 'note');
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app/color', 'ACTIONS'),
                                    'template' => '<div class="btn-group">{edit}</div>',
                                    'visibleButtons' => [
                                        'edit' => Yii::$app->user->can('animalEdit'),
                                    ],
                                    'buttons' => [

                                        'edit' => function ($url, $model) {
                                            return Html::button('<span class="fas fa-edit"></span>', [
                                                'id' => 'edit-calving-button',
                                                'class' => 'btn btn-sm btn-warning',
                                                'data' => [
                                                    'toggle' => 'modal',
                                                    'url' => Url::toRoute([
                                                        'animal/edit-calving-form',
                                                        'calvingId' => $model['calving_id']
                                                    ])
                                                ]
                                            ]);
                                        },
                                    ],
                                ],
                            ]
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($dataProviderCalvings->getModels()) : ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('calving-tabs', [
                    'dataProvider' => $dataProviderCalvings
                ]) ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно добавления отёла -->
<div class="modal fade" id="add-calving-form-button" tabindex="-1" role="dialog"
     aria-labelledby="addCalvingLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="addCalvingLabel">Добавление отёла</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->render('/animal/forms/add-calving', compact(
                    'animal'
                )) ?>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования отёла -->
<div class="modal fade" id="edit-calving-modal" tabindex="-1" role="dialog"
     aria-labelledby="editCalvingLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="editCalvingLabel">Редактирование отёла</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
