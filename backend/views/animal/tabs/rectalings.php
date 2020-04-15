<?php

use \yii\helpers\Url;
use \yii\helpers\Html;
use common\models\Animal;
use \yii\helpers\ArrayHelper;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;
use \common\models\Rectal;

/**
 * @var Animal $animal
 * @var ArrayDataProvider $dataProviderRectal
 * @var mixed $usersList
 * @var mixed $rectalResults
 */

?>

<div class="box-header">
    <?= Html::button('Добавить РИ', [
        'class' => 'btn btn-warning',
        'disabled' => true,
        'data' => [
            'toggle' => 'modal',
            'target' => '#add-rectal-form-button',
        ]
    ]) ?>
</div>

<div class="box box-success">
    <div class="box-header with-border" style="background-color: #0ead0e78">
        <h3 class="box-title">История РИ</h3>
    </div>

    <div class="box-body">
        <?php echo GridView::widget([
                'formatter' => [
                    'class' => 'yii\i18n\Formatter',
                    'nullDisplay' => ''
                ],
                "dataProvider" => $dataProviderRectal,
                'summary' => false,
                'tableOptions' => [
                    'style' => 'display:block; width:100%; overflow-x:auto',
                    'class' => 'table table-striped'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Дата проведения',
                        'content'   => function ($model) {
                            return (new DateTime(ArrayHelper::getValue($model, 'date')))->format('d.m.Y');
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
                        'buttons' => [
                            'edit'   => function ($url, $model) {
                                return Html::button('<span class="glyphicon glyphicon-edit"></span>', [
                                    'id'    => 'edit-rectal-button',
                                    'class' => 'btn btn-warning btn-sm',
                                    'data'  => [
                                        'toggle' => 'modal',
                                        'url'    => Url::toRoute([
                                            'animal/edit-rectal-form',
                                            'id' => ArrayHelper::getValue($model, 'id')
                                        ])
                                    ]
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-trash"></span>',
                                    Url::toRoute([
                                        'animal/remove-rectal',
                                        'id' => ArrayHelper::getValue($model, 'id')
                                    ]),
                                    [
                                        'class' => 'btn btn-danger btn-sm',
                                        'data' => ['confirm' => 'Вы действительно хотите удалить это РИ?']
                                    ]
                                );
                            }
                        ],
                    ]
                ]
            ]
        ); ?>
    </div>

</div>

<!-- Модальное окно добавления ректального исследования -->
<div class="modal fade" id="add-rectal-form-button" tabindex="-1" role="dialog"
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
            <div class="modal-body">
                <?= $this->render('/animal/forms/add-rectal', compact(
                    'animal',
                    'usersList',
                    'rectalResults'
                )) ?>
            </div>
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