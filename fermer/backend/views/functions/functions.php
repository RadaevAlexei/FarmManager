<?php

use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;

?>


<h2><?=\Yii::t('app/back', 'FUNCTIONS')?></h2>

<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-1">
        <col class="col-xs-7">
        <col class="col-xs-4">
    </colgroup>

    <thead>
        <tr>
            <th>№</th>
            <th>Название должности</th>
            <th>Сокращенное название</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($functions)) : ?>
            <?php foreach ($functions as $index => $function): ?>
                <tr data-id="<?=$function["id"]?>" style="cursor: pointer">
                    <th><?=($index + 1)?></th>
                    <th><?=ArrayHelper::getValue($function, "name", "")?></th>
                    <th><?=ArrayHelper::getValue($function, "short_name", "")?></th>
                    <th>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?=Url::toRoute(['/function/edit/' . $function['id'] . '/'])?>">Редактировать</a></li>
                                <li><a href="<?=Url::toRoute(['/function/delete/' . $function['id'] . '/'])?>">Удалить</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td align="center" colspan="3"><?=\Yii::t('app/back', 'FUNCTIONS_NOT_FOUND')?><td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

<div class="pull-right">
    <button type="button" id="addFunction" data-url="<?=Url::toRoute(['/function/new/'])?>" class="btn btn-info">Добавить</button>
</div>
