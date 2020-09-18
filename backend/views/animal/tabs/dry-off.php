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

<div class="box-header">
    <?= Html::button($rectalButtonText, [
        'id'       => 'add-rectal-button',
        'class'    => 'btn btn-warning',
        'disabled' => ArrayHelper::getValue($addRectal, "disable", true),
        'data'     => [
            'toggle' => 'modal',
            'url'    => Url::toRoute([
                'animal/add-rectal-form',
                'id' => ArrayHelper::getValue($addRectal, 'stage.rectal_id')
            ])
        ]
    ]) ?>
</div>

<div class="box box-success">
    <div class="box-header with-border" style="background-color: #0ead0e78">
        <h3 class="box-title">История РИ</h3>
    </div>

    <div class="box-body">
        <?php echo GridView::widget([
                'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                "dataProvider" => $dataProviderRectal,
                'summary'      => false,
                'columns'      => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label'   => 'Дата проведения',
                        'content' => function ($model) {
                            return (new DateTime(ArrayHelper::getValue($model, 'date')))->format('d.m.Y');
                        }
                    ],
                    [
                        'label'   => 'Этап',
                        'content' => function ($model) {
                            return Rectal::getStageLabel(ArrayHelper::getValue($model, 'rectal_stage'));
                        }
                    ],
                    [
                        'label'   => 'Результат',
                        'content' => function ($model) {
                            return Rectal::getResultLabel(ArrayHelper::getValue($model, 'result'));
                        }
                    ],
                    [
                        'label'   => 'Кто проводил?',
                        'content' => function ($model) {
                            return ArrayHelper::getValue($model, 'lastName');
                        }
                    ],
                    [
                        'class'    => 'yii\grid\ActionColumn',
                        'header'   => 'Действия',
                        'template' => '<div class="btn-group">{edit} {delete}</div>',
                        'buttons'  => [
                            'edit'   => function ($url, $model) {
                                return Html::button('<span class="fas fa-edit"></span>', [
                                    'id'    => 'edit-rectal-button',
                                    'class' => 'btn btn-sm btn-warning',
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
                                    '<span class="fas fa-trash"></span>',
                                    Url::toRoute([
                                        'animal/remove-rectal',
                                        'id' => ArrayHelper::getValue($model, 'id')
                                    ]),
                                    [
                                        'class' => 'btn btn-sm btn-danger',
                                        'data'  => ['confirm' => 'Вы действительно хотите удалить это РИ?']
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
