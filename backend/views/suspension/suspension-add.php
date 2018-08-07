<?php

use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use \yii\helpers\Url;
use \common\models\Functions;

$headerText = !empty($action) ? \Yii::t('app/back', 'SUSPENSION_' . strtoupper($action)) : "";
?>

<h2><?=$headerText?></h2>

<?php $form = ActiveForm::begin(['action' => $url, 'id' => 'suspension-form']); ?>
    <table class="table f-table-list table-striped table-bordered">
        <tbody>
            <tr>
                <td>
                    <?=$form->field($model, 'calf')->textInput(["autofocus" => true])?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'date')->input("date")?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'weight')->textInput()?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="pull-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'suspension-button']) ?>
    </div>
<?php ActiveForm::end(); ?>