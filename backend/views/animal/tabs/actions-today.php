<?php

use \backend\modules\scheme\models\ActionHistory;
use \yii\helpers\ArrayHelper;

/**
 * @var ActionHistory[] $actionsToday
 */

?>

<div class="row" style="margin-left: auto">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Что нужно сделать сегодня ?</h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Группа действий</th>
                        <th>Список действий</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($actionsToday)) :
                            foreach ($actionsToday as $index => $action) : ?>
                                <tr>
                                    <td><?=($index + 1)?></td>
                                    <td><?=ArrayHelper::getValue($action, 'groupsAction.name')?></td>
                                    <td><?=ArrayHelper::getValue($action, 'action.name')?></td>
                                    <td></td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="box-footer clearfix">
            </div>

        </div>
    </div>
</div>