<?php

use \backend\modules\scheme\models\ActionHistory;
use \yii\helpers\ArrayHelper;

/**
 * @var ActionHistory[] $actions_data
 * @var bool $overdue
 */

//$groupsAction = $day->groupsAction;
?>

<div class="row day_block" style="margin-left: auto">

    <?php foreach ($actions_data as $groupId => $data) : ?>
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><?= ArrayHelper::getValue($data, "group_action_name") ?></h3>
                </div>

                <div class="box-body table-responsive" style="padding: 0 0 10px 0">
                    <?php foreach ($data["actions"] as $actionHistory) :
                        echo $this->render('action', compact('actionHistory', 'overdue'));
                    endforeach; ?>
                </div>

            </div>
        </div>

    <?php endforeach; ?>
</div>