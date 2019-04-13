<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\pharmacy\models\Stock;

/**
 * @var Stock $model
 */

$this->title = 'Добавление склада';

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['stock/create']),
        'id'     => 'stock-form',
        'class'  => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Название склада-->
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
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>