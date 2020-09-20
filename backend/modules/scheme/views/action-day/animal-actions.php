<?php

use \backend\modules\scheme\models\ActionHistory;
use \yii\helpers\ArrayHelper;

/**
 * @var ActionHistory[] $actions_data
 * @var bool $overdue
 * @var bool $disable
 */

//$groupsAction = $day->groupsAction;
?>

<div class="container-fluid">
    <div class="row">

        <?php foreach ($actions_data as $groupId => $data) : ?>

        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?= ArrayHelper::getValue($data, "group_action_name") ?></h3>
                </div>

                <div class="card-body">
                    <?php foreach ($data["actions"] as $actionHistory) : ?>
                        <?= $this->render('action', compact('actionHistory', 'overdue', 'disable')); ?>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <?php endforeach; ?>
    </div>

</div>