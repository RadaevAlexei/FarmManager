<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\scheme\models\Action;
use \backend\modules\scheme\models\ActionList;
use \backend\modules\scheme\assets\ActionAsset;
use common\models\TypeField;
use backend\modules\pharmacy\models\Preparation;

/**
 * @var Action $model
 * @var array $typeFieldList
 * @var ActionList[] $actionList
 * @var integer $typeList
 * @var integer $typeNumber
 * @var Preparation[] $preparationList
 */

ActionAsset::register($this);
$this->title = Yii::t('app/action', 'ACTION_NEW');

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['action/create']),
        'id' => 'action-form',
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'name')->textInput([
                    'autofocus' => true,
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'type')->dropDownList(
                    $typeFieldList,
                    [
                        'id' => 'selectTypeField',
                        'class' => 'form-control',
                        'prompt' => 'Выберите тип поля',
                        'data' => [
                            'type-list' => $typeList,
                            'type-number' => $typeNumber,
                        ]
                    ])
                ?>
            </div>
        </div>

        <div id="actionListBlock" class="form-group" style="display:none;">
            <div class="col-sm-12">
                <?= $form->field($model, 'action_list_id')->dropDownList(
                    $actionList,
                    [
                        'id' => 'selectList',
                        'class' => 'form-control',
                        'prompt' => 'Выберите список'
                    ])
                ?>
            </div>
        </div>

        <div class="form-group <?= ($model->type == TypeField::TYPE_NUMBER ? "" : "hidden") ?>"
             id="preparationListBlock">
            <div class="col-sm-12">
                <?= $form->field($model, 'preparation_id')->dropDownList(
                    $preparationList,
                    [
                        'class' => 'form-control',
                        'prompt' => 'Какой препарат привязать к этому действию?'
                    ])
                ?>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/action', 'ADD'),
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>