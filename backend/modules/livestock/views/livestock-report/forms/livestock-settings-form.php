<?php

use \yii\helpers\Url;
use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use backend\modules\livestock\models\forms\LivestockSettingsForm;

/**
 * @var LivestockSettingsForm $model
 * @var array $organizationTypes
 * @var array $usersList
 */
?>

<?php $form = ActiveForm::begin([
    'action' => Url::toRoute(['/livestock/livestock-report/download']),
    'id'     => 'livestock-form',
    'method' => 'post',
    'class'  => 'form-horizontal',
]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">

                <?= $form->field($model, 'dateFrom')
                    ->hiddenInput()
                    ->label(false)
                ?>

                <?= $form->field($model, 'dateTo')
                    ->hiddenInput()
                    ->label(false)
                ?>

                <?= $form->field($model, 'organization_type')->dropDownList(
                    $organizationTypes,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Укажите организацию'
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'department')
                    ->hiddenInput()
                    ->label(false)
                ?>

                <?= $form->field($model, 'department')->textInput(
                    [
                        'class'    => 'form-control',
                        'prompt'   => 'Укажите отделение',
                        'disabled' => true,
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'user_id')->dropDownList(
                    $usersList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Укажите зав. фермой'
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'livestock_specialist_id')->dropDownList(
                    $usersList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Укажите зоотехника'
                    ]
                ) ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton('Скачать отчёт', [
            'class' => 'btn btn-primary',
            'data'  => ['confirm' => 'Вы действительно хотите скачать отчёт?']
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>