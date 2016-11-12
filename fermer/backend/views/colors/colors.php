<?php

use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use \yii\helpers\Url;

?>


<h2><?=\Yii::t('app/back', 'COLORS')?></h2>

<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-1">
        <col class="col-xs-7">
        <col class="col-xs-4">
    </colgroup>

    <thead>
    <tr>
        <th>№</th>
        <th>Сокращенное название</th>
        <th>Название масти</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
        <?php if (!empty($colors)) : ?>
            <?php foreach ($colors as $index => $color): ?>
                <tr data-id="<?=$color["id"]?>" style="cursor: pointer">
                    <th><?=($index + 1)?></th>
                    <th><?=ArrayHelper::getValue($color, "name")?></th>
                    <th><?=ArrayHelper::getValue($color, "short_name")?></th>
                    <th>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?=Url::toRoute(['/color/edit/' . $color['id'] . '/'])?>">Редактировать</a></li>
                                <li><a href="<?=Url::toRoute(['/color/delete/' . $color['id'] . '/'])?>">Удалить</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td align="center" colspan="3"><?=\Yii::t('app/back', 'COLORS_NOT_FOUND')?><td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

<div class="pull-right">
    <button type="button" data-url="<?=Url::toRoute(['/color/new/'])?>" class="btn btn-info addItem">Добавить</button>
</div>
