<?php

use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use \yii\helpers\Url;
use \common\models\Functions;

?>

<h2>Добавление взвешивания</h2>

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