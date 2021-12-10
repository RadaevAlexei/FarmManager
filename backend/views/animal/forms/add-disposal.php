<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Animal;
use backend\models\forms\CloseSchemeForm;
use backend\modules\scheme\models\AppropriationScheme;

$closeForm = new CloseSchemeForm();

/**
 * @var Animal $animal
 * @var AppropriationScheme $appropriationScheme
 */
?>

<?php $formHealth = ActiveForm::begin([
    'action' => Url::toRoute(['disposal']),
    'method' => 'post',
    'id'     => 'update-health-by-scheme',
    'class'  => 'form-horizontal',
]); ?>

<div class="box-body">
    <div class="form-group">
        <?= $formHealth->field($closeForm, 'health_status')->dropDownList(
            [],
            [
                'prompt' => 'С каким статусом выписать?',
                'class'  => 'form-control'
            ]
        ) ?>
    </div>
</div>

<div class="box-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
    <?= Html::submitButton('Применить', [
        'class' => 'btn btn-primary',
        'data'  => ['confirm' => 'Вы действительно хотите выполнить этой действие?']
    ]) ?>
</div>

<?php ActiveForm::end() ?>
