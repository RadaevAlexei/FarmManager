<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\scheme\models\Diagnosis;
use backend\modules\scheme\models\Scheme;

/**
 * @var Scheme $model
 * @var Diagnosis[] $diagnosisList
 */

$this->title = Yii::t('app/scheme', 'SCHEME_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

	<?php $form = ActiveForm::begin(['action' => Url::toRoute(['scheme/update', 'id' => $model->id]), 'id' => 'scheme-form', 'class' => 'form-horizontal']); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
				<?= $form->field($model, 'name')->textInput([
					'autofocus' => true,
					'class'     => 'form-control'
				]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
				<?= $form->field($model, 'diagnosis_id')->dropDownList(
					$diagnosisList,
					['class' => 'form-control', 'prompt' => 'Выберите диагноз'])
				?>
            </div>
        </div>

        <div class="hidden">
	        <?= $form->field($model, 'created_by')->hiddenInput(
		        ['value' => $model->created_by, 'class' => 'hidden'])
	        ?>
			<?= $form->field($model, 'created_at')->hiddenInput(
				['value' => $model->created_at, 'class' => 'hidden'])
			?>
        </div>

    </div>

    <div class="box-footer">
		<?= Html::submitButton(Yii::t('app/scheme', 'EDIT'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
	<?php ActiveForm::end(); ?>

</div>