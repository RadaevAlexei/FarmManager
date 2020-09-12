<?php

use \yii\helpers\Url;
use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \backend\modules\rectal\models\forms\RectalSettingsForm;
use \common\models\User;
use \yii\helpers\ArrayHelper;

/**
 * @var $dateFrom
 * @var $dateTo
 * @var array $usersList
 */

$model = new RectalSettingsForm();
$chiefVeterinarian = User::getChiefVeterinarian();
$model->chief_veterinarian = ArrayHelper::getValue($chiefVeterinarian, "id");

?>

<?php $form = ActiveForm::begin([
    'action' => Url::toRoute([
        '/rectal/rectal-list/download-rectal-list',
        'dateFrom' => $dateFrom,
        'dateTo'   => $dateTo
    ]),
    'id'     => 'rectal-form',
    'method' => 'post',
    'class'  => 'form-horizontal',
]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'gynecologist')->dropDownList(
                    $usersList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Укажите ветеринарного врача гинеколога'
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'chief_veterinarian')->dropDownList(
                    $usersList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Укажите главного ветеринарного врача'
                    ]
                ) ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton('Скачать отчёт', [
            'class' => 'btn btn-primary',
            'data'  => ['confirm' => 'Вы действительно хотите скачать отчёт?']
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>