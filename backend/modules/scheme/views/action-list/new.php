<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\scheme\models\ActionList;
use \backend\modules\scheme\assets\ActionListAsset;
use \yii\helpers\ArrayHelper;

/**
 * @var ActionList $model
 * @var array $typeList
 * @var array $listItems
 */

ActionListAsset::register($this);

$this->title = Yii::t('app/action-list', 'ACTION_LIST_NEW');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['action-list/create']),
        'id'     => 'action-list-form',
        'class'  => 'form-horizontal'
    ]); ?>
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
                    $typeList,
                    ['class' => 'form-control', 'prompt' => 'Выберите тип списка'])
                ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/action-list', 'ADD'),
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>