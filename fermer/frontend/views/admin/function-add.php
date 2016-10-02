<?php

use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use \yii\helpers\Url;

?>

<h2>Добавление должности</h2>

<?php $form = ActiveForm::begin(['action' => Url::toRoute(['/function/save']), 'id' => 'contact-form']); ?>
    <table class="table f-table-list table-striped table-hover table-condensed">
        <tbody>
            <tr>
                <td><?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="pull-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
