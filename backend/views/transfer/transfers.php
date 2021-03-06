<?php

use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;

?>


<h2><?=\Yii::t('app/back', 'TRANSFERS')?></h2>

<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-2">
        <col class="col-xs-2">
        <col class="col-xs-2">
        <col class="col-xs-2">
        <col class="col-xs-2">
        <col class="col-xs-2">
    </colgroup>

    <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>Откуда</th>
            <th>Куда</th>
            <th>Телёнок</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($transfers)) : ?>
            <?php foreach ($transfers as $index => $transfer): ?>
                <tr data-id="<?=$transfer["id"]?>" style="cursor: pointer">
                    <td><?=($index + 1)?></td>
                    <td><?=ArrayHelper::getValue($transfer, "date")?></td>
                    <td><?=ArrayHelper::getValue($transfer, "groupFrom.name")?></td>
                    <td><?=ArrayHelper::getValue($transfer, "groupTo.name")?></td>
                    <td><?=ArrayHelper::getValue($transfer, "name")?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?=Url::toRoute(['/transfer/edit/' . $transfer['id'] . '/'])?>">Редактировать</a></li>
                                <li><a href="<?=Url::toRoute(['/transfer/delete/' . $transfer['id'] . '/'])?>">Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td align="center" colspan="6"><?=\Yii::t('app/back', 'TRANSFERS_NOT_FOUND')?><td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

<div class="pull-right">
    <button type="button" id="exportTransfer" data-url="<?=Url::toRoute(['/transfer/export/'])?>" class="btn btn-info">Export</button>
    <button type="button" data-url="<?=Url::toRoute(['/transfer/new/'])?>" class="btn btn-info addItem">Добавить</button>
</div>
