<?php

use \yii\bootstrap4\ActiveForm;
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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Какие данные хотите изменить?</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['groups-action/update', 'id' => $model->id])]); ?>

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
                            <div class="form-group" id="list_block">
                                <label>Список действий: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button data-groups-action-id="<?= $model->id ?>"
                                                data-add-action-url="<?= Url::to(['add-new-action']) ?>"
                                                id="add-action"
                                                type="button"
                                                class="btn btn-sm btn-primary"
                                                disabled="true">Добавить
                                        </button>
                                    </div>
                                    <?= Html::dropDownList('groups-action-list', null, $actionList, [
                                        'class' => 'form-control form-control-sm',
                                        'prompt' => 'Выберите действие',
                                        'disabled' => !Yii::$app->user->can('schemeManageEdit'),
                                    ]) ?>
                                </div>
                                <ul id="action_list_block" style="list-style-type: none; padding-left: 0">
                                    <?php foreach ($model->actions as $action) :
                                        echo $this->render("new-action", [
                                            "model" => $action,
                                            "groupsActionId" => $model->id,
                                        ]);
                                    endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <?php if (Yii::$app->user->can('schemeManageEdit')) : ?>
                        <?= Html::submitButton(
                            Yii::t('app/groups-action', 'EDIT'),
                            ['class' => 'btn btn-sm btn-primary']
                        ) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
