<?php

use \yii\helpers\Url;
use \yii\bootstrap\ActiveForm;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use \backend\modules\reproduction\models\Insemination;
use common\models\Animal;
use \yii\data\ArrayDataProvider;

/**
 * @var Insemination $model
 * @var ArrayDataProvider $dataProvider
 * @var Animal $animal
 * @var mixed $userList
 * @var mixed $seedBullList
 * @var mixed $containerDuaraList
 */

$model = new Insemination([
    'animal_id' => $animal->id
]);

?>

<?php $form = ActiveForm::begin([
    'action' => Url::toRoute(['add-insemination']),
    'id'     => 'insemination-form',
    'method' => 'post',
    'class'  => 'form-horizontal'
]); ?>
<div class="box-body">

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($model, 'animal_id')->hiddenInput()->label(false); ?>

            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'language'   => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'    => [
                    'class'        => 'form-control',
                    'autocomplete' => 'off'
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($model, 'user_id')->dropDownList(
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
            <?= $form->field($model, 'seed_bull_id')->dropDownList(
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
            <?= $form->field($model, 'container_duara_id')->dropDownList(
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
            <?= $form->field($model, 'count')->input(
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
            <?= $form->field($model, 'type_insemination')->dropDownList(
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
            <?= $form->field($model, 'comment')->textInput(
                [
                    'class'  => 'form-control',
                    'prompt' => 'Примечание'
                ]
            ) ?>
        </div>
    </div>
</div>

<div class="box-footer">
    <?= Html::submitButton('Добавить', [
        'class' => 'btn btn-primary',
        'data'  => ['confirm' => 'Вы действительно хотите добавить осеменение?']
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
