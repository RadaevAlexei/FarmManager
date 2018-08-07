<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use \common\models\Group;

/** @var $group Group */

?>

<h2><?=ArrayHelper::getValue($group, "name")?></h2>
<hr>

<h4>Основная информация</h4>
<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-2">
    </colgroup>

    <tbody>
        <tr>
            <td>Ответственный</td>
            <td>
                <?php echo Html::a(
                    implode(" ", [$group->employee["firstName"], $group->employee["lastName"], $group->employee["middleName"]]),
                    Url::toRoute(['/employee/detail/' . $group->employee["id"] . '/'])
                ); ?>
            </td>
        </tr>
    </tbody>
</table>

<h4>Руководство</h4>
<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-2">
    </colgroup>

    <tbody>
        <tr>
            <td>Исполнительный директор</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->director["firstName"], $group->director["lastName"], $group->director["middleName"]]),
                    Url::toRoute(['/employee/detail/' . $group->director["id"] . '/'])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Главный зоотехник</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->zootechnician["firstName"], $group->zootechnician["lastName"], $group->zootechnician["middleName"]]),
                    Url::toRoute(['/employee/detail/' . $group->zootechnician["id"] .'/'])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Бухгалтер</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->accountant["firstName"], $group->accountant["lastName"], $group->accountant["middleName"]]),
                    Url::toRoute(['/employee/detail/' . $group->accountant["id"] . '/'])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Телятник(ца)</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->calfEmployee["firstName"], $group->calfEmployee["lastName"], $group->calfEmployee["middleName"]]),
                    Url::toRoute(['/employee/detail/' . $group->calfEmployee["id"] . '/'])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>Начальник службы безопасности</td>
            <td>
                <?php echo Html::a(implode(" ", [$group->directorSecurity["firstName"], $group->directorSecurity["lastName"], $group->directorSecurity["middleName"]]),
                    Url::toRoute(['/employee/detail/' . $group->directorSecurity["id"] . '/'])
                ); ?>
            </td>
        </tr>
    </tbody>
</table>