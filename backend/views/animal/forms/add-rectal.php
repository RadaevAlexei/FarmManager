<?php

use \yii\helpers\Url;
use \yii\bootstrap4\ActiveForm;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use \yii\data\ArrayDataProvider;
use common\models\Animal;
use \common\models\rectal\Rectal;

/**
 * @var Rectal $model
 * @var ArrayDataProvider $dataProvider
 * @var Animal $animal
 * @var mixed $usersList
 * @var mixed $rectalResults
 */

?>

<?php $form = ActiveForm::begin([
    'action' => Url::toRoute(['update-rectal', 'id' => $model->id]),
    'id'     => 'rectal-form',
    'method' => 'post',
    'class'  => 'form-horizontal',
]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'user_id')->dropDownList(
                    $usersList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Укажите кто проводил РИ'
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'animal_id')->hiddenInput()->label(false); ?>

                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'language'   => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'options'    => [
                        'class'        => 'form-control add-rectal-datepicker',
                        'autocomplete' => 'off'
                    ]
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'result')->dropDownList(
                    $rectalResults,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Какой результат РИ?'
                    ]
                ) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?= Html::submitButton('Провести', [
            'class' => 'btn btn-primary',
            'data'  => ['confirm' => 'Вы действительно хотите провести Ректальное исследование?']
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>