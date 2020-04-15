<?php

use \yii\helpers\Url;
use \yii\bootstrap\ActiveForm;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use common\models\Rectal;
use yii\helpers\ArrayHelper;

/**
 * @var Rectal $editModel
 * @var mixed $usersList
 * @var mixed $rectalResults
 */

?>

<?php $form = ActiveForm::begin([
    'action' => Url::toRoute(['edit-rectal', 'id' => $editModel->id]),
    'id' => 'edit-rectal-form',
    'method' => 'post',
    'class' => 'form-horizontal'
]); ?>

<div class="box-body">

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'user_id')->dropDownList(
                ArrayHelper::map($usersList, "id", "lastName"),
                [
                    'class' => 'form-control',
                    'prompt' => 'Укажите кто проводил РИ'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'animal_id')->hiddenInput()->label(false); ?>

            <?= $form->field($editModel, 'date')->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => [
                    'id' => "rectal-date-{$editModel->id}",
                    'class' => 'form-control edit-rectal-datepicker',
                    'autocomplete' => 'off'
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'result')->dropDownList(
                $rectalResults,
                [
                    'class' => 'form-control',
                    'prompt' => 'Какой результат РИ?'
                ]
            ) ?>
        </div>
    </div>

</div>

<div class="box-footer">
    <?= Html::submitButton('Редактировать', [
        'class' => 'btn btn-primary',
        'data' => ['confirm' => 'Вы действительно хотите отредактировать это РИ?']
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
