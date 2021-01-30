<?php

use \yii\helpers\ArrayHelper;
use yii\grid\GridView;
use \yii\data\ArrayDataProvider;
use \yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use common\models\AnimalNote;
use yii\helpers\Html;
use \yii\jui\DatePicker;

/**
 * @var array $notes
 * @var AnimalNote $animalNoteModel
 */

$historyDataProvider = new ArrayDataProvider([
    'allModels' => $notes,
]);

$animalNoteModel->date = (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('d.m.Y');

?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Добавьте заметку!</h3>
            </div>

            <?php $form = ActiveForm::begin([
                'action' => Url::toRoute(['save-note']),
            ]); ?>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <?= $form->field($animalNoteModel, 'date')
                            ->widget(DatePicker::class, [
                                'language' => 'ru',
                                'dateFormat' => 'dd.MM.yyyy',
                                'options' => [
                                    'class' => 'form-control form-control-sm',
                                    'autocomplete' => 'off'
                                ]
                            ])->textInput([
                                'placeholder' => 'Дата',
                                'class' => 'form-control form-control-sm'
                            ])->label(false) ?>
                    </div>
                    <div class="col-sm-7">
                        <?= $form->field($animalNoteModel, 'description')->textInput([
                            'autofocus' => true,
                            'placeholder' => 'напишите что-нибудь...',
                            'class' => 'form-control form-control-sm'
                        ])->label(false) ?>
                    </div>
                    <div class="col-sm-2">
                        <?php if (Yii::$app->user->can('animalEdit')) : ?>
                            <?= Html::submitButton('Добавить', [
                                'class' => 'btn btn-sm btn-primary',
                                'data' => ['confirm' => 'Вы действительно хотите добавить эту заметку?']
                            ]) ?>
                        <?php endif; ?>

                        <?= $form->field($animalNoteModel, 'animal_id')->hiddenInput()->label(false); ?>
                        <?= $form->field($animalNoteModel, 'user_id')->hiddenInput()->label(false); ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?= GridView::widget([
    "dataProvider" => $historyDataProvider,
    'summary' => false,
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
    'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Дата',
            'value' => function ($action) {
                return (new DateTime(ArrayHelper::getValue($action, "date")))->format('d.m.Y H:i:s');
            }
        ],
        [
            'label' => 'Кто написал заметку?',
            'value' => function ($action) {
                return ArrayHelper::getValue($action, "user.username");
            }
        ],
        [
            'label' => 'Описание',
            'value' => function ($action) {
                return ArrayHelper::getValue($action, "description");
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'template' => '<div class="btn-group">{delete}</div>',
            'visibleButtons' => [
                'delete' => Yii::$app->user->can('animalEdit'),
            ],
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="fas fa-sm fa-trash"></span>',
                        Url::toRoute(['animal/remove-note', 'id' => $model->id]),
                        [
                            'class' => 'btn btn-danger',
                            'data' => ['confirm' => 'Вы действительно хотите удалить эту заметку?']
                        ]
                    );
                },
            ],
        ],
    ]
]); ?>
