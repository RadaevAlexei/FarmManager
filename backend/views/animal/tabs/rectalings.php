<?php

use \yii\helpers\Url;
use \yii\helpers\Html;
use common\models\Animal;
use \yii\helpers\ArrayHelper;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;
use common\models\rectal\Rectal;

/**
 * @var Animal $animal
 * @var ArrayDataProvider $dataProviderRectal
 * @var mixed $usersList
 * @var mixed $rectalResults
 * @var mixed $addRectal
 */

$rectalButtonText = "Провести РИ";
$stage = ArrayHelper::getValue($addRectal, 'stage.rectal_stage');

if (!empty($stage) && $stage > Rectal::STAGE_FIRST) {
    $rectalButtonText = "Подтверждение стельности №" . ($stage - 1);
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">История РИ</h3>
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->user->can('animalEdit')) : ?>
                        <?= Html::button($rectalButtonText, [
                            'id' => 'add-rectal-button',
                            'class' => 'btn btn-sm btn-warning',
                            'disabled' => ArrayHelper::getValue($addRectal, "disable", true),
                            'data' => [
                                'toggle' => 'modal',
                                'url' => Url::toRoute([
                                    'animal/add-rectal-form',
                                    'id' => ArrayHelper::getValue($addRectal, 'stage.rectal_id')
                                ])
                            ]
                        ]) ?>

                        <?= GridView::widget([
                                'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                                'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                                "dataProvider" => $dataProviderRectal,
                                'summary' => false,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'label' => 'Дата проведения',
                                        'content' => function ($model) {
                                            return (new DateTime(ArrayHelper::getValue($model,
                                                'date')))->format('d.m.Y');
                                        }
                                    ],
                                    [
                                        'label' => 'Этап',
                                        'content' => function ($model) {
                                            return Rectal::getStageLabel(ArrayHelper::getValue($model, 'rectal_stage'));
                                        }
                                    ],
                                    [
                                        'label' => 'Результат',
                                        'content' => function ($model) {
                                            return Rectal::getResultLabel(ArrayHelper::getValue($model, 'result'));
                                        }
                                    ],
                                    [
                                        'label' => 'Кто проводил?',
                                        'content' => function ($model) {
                                            return ArrayHelper::getValue($model, 'lastName');
                                        }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Действия',
                                        'template' => '<div class="btn-group">{edit} {delete}</div>',
                                        'visibleButtons' => [
                                            'edit' => Yii::$app->user->can('animalEdit'),
                                            'delete' => Yii::$app->user->can('animalEdit'),
                                        ],
                                        'buttons' => [
                                            'edit' => function ($url, $model) {
                                                return Html::button('<span class="fas fa-edit"></span>', [
                                                    'id' => 'edit-rectal-button',
                                                    'class' => 'btn btn-sm btn-warning',
                                                    'data' => [
                                                        'toggle' => 'modal',
                                                        'url' => Url::toRoute([
                                                            'animal/edit-rectal-form',
                                                            'id' => ArrayHelper::getValue($model, 'id')
                                                        ])
                                                    ]
                                                ]);
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                    '<span class="fas fa-trash"></span>',
                                                    Url::toRoute([
                                                        'animal/remove-rectal',
                                                        'id' => ArrayHelper::getValue($model, 'id')
                                                    ]),
                                                    [
                                                        'class' => 'btn btn-sm btn-danger',
                                                        'data' => ['confirm' => 'Вы действительно хотите удалить это РИ?']
                                                    ]
                                                );
                                            }
                                        ],
                                    ]
                                ]
                            ]
                        ); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно добавления ректального исследования -->
<div class="modal fade" id="add-rectal-modal" tabindex="-1" role="dialog"
     aria-labelledby="addRectalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="addRectalLabel">Добавление ректального исследования</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования РИ -->
<div class="modal fade" id="edit-rectal-modal" tabindex="-1" role="dialog"
     aria-labelledby="editRectalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="editRectalLabel">Редактирование ректального исследования</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
