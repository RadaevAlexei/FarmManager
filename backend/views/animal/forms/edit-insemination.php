<?php

use \yii\helpers\Url;
use \yii\bootstrap4\ActiveForm;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use \backend\modules\reproduction\models\Insemination;
use common\models\Animal;
use \yii\data\ArrayDataProvider;

/**
 * @var Insemination $editModel
 * @var ArrayDataProvider $dataProvider
 * @var Animal $animal
 * @var mixed $userList
 * @var mixed $seedBullList
 * @var mixed $containerDuaraList
 */

?>

<?php $form = ActiveForm::begin([
    'action' => Url::toRoute(['edit-insemination', 'inseminationId' => $editModel->id]),
    'id'     => 'edit-insemination-form',
    'method' => 'post',
    'class'  => 'form-horizontal'
]); ?>

<div class="box-body">

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'animal_id')->hiddenInput()->label(false); ?>

            <?= $form->field($editModel, 'date')->widget(DatePicker::class, [
                'language'   => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'    => [
                    'id'           => "insemination-date-{$editModel->id}",
                    'class'        => 'form-control edit-insemination-datepicker',
                    'autocomplete' => 'off'
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'user_id')->dropDownList(
                $userList,
                [
                    'class'  => 'form-control',
                    'prompt' => 'Кто проводил?'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'seed_bull_id')->dropDownList(
                $seedBullList,
                [
                    'class'  => 'form-control',
                    'prompt' => 'Выберите быка'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'container_duara_id')->dropDownList(
                $containerDuaraList,
                [
                    'class'  => 'form-control',
                    'prompt' => 'Выберите Сосуд Дьюара'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'count')->input(
                'number',
                [
                    'class'  => 'form-control',
                    'prompt' => 'Выберите быка',
                    'min'    => 1
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'type_insemination')->dropDownList(
                Insemination::getTypesInsemination(),
                [
                    'class'  => 'form-control',
                    'prompt' => 'Выберите ип осеменения?'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($editModel, 'comment')->textInput(
                [
                    'class'  => 'form-control',
                    'prompt' => 'Примечание'
                ]
            ) ?>
        </div>
    </div>
</div>

<div class="box-footer">
    <?= Html::submitButton('Редактировать', [
        'class' => 'btn btn-primary',
        'data'  => ['confirm' => 'Вы действительно хотите отредактировать это осеменение?']
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
