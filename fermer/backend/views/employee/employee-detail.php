<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

?>


<h2><?=implode(" ", [$employee["firstName"], $employee["lastName"], $employee["middleName"]])?></h2>

<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-2">
        <col class="col-xs-5">
        <col class="col-xs-5">
    </colgroup>

    <tbody>
        <?php if (!empty($employee)) : ?>
            <tr>
                <?php $gender = $employee["gender"] == "Мужской" ? "male.png" : "female.jpg"; ?>
                <td rowspan="6"><img src="/images/<?=$gender?>" alt="..." class="img-rounded"></td>
                <td>Пол</td>
                <td><?=ArrayHelper::getValue($employee, "gender", "")?></td>
            </tr>
            <tr>
                <td>Дата Рождения</td>
                <td><?=ArrayHelper::getValue($employee, "birthday", "")?></td>
            </tr>
            <tr>
                <td>Должность</td>
                <td><?=ArrayHelper::getValue($employee->function, "name", "")?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>