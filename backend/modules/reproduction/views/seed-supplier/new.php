<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\reproduction\models\SeedSupplier;

/**
 * @var SeedSupplier $model
 */

$this->title = 'Добавление поставщика';

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['seed-supplier/create']),
        'id'     => 'seed-supplier-form',
        'class'  => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Название поставщика-->
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
            ['class' => 'btn btn-info pull-right', 'name' => 'create-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>