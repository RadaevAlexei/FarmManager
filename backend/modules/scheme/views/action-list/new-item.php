<?php

use \yii\helpers\Url;
use \yii\helpers\Html;
use \backend\modules\scheme\models\ActionListItem;
use \yii\helpers\ArrayHelper;

/**
 * @var ActionListItem $model
 * @var integer $actionListId
 */
?>

<li data-remove-url="<?= Url::to([
        'remove-item',
        'action_list_id' => $actionListId,
        'item_id' => $model->id
]) ?>" class="action-list-item">
    <div class="input-group input-group-sm">
        <?= Html::textInput(
            'items',
            ArrayHelper::getValue($model, "name"),
            ["class" => "form-control"]
        ) ?>
        <span class="input-group-btn">
            <button remove-action-item type="button" class="btn btn-danger btn-flat">Удалить</button>
        </span>
    </div>
</li>
