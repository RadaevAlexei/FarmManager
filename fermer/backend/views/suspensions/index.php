<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;

$headerText = !empty($action) ? \Yii::t('app/back', 'SUSPENSION_' . strtoupper($action)) : "";
?>

<h1><?=$headerText?></h1>
<table class="table f-table-list table-striped table-hover table-condensed">

    <thead>
        <tr>
            <th>№</th>
            <th>Дата взвешивания</th>
            <th>Теленок</th>
            <th>Вес</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($suspensions)) : ?>
            <?php foreach ($suspensions as $index => $suspension): ?>
                <tr data-id="<?=$suspension["id"]?>" style="cursor: pointer">
                    <th><?=($index + 1)?></th>
                    <th><?=ArrayHelper::getValue($suspension, "date", "")?></th>
                    <th><?=ArrayHelper::getValue($suspension, "calf", "")?></th>
                    <th><?=ArrayHelper::getValue($suspension, "weight", "")?></th>
                    <th align="center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?=Url::toRoute(['/suspension/edit/' . $suspension['id'] . '/'])?>">Редактировать</a></li>
                                <li><a href="<?=Url::toRoute(['/suspension/delete/' . $suspension['id'] . '/'])?>">Удалить</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td align="center" colspan="5"><?=\Yii::t('app/back', 'SUSPENSIONS_NOT_FOUND')?></td>
            </tr>
        <?php endif; ?>

    </tbody>
</table>

<div class="pull-right">
    <button type="button" id="addSuspension" data-url="<?=Url::toRoute(['/suspension/new/'])?>" class="btn btn-info">Добавить</button>
</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>