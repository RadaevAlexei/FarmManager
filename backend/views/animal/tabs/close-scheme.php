<?php

use \yii\widgets\ActiveForm;
use \yii\helpers\Url;
use \yii\helpers\Html;
use\common\models\Animal;
use \yii\jui\DatePicker;
use \backend\models\forms\CloseSchemeForm;
use \backend\modules\scheme\models\AppropriationScheme;

$closeForm = new CloseSchemeForm();

/**
 * @var Animal $animal
 * @var AppropriationScheme $appropriationScheme
 */
?>

<?php $formHealth = ActiveForm::begin([
    'action' => Url::toRoute(['close-scheme']),
    'method' => 'post',
    'id'     => 'update-health-by-scheme',
    'class'  => 'form-horizontal',
]); ?>
<div class="box-body">
    <div class="form-group">
        <?= $formHealth->field($closeForm, 'animal_id')->hiddenInput(['value' => $animal->id])->label(false) ?>
        <?= $formHealth->field($closeForm,
            'appropriation_scheme_id')->hiddenInput(['value' => $appropriationScheme->id])->label(false) ?>
        <?= $formHealth->field($closeForm, 'health_status')->dropDownList(
            AppropriationScheme::getHealthStatusList(),
            [
                'prompt' => 'С каким статусом выписать?',
                'class'  => 'form-control'
            ]
        ) ?>
    </div>
    <div class="form-group">
        <?= $formHealth->field($closeForm, 'date_health')->widget(DatePicker::class, [
            'language'   => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
            'options'    => ['class' => 'form-control', 'autocomplete' => 'off']
        ]) ?>
    </div>
    <div class="form-group">
        <?= $formHealth->field($closeForm, 'comment')->textInput([
            'prompt' => 'Что-то хотите отметить?',
            'class'  => 'form-control'
        ]) ?>
    </div>
</div>
<div class="box-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
    <?= Html::submitButton('Завершить схему', [
        'class' => 'btn btn-primary',
        'data'  => ['confirm' => 'Вы действительно хотите завершить схему?']
    ]) ?>
</div>
<?php ActiveForm::end() ?>
