<?php

use \yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use \yii\helpers\Html;
use \yii\jui\DatePicker;
use backend\modules\scheme\models\Scheme;
use backend\modules\scheme\models\AppropriationScheme;

/**
 * @var Scheme[] $schemeList
 * @var AppropriationScheme $appropriationScheme
 * @var boolean $disabledAppropriationScheme
 */

$cardClassesArr = [];
$cardClassesArr[] = $disabledAppropriationScheme ? "bg-danger" : "card-success";
$cardClass = implode(" ", $cardClassesArr);

?>

<div class="card <?= $cardClass ?>">
    <div class="card-header">
        <h3 class="card-title">Поставить на схему лечения</h3>
    </div>

    <?php $formOnScheme = ActiveForm::begin([
        'action' => Url::toRoute(['appropriation-scheme']),
    ]); ?>

    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <?= $formOnScheme->field($appropriationScheme, 'started_at')->widget(DatePicker::class, [
                    'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'class' => 'form-control form-control-sm',
                        'autocomplete' => 'off',
                        'disabled' => $disabledAppropriationScheme,
                    ],
                ]) ?>
            </div>
            <div class="col-sm-12">
                <?= $formOnScheme->field($appropriationScheme, 'scheme_id')->dropDownList(
                    $schemeList,
                    [
                        'class' => 'form-control form-control-sm',
                        'prompt' => 'Выберите схему',
                        'disabled' => $disabledAppropriationScheme,
                    ]
                ) ?>
                <?= $formOnScheme->field($appropriationScheme, 'animal_id')->hiddenInput()->label(false); ?>
                <?= $formOnScheme->field($appropriationScheme, 'status')->hiddenInput()->label(false); ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <?php if (Yii::$app->user->can('animalEdit')) : ?>
            <?= Html::submitButton('Поставить на схему', [
                'class' => 'btn btn-sm btn-warning',
                'disabled' => $disabledAppropriationScheme,
                'data' => ['confirm' => 'Вы действительно хотите поставить на эту схему?']
            ]) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
