<?php

use \yii\helpers\ArrayHelper;

/**
 * @var $history
 */

?>

<div class="row day_block" style="margin-left: auto">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">ИСТОРИЯ ПЫТОК ЭТОГО ЖИВОТНОГО</h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Дата</th>
                        <th>Кто сделал?</th>
                        <th>Что сделал?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($history)) :
                        foreach ($history as $index => $action) : ?>
                            <tr>
                                <td><?= ($index + 1) ?></td>
                                <td><?= (new DateTime(ArrayHelper::getValue($action, "date")))->format('d.m.Y H:i:s') ?></td>
                                <td><?= ArrayHelper::getValue($action, "user.lastName") ?></td>
                                <td><?= ArrayHelper::getValue($action, "action_text") ?></td>
                            </tr>
                        <?php endforeach;
                    endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
