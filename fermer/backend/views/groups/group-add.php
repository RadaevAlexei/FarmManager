<?php

use \yii\widgets\ActiveForm;
use \yii\helpers\Html;

$headerText = !empty($action) ? \Yii::t('app/back', 'GROUP_' . strtoupper($action)) : "";
?>

<h4><?=$headerText?></h4>

<?php $form = ActiveForm::begin(['action' => $url, 'id' => 'contact-form']); ?>
<table class="table f-table-list table-striped table-hover table-condensed">
    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-9">
    </colgroup>

    <tbody>
    <tr>
        <td colspan="2"><?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <?=$form->field($model, 'employeeId')->dropDownList(
                $employees,
                ['prompt'=>'Выберите должность']
            )?>
        </td>
    </tr>
    </tbody>
</table>

<h4>Руководство</h4>
<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-5">
        <col class="col-xs-7">
    </colgroup>

    <tbody>
    <tr>
        <td colspan="2">
            <?=$form->field($model, 'directorId')->dropDownList(
                $employees,
                ['prompt'=>'Выберите должность']
            )?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?=$form->field($model, 'mainZootechnicianId')->dropDownList(
                $employees,
                ['prompt'=>'Выберите должность']
            )?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?=$form->field($model, 'accountantId')->dropDownList(
                $employees,
                ['prompt'=>'Выберите должность']
            )?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?=$form->field($model, 'calfEmployeeId')->dropDownList(
                $employees,
                ['prompt'=>'Выберите должность']
            )?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?=$form->field($model, 'directorSecurityId')->dropDownList(
                $employees,
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