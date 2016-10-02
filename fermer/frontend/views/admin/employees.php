<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

?>


    <h2>Список сотрудников</h2>

    <table class="table f-table-list table-striped table-hover table-condensed">

        <colgroup>
            <col class="col-xs-1">
            <col class="col-xs-3">
            <col class="col-xs-1">
            <col class="col-xs-1">
            <col class="col-xs-6">
        </colgroup>

        <thead>
            <tr>
                <th>№</th>
                <th>ФИО</th>
                <th>Дата Рождения</th>
                <th>Пол</th>
                <th>Должность</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($employees)) : ?>
                <?php foreach ($employees as $index => $employee): ?>
                    <tr style="cursor: pointer">
                        <th><?=($index + 1)?></th>
                        <th>
                            <?php echo Html::a(implode(" ", [$employee["firstName"], $employee["lastName"], $employee["middleName"]]),
                                Url::toRoute(['/employee/detail', 'id' => $employee["id"]])
                            ); ?>
                        </th>
                        <th><?=ArrayHelper::getValue($employee, "birthday", "")?></th>
                        <th><?=ArrayHelper::getValue($employee, "gender", "")?></th>
                        <th><?=ArrayHelper::getValue($employee->function, "name", "")?></th>
                        <th>
                            <div class="btn-group">
                                <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?=Url::toRoute(['/employee/edit', 'id' => $employee['id']])?>">Редактировать</a></li>
                                    <li><a href="<?=Url::toRoute(['/employee/delete', 'id' => $employee['id']])?>">Удалить</a></li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td align="center" colspan="5">Сотрудников нет<td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
    <div class="pull-right">
        <button type="button" id="addEmployee" data-url="<?=Url::toRoute(['/employee/add'])?>" class="btn btn-info">Добавить</button>
    </div>


<?= LinkPager::widget(['pagination' => $pagination]) ?>