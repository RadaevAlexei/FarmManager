<?php

use \yii\helpers\Url;
use \yii\bootstrap4\ActiveForm;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use \backend\modules\reproduction\models\Insemination;
use common\models\Animal;
use \yii\data\ArrayDataProvider;
use common\models\Calving;
use yii\helpers\ArrayHelper;

/**
 * @var Calving $editModel
 * @var ArrayDataProvider $dataProvider
 * @var mixed $statusesList
 * @var mixed $positionsList
 * @var mixed $usersList
 */

?>

<?php $form = ActiveForm::begin([
    'action' => Url::toRoute(['edit-calving', 'calvingId' => $editModel->id]),
    'id' => 'edit-calving-form',
    'method' => 'post',
    'class' => 'form-horizontal'
]); ?>

<div class="box-body">

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'animal_id')->hiddenInput()->label(false); ?>

            <?= $form->field($editModel, 'date')->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => [
                    'id' => "calving-date-{$editModel->id}",
                    'class' => 'form-control edit-calving-datepicker',
                    'autocomplete' => 'off'
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'status')->dropDownList(
                $statusesList,
                [
                    'class' => 'form-control',
                    'prompt' => 'Укажите лёгкость отёла'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'position')->dropDownList(
                $positionsList,
                [
                    'class' => 'form-control',
                    'prompt' => 'Укажите предлежание плода'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'note')->textInput(
                [
                    'class' => 'form-control',
                    'prompt' => 'Примечание'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'user_id')->dropDownList(
                ArrayHelper::map($usersList, "id", "lastName"),
                [
                    'class' => 'form-control',
                    'prompt' => 'Укажите кто проводил отёл'
                ]
            ) ?>
        </div>
    </div>

</div>

<div class="box-footer">
    <?= Html::submitButton('Редактировать', [
        'class' => 'btn btn-primary',
        'data' => ['confirm' => 'Вы действительно хотите отредактировать этот отёл?']
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
