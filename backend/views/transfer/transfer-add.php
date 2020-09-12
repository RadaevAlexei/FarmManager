<?php

use \yii\bootstrap4\ActiveForm;
use \yii\bootstrap\Html;
use \yii\helpers\Url;

$headerText = !empty($action) ? \Yii::t('app/back', 'TRANSFER_' . strtoupper($action)) : "";
?>

<h2><?=$headerText?></h2>

<?php $form = ActiveForm::begin(['action' => $url, 'id' => 'transfer-form']); ?>
    <table class="table f-table-list table-striped table-hover table-condensed">
        <tbody>
            <tr>
                <td><?= $form->field($model, 'calf')->textInput(['autofocus' => true]) ?></td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'groupFromId')->dropDownList(
                        $groups,
                        ['prompt'=>'Выберите группу']
                    )?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'groupToId')->dropDownList(
                        $groups,
                        ['prompt'=>'Выберите группу']
                    )?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'date')->input("date")?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="pull-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'transfer-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
