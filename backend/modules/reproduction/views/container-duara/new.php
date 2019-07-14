<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\reproduction\models\ContainerDuara;

/**
 * @var ContainerDuara $model
 */

$this->title = 'Добавление сосуда';

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['container-duara/create']),
        'id'     => 'container-duara-form',
        'class'  => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Название сосуда-->
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
        <?= Html::submitButton('Добавить',
            ['class' => 'btn btn-info pull-right', 'name' => 'add-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>