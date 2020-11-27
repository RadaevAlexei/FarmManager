<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;
use \yii\bootstrap4\ActiveForm;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use common\models\Animal;
use \yii\data\ArrayDataProvider;
use \common\models\Calving;

/**
 * @var Calving $model
 * @var ArrayDataProvider $dataProvider
 * @var Animal $animal
 * @var mixed $userList
 * @var mixed $seedBullList
 * @var mixed $containerDuaraList
 */

$model = new Calving([
    'animal_id' => $animal->id
]);

?>

<?php $form = ActiveForm::begin([
    'action' => Url::toRoute(['add-calving']),
    'id' => 'calving-form',
    'method' => 'post',
    'class' => 'form-horizontal',
]); ?>
<div class="box-body">

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($model, 'animal_id')->hiddenInput()->label(false); ?>

            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($model, 'number')->input(
                'number',
                [
                    'class' => 'form-control',
                    'min' => 1,
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($model, 'status')->dropDownList(
                Calving::getListStatuses(),
                [
                    'class' => 'form-control',
                    'prompt' => 'Укажите лёгкость отёла'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($model, 'position')->dropDownList(
                Calving::getListPositions(),
                [
                    'class' => 'form-control',
                    'prompt' => 'Укажите предлежание плода'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($model, 'note')->textInput(
                [
                    'class' => 'form-control',
                    'prompt' => 'Примечание'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= $form->field($model, 'user_id')->dropDownList(
                ArrayHelper::map(User::getAllList(), "id", "lastName"),
                [
                    'class' => 'form-control',
                    'prompt' => 'Укажите кто проводил отёл'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <?= Html::button('Добавить приплод', [
                'class' => 'btn btn-warning',
                'id' => 'add_calving_template',
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Приплод</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Пол</th>
                                <th>Мертвород</th>
                                <th>Бирка</th>
                                <th>Вес,кг</th>
                                <th style="width: 40px">Действие</th>
                            </tr>
                            </thead>
                            <tbody id="child_animals">
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box-footer">
    <?= Html::submitButton('Добавить', [
        'class' => 'btn btn-primary',
        'data' => ['confirm' => 'Вы действительно хотите добавить отёл?']
    ]) ?>
</div>
<?php ActiveForm::end(); ?>

<script type="text/html" id="newCalving">
    <tr class="calving_row_block">
        <td>
            <select class="form-control" name="Calving[child][<%-index%>][sex]">
                <option value="1">Бычок</option>
                <option value="2">Тёлочка</option>
            </select>
        </td>
        <td>
            <select class="form-control" name="Calving[child][<%-index%>][dead]">
                <option value="0">Нет</option>
                <option value="1">Да</option>
            </select>
        </td>
        <td>
            <input type="text" name="Calving[child][<%-index%>][label]" class="form-control">
        </td>
        <td>
            <input type="number" name="Calving[child][<%-index%>][weight]" class="form-control"
                   min="1"
            >
        </td>
        <td>
            <button
                    class="btn btn-danger"
                    type="button"
                    id="remove_calving"
            >
                <span class="fas fa-trash"></span>
            </button>
        </td>
    </tr>
</script>
