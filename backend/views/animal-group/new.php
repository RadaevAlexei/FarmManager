<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\AnimalGroup;

/**
 * @var AnimalGroup $model
 */

$this->title = 'Добавление новой группы';

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['animal-group/create']),
        'id' => 'animal-group-form',
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <!--Название группы-->
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
        <?= Html::submitButton('Добавить',
            ['class' => 'btn btn-info pull-right', 'name' => 'animal-group-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>