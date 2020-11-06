<?php

use \yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use common\models\Animal;
use \yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\modules\scheme\models\Diagnosis;
use backend\models\forms\AnimalDiagnosisForm;

/**
 * @var AnimalDiagnosisForm $animalDiagnosisForm
 * @var Animal $animal
 * @var boolean $disabledChooseDiagnosis
 */

$cardClassesArr = [];
$cardClassesArr[] = $disabledChooseDiagnosis ? "bg-danger" : "card-success";
$cardClass = implode(" ", $cardClassesArr);

?>

<div class="card <?= $cardClass ?>">
    <div class="card-header">
        <h3 class="card-title">Выберите диагноз</h3>
    </div>

    <?php $formDiagnosis = ActiveForm::begin([
        'action' => Url::toRoute(['update-diagnoses']),
    ]); ?>

    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <?= $formDiagnosis->field($animalDiagnosisForm, 'diagnosis')->dropDownList(
                        ArrayHelper::map(Diagnosis::getAllList(), "id", "name"),
                        [
                            'prompt' => 'Выберите диагноз',
                            'class' => ['form-control form-control-sm'],
                            'disabled' => $disabledChooseDiagnosis,
                            'value' => $animal->diagnosis,
                        ]
                    ) ?>
                </div>
                <div class="form-group">
                    <?= $formDiagnosis->field($animalDiagnosisForm, 'animal_id')
                        ->hiddenInput(['value' => $animal->id])
                        ->label(false)
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <?php if (Yii::$app->user->can('animalEdit')) : ?>
            <?= Html::submitButton('Поставить диагноз', [
                'class' => 'btn btn-sm btn-warning',
                'disabled' => $disabledChooseDiagnosis,
                'data' => ['confirm' => 'Вы действительно хотите поставить такой диагноз?']
            ]) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
