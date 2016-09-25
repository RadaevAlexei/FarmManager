<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

?>

    <h1>Список телят</h1>
    <table class="table f-table-list table-striped table-hover table-condensed">

        <colgroup>
            <col class="col-xs-2">
            <col class="col-xs-2">
            <col class="col-xs-1">
            <col class="col-xs-2">
            <col class="col-xs-2">
            <col class="col-xs-2">
            <col class="col-xs-1">
        </colgroup>

        <thead>
            <tr>
                <th>№</th>
                <th>Кличка</th>
                <th>Пол</th>
                <th>Дата Рождения</th>
                <th>Группа</th>
                <th>Цвет</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($calfs)) : ?>
                <?php foreach ($calfs as $calf): ?>
                    <tr data-id="<?=$calf["id"]?>" style="cursor: pointer">
                        <th><?=ArrayHelper::getValue($calf, "number", "")?></th>
                        <th><?=ArrayHelper::getValue($calf, "nickname", "")?></th>
                        <th><?=ArrayHelper::getValue($calf, "gender", "")?></th>
                        <th><?=(!empty($calf["birthday"]) ? date("d/m/Y", strtotime($calf["birthday"])) : "")?></th>
                        <th><?=ArrayHelper::getValue($calf, "group", "")?></th>
                        <th><?=ArrayHelper::getValue($calf->suit, "name", "")?></th>
                        <th align="center"><span class="glyphicon glyphicon-trash"></span></th>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td align="center" colspan="5">Телят нет</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>

<?= LinkPager::widget(['pagination' => $pagination]) ?>