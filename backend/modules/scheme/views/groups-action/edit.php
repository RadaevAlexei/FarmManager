<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\scheme\models\GroupsAction;
use \backend\modules\scheme\models\Action;
use \backend\modules\scheme\assets\GroupsActionAsset;

/**
 * @var GroupsAction $model
 * @var Action[] $actionList
 */

GroupsActionAsset::register($this);
$this->title = Yii::t('app/groups-action', 'GROUPS_ACTION_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-info">

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['groups-action/update', 'id' => $model->id]),
        'id'     => 'groups-action-form',
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

        <div class="form-group" id="list_block">
            <div class="col-sm-12">
                <label>Список действий: </label>
                <div class="input-group">
                    <div class="input-group-btn">
                        <button data-groups-action-id="<?= $model->id ?>"
                                data-add-action-url="<?= Url::to(['add-new-action']) ?>"
                                id="add-action"
                                type="button"
                                class="btn btn-primary"
                                disabled="true">Добавить
                        </button>
                    </div>
                    <?= Html::dropDownList('groups-action-list', null, $actionList, [
                        'class'  => 'form-control',
                        'prompt' => 'Выберите действие',
                    ]) ?>
                </div>
                <div class="box box-solid">
                    <div class="box-body">
                        <ul id="action_list_block" style="list-style-type: none; padding-left: 0">
                            <?php foreach ($model->actions as $action) :
                                echo $this->render("new-action", [
                                    "model"          => $action,
                                    "groupsActionId" => $model->id,
                                ]);
                            endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app/groups-action', 'EDIT'),
            ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>