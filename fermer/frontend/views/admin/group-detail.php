<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

?>


<h2><?=ArrayHelper::getValue($group, "name", "")?></h2>
<hr>

<h4>Основная информация</h4>
<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-9">
    </colgroup>

    <tbody>
        <tr>
            <td>Ответственный</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->employee["firstName"], $group->employee["lastName"], $group->employee["middleName"]]),
                    Url::toRoute(['/employee/detail', 'id' => $group->employee["id"]])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Дата создания</td>
            <td><?=ArrayHelper::getValue($group, "created_at", "")?></td>
        </tr>
        <tr>
            <td>Дата последнего обновления</td>
            <td><?=ArrayHelper::getValue($group, "updated_at", "")?></td>
        </tr>
    </tbody>
</table>

<h4>Руководство</h4>
<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-5">
        <col class="col-xs-7">
    </colgroup>

    <tbody>
        <tr>
            <td>Исполнительный директор</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->director["firstName"], $group->director["lastName"], $group->director["middleName"]]),
                    Url::toRoute(['/employee/detail', 'id' => $group->director["id"]])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Главный зоотехник</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->zootechnician["firstName"], $group->zootechnician["lastName"], $group->zootechnician["middleName"]]),
                    Url::toRoute(['/employee/detail', 'id' => $group->zootechnician["id"]])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Бухгалтер</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->accountant["firstName"], $group->accountant["lastName"], $group->accountant["middleName"]]),
                    Url::toRoute(['/employee/detail', 'id' => $group->accountant["id"]])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Телятник(ца)</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->calfEmployee["firstName"], $group->calfEmployee["lastName"], $group->calfEmployee["middleName"]]),
                    Url::toRoute(['/employee/detail', 'id' => $group->calfEmployee["id"]])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Начальник службы безопасности</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->directorSecurity["firstName"], $group->directorSecurity["lastName"], $group->directorSecurity["middleName"]]),
                    Url::toRoute(['/employee/detail', 'id' => $group->directorSecurity["id"]])
                ); ?>
            </td>
        </tr>
    </tbody>
</table>