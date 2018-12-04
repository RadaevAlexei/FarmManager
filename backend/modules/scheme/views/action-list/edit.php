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
 */

ActionListAsset::register($this);
$this->title = Yii::t('app/action-list', 'ACTION_LIST_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['action-list/update', 'id' => $model->id]),
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

        <div class="form-group" id="list_block">
            <div class="col-sm-12">
                <label>Пункты меню:</label>
                <div class="input-group">
                    <div class="input-group-btn">
                        <button data-url="<?= Url::to(['add-new-item']) ?>" id="add-action-item" type="button"
                                class="btn btn-danger" disabled="true">Добавить
                        </button>
                    </div>
                    <input id="new-item" type="text" class="form-control">
                </div>
                <div class="box box-solid">
                    <div class="box-body">
                        <ul id="list_block_item" style="list-style-type: none; padding-left: 15px;">
                            <?php foreach ($model->items as $item) : ?>
                                <li>
                                    <?= ArrayHelper::getValue($item, "name") ?> <i remove-action-item class='fa fa-minus-circle'></i>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/action-list', 'EDIT'),
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>