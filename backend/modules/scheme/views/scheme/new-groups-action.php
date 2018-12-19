<?php

use \backend\modules\scheme\models\SchemeDay;
use \backend\modules\scheme\models\GroupsAction;
use \yii\helpers\Html;
use \yii\helpers\Url;

/**
 * @var integer $index
 * @var SchemeDay $day
 * @var GroupsAction $model
 */

?>

<tr data-remove-group-action-url="<?= Url::to([
    'remove-groups-action',
    'scheme_day_id'    => $day->id,
    'groups_action_id' => $model->id,
]) ?>">
    <td><?= ($index + 1) ?></td>
    <td><?= $model->name ?></td>
    <td>
        <?php foreach ($model->actions as $action): ?>
            <span class="label label-success"><?= $action->name ?></span><br>
        <?php endforeach; ?>
    </td>
    <td style="text-align: center">
        <button remove-groups-action type="button" class="btn btn-danger">
            <span class="glyphicon glyphicon-trash"></span>
        </button>
    </td>
</tr>