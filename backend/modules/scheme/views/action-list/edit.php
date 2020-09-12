<?php

use \yii\bootstrap4\ActiveForm;
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
        'id' => 'action-list-form',
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
                        <button data-action-list-id="<?= $model->id ?>"
                                data-add-item-url="<?= Url::to(['add-new-item']) ?>"
                                id="add-action-item"
                                type="button"
                                class="btn btn-danger"
                                disabled="true">Добавить
                        </button>
                    </div>
                    <?= Html::textInput('delete', '', [
                        'id' => 'new-item',
                        'class' => 'form-control',
                        'disabled' => !Yii::$app->user->can('schemeManageEdit'),
                    ]) ?>
                </div>
                <div class="box box-solid">
                    <div class="box-body">
                        <ul id="list_block_item" style="list-style-type: none; padding-left: 0">
                            <?php foreach ($model->items as $item) : ?>
                                <li data-remove-url="<?= Url::to([
                                    'remove-item',
                                    'action_list_id' => $model->id,
                                    'item_id' => $item->id
                                ]) ?>" class="action-list-item">
                                    <div class="input-group input-group-sm">
                                        <?= Html::textInput(
                                            'items',
                                            ArrayHelper::getValue($item, "name"),
                                            ["class" => "form-control"]
                                        ) ?>
                                        <span class="input-group-btn">
                                            <?= Html::button('Удалить', [
                                                'remove-action-item' => '',
                                                'type' => 'button',
                                                'class' => 'btn btn-danger btn-flat',
                                                'disabled' => !Yii::$app->user->can('schemeManageEdit'),
                                            ]) ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?php if (Yii::$app->user->can('schemeManageEdit')) : ?>
            <?= Html::submitButton(Yii::t('app/action-list', 'EDIT'),
                ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
