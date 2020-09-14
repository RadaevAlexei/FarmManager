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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Какие данные хотите изменить?</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['action-list/update', 'id' => $model->id])]); ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?= $form->field($model, 'name')->textInput([
                                    'autofocus' => true,
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?= $form->field($model, 'type')->dropDownList(
                                    $typeList,
                                    ['class' => 'form-control form-control-sm', 'prompt' => 'Выберите тип списка'])
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" id="list_block">
                                <label>Пункты меню:</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button data-action-list-id="<?= $model->id ?>"
                                                data-add-item-url="<?= Url::to(['add-new-item']) ?>"
                                                id="add-action-item"
                                                type="button"
                                                class="btn btn-sm btn-primary"
                                                disabled="true">Добавить
                                        </button>
                                    </div>
                                    <?= Html::textInput('delete', '', [
                                        'id' => 'new-item',
                                        'class' => 'form-control form-control-sm',
                                        'disabled' => !Yii::$app->user->can('schemeManageEdit'),
                                    ]) ?>
                                </div>

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
                                                    ["class" => "form-control form-control-sm"]
                                                ) ?>
                                                <span class="input-group-append">
                                                    <?= Html::button('Удалить', [
                                                        'remove-action-item' => '',
                                                        'type' => 'button',
                                                        'class' => 'btn btn-sm btn-danger btn-flat',
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
                <div class="card-footer">
                    <?php if (Yii::$app->user->can('schemeManageEdit')) : ?>
                        <?= Html::submitButton(
                            Yii::t('app/action-list', 'EDIT'),
                            ['class' => 'btn btn-sm btn-primary']
                        ) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>