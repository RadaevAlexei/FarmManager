<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;

?>


    <h2>Должности</h2>

    <table class="table f-table-list table-striped table-hover table-condensed">

        <colgroup>
            <col class="col-xs-1">
            <col class="col-xs-10">
            <col class="col-xs-1">
        </colgroup>

        <thead>
            <tr>
                <th>№</th>
                <th>Название должности</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($functions)) : ?>
                <?php foreach ($functions as $index => $function): ?>
                    <tr data-id="<?=$function["id"]?>" style="cursor: pointer">
                        <th><?=($index + 1)?></th>
                        <th><?=ArrayHelper::getValue($function, "name", "")?></th>
                        <th>
                            <div class="btn-group">
                                <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?=Url::toRoute(['/function/edit', 'id' => $function['id']])?>">Редактировать</a></li>
                                    <li><a href="<?=Url::toRoute(['/function/delete', 'id' => $function['id']])?>">Удалить</a></li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td align="center" colspan="2">Должностей нет<td>
                </tr>
            <?php endif; ?>

        <tr>
            <td colspan="3">

            </td>
        </tr>

        </tbody>
    </table>
    <div class="pull-right">
        <button type="button" id="addFunction" data-url="<?=Url::toRoute(['/function/add'])?>" class="btn btn-info">Добавить</button>
    </div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>