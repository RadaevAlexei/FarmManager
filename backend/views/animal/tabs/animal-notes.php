<?php

use \yii\helpers\ArrayHelper;
use yii\grid\GridView;
use \yii\data\ArrayDataProvider;
use \yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use common\models\AnimalNote;
use yii\helpers\Html;

/**
 * @var array $notes
 * @var AnimalNote $animalNoteModel
 */

$historyDataProvider = new ArrayDataProvider([
    'allModels' => $notes,
]);

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
                    <div class="col-sm-10">
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
                return ArrayHelper::getValue($action, "user.lastName");
            }
        ],
        [
            'label' => 'Описание',
            'value' => function ($action) {
                return ArrayHelper::getValue($action, "description");
            }
        ],
    ]
]); ?>
