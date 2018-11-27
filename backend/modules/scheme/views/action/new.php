<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\scheme\models\Action;

/**
 * @var Action $model
 * @var array $typeFieldList
 */

$this->title = Yii::t('app/action', 'ACTION_NEW');

?>

<div class="box box-info">

	<?php $form = ActiveForm::begin(['action' => Url::toRoute(['action/create']), 'id' => 'action-form', 'class' => 'form-horizontal']); ?>
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
                <?= $form->field($model, 'type')->dropDownList(
                    $typeFieldList,
                    ['class' => 'form-control', 'prompt' => 'Выберите тип поля'])
                ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
		<?= Html::submitButton(Yii::t('app/action', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
	<?php ActiveForm::end(); ?>

</div>