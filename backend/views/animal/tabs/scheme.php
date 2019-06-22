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

/**
 * @var Animal $animal
 * @var Scheme[] $schemeList
 * @var AppropriationScheme $appropriationScheme
 * @var AppropriationScheme $animalOnScheme
 * @var ActionHistory[] $actionsToday
 * @var bool $closeScheme
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
        'id' => 'update-health-form',
        'class' => 'form-horizontal',
    ]); ?>
    <div class="box-body">
        <div class="form-group">
            <?= $formHealth->field($healthModel, 'animal_id')->hiddenInput(['value' => $animal->id])->label(false) ?>
            <?= $formHealth->field($healthModel, 'health_status')->dropDownList(
                Animal::getHealthStatusList(),
                [
                    'id' => 'health_status_list',
                    'prompt' => 'Выберите статус здоровья',
                    'class' => 'form-control',
                    'value' => $animal->health_status,
                ]
            ) ?>
        </div>
        <div class="form-group">
            <?= $formHealth->field($healthModel, 'date_health')->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => ['class' => 'form-control', 'autocomplete' => 'off']
            ]) ?>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сменить состояние здоровья', [
            'class' => 'btn btn-primary',
            'data' => ['confirm' => 'Вы действительно хотите сменить состояние здоровья?']
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
            'id' => 'update-diagnoses-form',
            'class' => 'form-horizontal',
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
                        'class' => ['form-control'],
                        'value' => $animal->diagnosis,
                    ]
                ) ?>
            </div>
        </div>
        <div class="box-footer">
            <?= Html::submitButton('Поставить диагноз', [
                'class' => 'btn btn-primary',
                'data' => ['confirm' => 'Вы действительно хотите поставить такой диагноз?']
            ]) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>

<?php endif; ?>

<?php if ($animalOnScheme) :

    if ($closeScheme) :
        echo $this->render('/animal/tabs/close-scheme', [
            'appropriationScheme' => $animalOnScheme,
            'animal' => $animal,
        ]);
    else:
        echo $this->render('/animal/tabs/actions-today', [
            'actionsToday' => $actionsToday
        ]);
    endif;

else: ?>

    <div class="box box-success">
        <div class="box-header with-border" style="background-color: #0ead0e78">
            <h3 class="box-title">Поставить на схему лечения</h3>
        </div>

        <?php $form = ActiveForm::begin([
            'action' => Url::toRoute(['appropriation-scheme']),
            'id' => 'animal-form',
            'method' => 'post',
            'class' => 'form-horizontal'
        ]); ?>
        <div class="box-body">

            <div class="form-group">
                <div class="col-sm-10">
                    <?= $form->field($appropriationScheme, 'animal_id')->hiddenInput()->label(false); ?>
                    <?= $form->field($appropriationScheme, 'status')->hiddenInput()->label(false); ?>

                    <?= $form->field($appropriationScheme, 'started_at')->widget(DatePicker::class, [
                        'language' => 'ru',
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => [
                            'class' => 'form-control',
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
                            'class' => 'form-control',
                            'prompt' => 'Выберите схему'
                        ]
                    ) ?>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <?= Html::submitButton('Поставить на схему', [
                'class' => 'btn btn-primary',
                'data' => ['confirm' => 'Вы действительно хотите поставить на эту схему?']
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php endif; ?>
