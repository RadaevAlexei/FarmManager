<?php

use \yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use common\models\Animal;
use backend\models\forms\HealthForm;
use \yii\jui\DatePicker;
use \yii\helpers\Html;

/**
 * @var HealthForm $healthModel
 * @var Animal $animal
 */

?>

<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">Контроль здоровья</h3>
    </div>

    <?php $formHealth = ActiveForm::begin([
        'action' => Url::toRoute(['update-health']),
    ]); ?>

    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?= $formHealth->field($healthModel, 'health_status')->dropDownList(
                        Animal::getHealthStatusList(),
                        [
                            'id' => 'health_status_list',
                            'prompt' => 'Выберите статус здоровья',
                            'class' => 'form-control form-control-sm',
                            'value' => $animal->health_status,
                        ]
                    ) ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <?= $formHealth->field($healthModel, 'health_status_comment')->textInput(
                        ['class' => 'form-control form-control-sm']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?= $formHealth->field($healthModel, 'date_health')->widget(DatePicker::class, [
                        'language' => 'ru',
                        'dateFormat' => 'dd.MM.yyyy',
                        'options' => ['class' => 'form-control form-control-sm', 'autocomplete' => 'off']
                    ]) ?>
                </div>
            </div>
            <div class="col-sm-6">
                <?= $formHealth->field($healthModel, 'animal_id')
                    ->hiddenInput(['value' => $animal->id])
                    ->label(false)
                ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <?php if (Yii::$app->user->can('animalEdit')) : ?>
            <?= Html::submitButton('Сменить состояние здоровья', [
                'class' => 'btn btn-sm btn-warning',
                'data' => ['confirm' => 'Вы действительно хотите сменить состояние здоровья?']
            ]) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
