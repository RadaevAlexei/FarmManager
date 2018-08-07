<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

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
        <col class="col-xs-2">
        <col class="col-xs-2">
    </colgroup>

    <thead>
        <tr>
            <th>№</th>
            <th>Инд. номер</th>
            <th>Кличка</th>
            <th>Пол</th>
            <th>Дата Рождения</th>
            <th>Вес при Рождении</th>
            <th>Группа</th>
            <th>Цвет</th>
            <th>Последнее взвешивание/<br>Кг</th>
            <th>Последнее взвешивание/<br>Дата</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($calfs)) : ?>
            <?php foreach ($calfs as $index => $calf): ?>
                <tr data-id="<?=$calf["id"]?>" style="cursor: pointer">
                    <td><?=($index + 1)?></td>
                    <td><?=ArrayHelper::getValue($calf, "number", "")?></td>
                    <td><?=ArrayHelper::getValue($calf, "nickname", "")?></td>
                    <td><?=ArrayHelper::getValue($calf, "gender", "")?></td>
                    <td><?=(!empty($calf["birthday"]) ? date("d/m/Y", strtotime($calf["birthday"])) : "")?></td>
                    <td></td>
                    <td></td>
                    <td><?=ArrayHelper::getValue($calf->suit, "name", "")?></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td align="center" colspan="10">Телят нет</td>
            </tr>
        <?php endif; ?>

    </tbody>
</table>

<?= LinkPager::widget(['pagination' => $pagination]) ?>