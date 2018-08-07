<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

?>


<h2><?=\Yii::t('app/back', 'GROUPS')?></h2>

<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-1">
        <col class="col-xs-5">
        <col class="col-xs-3">
        <col class="col-xs-3">
    </colgroup>

    <thead>
        <tr>
            <th>№</th>
            <th>Название группы</th>
            <th>ФИО</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($groups)) : ?>
            <?php foreach ($groups as $index => $group): ?>
                <tr style="cursor: pointer">
                    <td><?=($index + 1)?></td>
                    <td>
                        <?php echo Html::a(
                            ArrayHelper::getValue($group, "name", ""),
                            Url::toRoute(['/group/detail/' . $group["id"] . '/'])
                        ); ?>
                    </td>
                    <td>
                        <?php echo Html::a(
                            ArrayHelper::getValue($group->employee, "fioEmployee", ""),
                            Url::toRoute(['/employee/detail/' . $group->employee["id"] . '/'])
                        ); ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm  dropdown-toggle" data-toggle="dropdown">Действие <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?=Url::toRoute(['/group/edit/' . $group['id'] . '/'])?>">Редактировать</a></li>
                                <li><a href="<?=Url::toRoute(['/group/delete/' . $group['id'] . '/'])?>">Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td align="center" colspan="4"><?=\Yii::t('app/back', 'GROUPS_NOT_FOUND')?><td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>

<div class="pull-right">
    <button type="button" data-url="<?=Url::toRoute(['/group/new/'])?>" class="btn btn-info addItem">Добавить</button>
</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>