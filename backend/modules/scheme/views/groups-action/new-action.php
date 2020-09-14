<?php

use \yii\helpers\Url;
use \yii\helpers\Html;
use \backend\modules\scheme\models\Action;
use \yii\helpers\ArrayHelper;

/**
 * @var Action $model
 * @var integer $groupsActionId
 */
?>

<li data-remove-action-url="<?= Url::to([
    'remove-action',
    'groups_action_id' => $groupsActionId,
    'action_id' => $model->id
]) ?>" class="groups-action-item">
    <div class="input-group input-group-sm">
        <?= Html::textInput(
            'action-name',
            ArrayHelper::getValue($model, "name"),
            ["class" => "form-control form-control-sm"]
        ) ?>
        <span class="input-group-append">
            <?= Html::button('Удалить', [
                'remove-action' => '',
                'type' => 'button',
                'class' => 'btn btn-sm btn-danger btn-flat',
                'disabled' => !Yii::$app->user->can('schemeManageEdit'),
            ]) ?>
        </span>
    </div>
</li>
