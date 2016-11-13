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
                    <th><?=($index + 1)?></th>
                    <th>
                        <a href="<?=Url::toRoute(['/calf/detail/' . ArrayHelper::getValue($calf, "number") . "/"])?>">
                            <?=ArrayHelper::getValue($calf, "number")?>
                        </a>
                    </th>
                    <th><?=ArrayHelper::getValue($calf, "nickname")?></th>
                    <th><?=ArrayHelper::getValue($calf, "gender_short")?></th>
                    <th><?=ArrayHelper::getValue($calf, "birthday")?></th>
                    <th><?=ArrayHelper::getValue($calf, "birthWeight")?></th>
                    <th><?=ArrayHelper::getValue($calf, "calfGroup.name")?></th>
                    <th><?=ArrayHelper::getValue($calf, "suit.name")?></th>
                    <th><?=ArrayHelper::getValue($calf, "previousWeighing")?></th>
                    <th><?=ArrayHelper::getValue($calf, "currentWeighing")?></th>
                    <th align="center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?=Url::toRoute(['/calf/edit/' . $calf['id'] . '/'])?>">Редактировать</a></li>
                                <li><a href="<?=Url::toRoute(['/calf/delete/' . $calf['id'] . '/'])?>">Удалить</a></li>
                            </ul>
                        </div>
                    </th>
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