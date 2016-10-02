<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

?>


    <h2>Группы</h2>

    <table class="table f-table-list table-striped table-hover table-condensed">

        <colgroup>
            <col class="col-xs-1">
            <col class="col-xs-2">
            <col class="col-xs-3">
            <col class="col-xs-3">
            <col class="col-xs-3">
        </colgroup>

        <thead>
            <tr>
                <th>№</th>
                <th>Название группы</th>
                <th>ФИО</th>
                <th>Дата создания</th>
                <th>Последнее обновление</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($groups)) : ?>
                <?php foreach ($groups as $index => $group): ?>
                    <tr style="cursor: pointer">
                        <th><?=($index + 1)?></th>
                        <th>
                            <?php echo Html::a(ArrayHelper::getValue($group, "name", ""),
                                Url::toRoute(['/group/detail', 'id' => $group["id"]])
                            ); ?>
                        </th>
                        <th>
                            <?php echo Html::a(implode(" ", [$group->employee["firstName"], $group->employee["lastName"], $group->employee["middleName"]]),
                                Url::toRoute(['/employee/detail', 'id' => $group->employee["id"]])
                            ); ?>
                        </th>
                        <th><?=ArrayHelper::getValue($group, "created_at", "")?></th>
                        <th><?=ArrayHelper::getValue($group, "updated_at", "")?></th>
                        <th>
                            <div class="btn-group">
                                <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?=Url::toRoute(['/group/edit', 'id' => $group['id']])?>">Редактировать</a></li>
                                    <li><a href="<?=Url::toRoute(['/group/delete', 'id' => $group['id']])?>">Удалить</a></li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td align="center" colspan="5">Групп нет<td>
                </tr>
            <?php endif; ?>
        </tbody>

    </table>

    <div class="pull-right">
        <button type="button" class="btn btn-info">Добавить</button>
    </div>


<?= LinkPager::widget(['pagination' => $pagination]) ?>