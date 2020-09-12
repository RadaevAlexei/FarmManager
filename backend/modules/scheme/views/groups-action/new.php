<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\scheme\models\GroupsAction;

/**
 * @var GroupsAction $model
 */

$this->title = Yii::t('app/groups-action', 'GROUPS_ACTION_NEW');

?>

<div class="box box-info">

	<?php $form = ActiveForm::begin(['action' => Url::toRoute(['groups-action/create']), 'id' => 'groups-action-form', 'class' => 'form-horizontal']); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
				<?= $form->field($model, 'name')->textInput([
					'autofocus' => true,
					'class'     => 'form-control'
				]) ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
		<?= Html::submitButton(Yii::t('app/groups-action', 'ADD'), ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
	<?php ActiveForm::end(); ?>

</div>