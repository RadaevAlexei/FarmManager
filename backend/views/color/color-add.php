<?php

use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use \yii\helpers\Url;

$headerText = !empty($action) ? \Yii::t('app/back', 'COLOR_' . strtoupper($action)) : "";
?>

<h2><?=$headerText?></h2>

<?php $form = ActiveForm::begin(['action' => $url, 'id' => 'color-form']); ?>
    <table class="table f-table-list table-striped table-hover table-condensed">
        <tbody>
            <tr>
                <td><?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?></td>
            </tr>
            <tr>
                <td><?= $form->field($model, 'short_name')->textInput() ?></td>
            </tr>
        </tbody>
    </table>

    <div class="pull-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'color-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
