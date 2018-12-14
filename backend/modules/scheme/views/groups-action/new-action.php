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
    'action_id'        => $model->id
]) ?>" class="groups-action-item">
    <div class="input-group input-group-sm">
        <?= Html::textInput(
            'action-name',
            ArrayHelper::getValue($model, "name"),
            ["class" => "form-control"]
        ) ?>
        <span class="input-group-btn">
            <button remove-action type="button" class="btn btn-danger btn-flat">Удалить</button>
        </span>
    </div>
</li>
