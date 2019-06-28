<?php

use \yii\helpers\Url;
use \common\models\Animal;
use \backend\modules\scheme\models\Scheme;
use \yii\bootstrap\ActiveForm;
use \backend\modules\scheme\models\AppropriationScheme;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use \yii\helpers\ArrayHelper;
use \backend\modules\scheme\models\ActionHistory;
use \backend\modules\scheme\models\Diagnosis;
use \backend\models\forms\HealthForm;
use \backend\models\forms\AnimalDiagnosisForm;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;

/**
 * @var Animal $animal
 * @var Scheme[] $schemeList
 * @var AppropriationScheme $appropriationScheme
 * @var ArrayDataProvider $dataProvider
 */

$healthModel = new HealthForm(['date_health' => $animal->date_health]);
$animalDiagnosisForm = new AnimalDiagnosisForm();

?>

<div class="box box-success">
    <div class="box-header with-border" style="background-color: #0ead0e78">
        <h3 class="box-title">Контроль здоровья</h3>
    </div>
    <?php $formHealth = ActiveForm::begin([
        'action' => Url::toRoute(['update-health']),
        'method' => 'post',
        'id'     => 'update-health-form',
        'class'  => 'form-horizontal',
    ]); ?>
    <div class="box-body">
        <div class="form-group">
            <?= $formHealth->field($healthModel, 'animal_id')->hiddenInput(['value' => $animal->id])->label(false) ?>
            <?= $formHealth->field($healthModel, 'health_status')->dropDownList(
                Animal::getHealthStatusList(),
                [
                    'id'     => 'health_status_list',
                    'prompt' => 'Выберите статус здоровья',
                    'class'  => 'form-control',
                    'value'  => $animal->health_status,
                ]
            ) ?>
        </div>
        <div class="form-group">
            <?= $formHealth->field($healthModel, 'date_health')->widget(DatePicker::class, [
                'language'   => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'    => ['class' => 'form-control', 'autocomplete' => 'off']
            ]) ?>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сменить состояние здоровья', [
            'class' => 'btn btn-primary',
            'data'  => ['confirm' => 'Вы действительно хотите сменить состояние здоровья?']
        ]) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>

<?php if ($animal->isSick()) : ?>

    <div class="box box-success">
        <div class="box-header with-border" style="background-color: #0ead0e78">
            <h3 class="box-title">Диагноз</h3>
        </div>
        <?php $formDiagnosis = ActiveForm::begin([
            'action' => Url::toRoute(['update-diagnoses']),
            'method' => 'post',
            'id'     => 'update-diagnoses-form',
            'class'  => 'form-horizontal',
        ]); ?>
        <div class="box-body">
            <div class="form-group">
                <?= $formDiagnosis->field($animalDiagnosisForm,
                    'animal_id')->hiddenInput(['value' => $animal->id])->label(false) ?>
            </div>
            <div class="form-group">
                <?= $formDiagnosis->field($animalDiagnosisForm, 'diagnosis')->dropDownList(
                    ArrayHelper::map(Diagnosis::getAllList(), "id", "name"),
                    [
                        'prompt' => 'Выберите диагноз',
                        'class'  => ['form-control'],
                        'value'  => $animal->diagnosis,
                    ]
                ) ?>
            </div>
        </div>
        <div class="box-footer">
            <?= Html::submitButton('Поставить диагноз', [
                'class' => 'btn btn-primary',
                'data'  => ['confirm' => 'Вы действительно хотите поставить такой диагноз?']
            ]) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>

<?php endif; ?>

<div class="box box-success">
    <div class="box-header with-border" style="background-color: #0ead0e78">
        <h3 class="box-title">Поставить на схему лечения</h3>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['appropriation-scheme']),
        'id'     => 'animal-form',
        'method' => 'post',
        'class'  => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($appropriationScheme, 'animal_id')->hiddenInput()->label(false); ?>
                <?= $form->field($appropriationScheme, 'status')->hiddenInput()->label(false); ?>

                <?= $form->field($appropriationScheme, 'started_at')->widget(DatePicker::class, [
                    'language'   => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options'    => [
                        'class'        => 'form-control',
                        'autocomplete' => 'off'
                    ]
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($appropriationScheme, 'scheme_id')->dropDownList(
                    $schemeList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Выберите схему'
                    ]
                ) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?= Html::submitButton('Поставить на схему', [
            'class' => 'btn btn-primary',
            'data'  => ['confirm' => 'Вы действительно хотите поставить на эту схему?']
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>


<div class="box box-success">
    <div class="box-header with-border" style="background-color: #0ead0e78">
        <h3 class="box-title">Животное находится на следующих схемах:</h3>
    </div>

    <div class="box-body">
        <?php echo GridView::widget([
            'formatter'    => [
                'class'       => 'yii\i18n\Formatter',
                'nullDisplay' => '',
            ],
            "dataProvider" => $dataProvider,
            'summary'      => false,
            'tableOptions' => [
                'style' => 'display:block; width:100%; overflow-x:auto',
                'class' => 'table table-striped',
            ],
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'scheme_id',
                    'content'   => function (AppropriationScheme $model) {
                        return Html::a(
                            ArrayHelper::getValue($model, "scheme.name"),
                            Url::toRoute(['scheme/scheme/edit/', 'id' => $model->scheme_id]),
                            ["target" => "_blank"]
                        );
                    }
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'header'   => '',
                    'template' => '<div class="btn-group">{close-scheme} {delete}</div>',
                    'buttons'  => [
                        'close-scheme' => function ($url, AppropriationScheme $model) {
                            return Html::button('Завершить схему', [
                                'id'       => 'close-form-button',
                                'class'    => 'btn btn-warning',
                                'data'     => [
                                    'toggle'                  => 'modal',
                                    'url'                     => Url::toRoute([
                                        'animal/close-scheme-form',
                                        'id' => $model->id
                                    ]),
                                    'animal_id'               => $model->animal_id,
                                    'appropriation_scheme_id' => $model->id,
                                ],
                                'disabled' => $model->getListNewActions() ? true : false,
                            ]);
                        },
                        'delete'       => function ($url, $model) {
                            return Html::a(
                                'Убрать со схемы',
                                Url::toRoute(['animal/remove-from-scheme', 'id' => $model->id]),
                                [
                                    'class' => 'btn btn-danger',
                                    'data'  => ['confirm' => 'Вы действительно хотите убрать животное со схемы?']
                                ]
                            );
                        },
                    ],
                ]
            ]
        ]); ?>
    </div>
</div>

<!-- Модальное окно завершения схемы -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="exampleModalLabel">Завершение схемы - выписка</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>