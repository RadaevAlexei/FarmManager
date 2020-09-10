<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\reproduction\models\ContainerDuara;

/**
 * @var ContainerDuara $model
 */

$this->title = 'Редактирование сосуда';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['container-duara/update', 'id' => $model->id]),
        'id' => 'container-duara-form',
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Название сосуда-->
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
        <?php if (Yii::$app->user->can('containerDuaraEdit')) : ?>
            <?= Html::submitButton('Редактировать',
                ['class' => 'btn btn-info pull-right', 'name' => 'edit-button']) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
