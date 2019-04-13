<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\pharmacy\models\Preparation;
use yii\jui\DatePicker;

/**
 * @var Preparation $model
 * @var array $packingList
 */

$this->title = Yii::t('app/preparation', 'PREPARATION_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['preparation/update', 'id' => $model->id]),
        'id' => 'preparation-form',
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Название препарата-->
        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'name')->textInput([
                    'autofocus' => true,
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/preparation', 'EDIT'),
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>