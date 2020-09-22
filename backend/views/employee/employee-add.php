<?php

use \yii\bootstrap4\ActiveForm;
use \yii\bootstrap\Html;
use \yii\helpers\Url;
use \common\models\Functions;

$headerText = !empty($action) ? \Yii::t('app/back', 'EMPLOYEE_' . strtoupper($action)) : "";
?>

<h2><?=$headerText?></h2>

<?php $form = ActiveForm::begin(['action' => $url, 'id' => 'contact-form']); ?>
    <table class="table f-table-list table-striped table-bordered">
        <tbody>
            <tr>
                <td>
                    <?=$form->field($model, 'firstName')->textInput(['autofocus' => true])?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'lastName')->textInput()?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'middleName')->textInput()?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'gender')->dropDownList([
                        "0" => "Мужской",
                        "1" => "Женский"
                    ])?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'birthday')->input("date")?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'functionId')->dropDownList(
                        Functions::find()->select(['name', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
                        ['prompt'=>'Выберите должность']
                    )?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="pull-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
    </div>
<?php ActiveForm::end(); ?>