<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;

?>

<h1>Список телят</h1>
<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
    </colgroup>

    <thead>
        <tr>
            <th>№</th>
            <th>Инд. номер</th>
            <th>Кличка</th>
            <th>Пол</th>
            <th>Дата Рождения</th>
            <th>Вес при Рождении,кг</th>
            <th>Группа</th>
            <th>Масть</th>
            <th>Предыдущее взвешивание</th>
            <th>Текущее взвешивание</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($calfs)) : ?>
            <?php foreach ($calfs as $index => $calf): ?>
                <tr data-id="<?=$calf["id"]?>" style="cursor: pointer">
                    <td><?=($index + 1)?></td>
                    <td>
                        <a href="<?=Url::toRoute(['/calf/detail/' . ArrayHelper::getValue($calf, "number") . "/"])?>">
                            <?=ArrayHelper::getValue($calf, "number")?>
                        </a>
                    </td>
                    <td><?=ArrayHelper::getValue($calf, "nickname")?></td>
                    <td><?=ArrayHelper::getValue($calf, "gender_short")?></td>
                    <td><?=ArrayHelper::getValue($calf, "birthday")?></td>
                    <td><?=ArrayHelper::getValue($calf, "birthWeight")?></td>
                    <td><?=ArrayHelper::getValue($calf, "calfGroup.name")?></td>
                    <td><?=ArrayHelper::getValue($calf, "suit.name")?></td>
                    <td><?=ArrayHelper::getValue($calf, "previousWeighing")?></td>
                    <td><?=ArrayHelper::getValue($calf, "currentWeighing")?></td>
                    <td align="center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?=Url::toRoute(['/calf/edit/' . $calf['id'] . '/'])?>">Редактировать</a></li>
                                <li><a href="<?=Url::toRoute(['/calf/delete/' . $calf['id'] . '/'])?>">Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td align="center" colspan="11"><?=\Yii::t('app/back', 'CALFS_NOT_FOUND')?></td>
            </tr>
        <?php endif; ?>

    </tbody>
</table>

<div class="pull-right">
    <button type="button" data-url="<?=Url::toRoute(['/calf/new/'])?>" class="btn btn-info addItem">Добавить</button>
</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>