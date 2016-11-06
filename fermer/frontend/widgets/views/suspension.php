<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

?>

<h2>Акт перевески</h2>
<table class="table f-table-list table-striped table-hover table-condensed">

    <thead>
        <tr class="info">
            <th>№</th>
            <th>Инд.<br>номер</th>
            <th>Дата<br>Рождения</th>
            <th>Вес при<br>Рождении</th>
            <th>Пол</th>
            <th>Предыдущее взвешивание/<br>Дата</th>
            <th>Предыдущее взвешивание/<br>Кг</th>
            <th>Текущее взвешивание/<br>Дата</th>
            <th>Текущее взвешивание/<br>Кг</th>
            <th>Возраст/<br>Дн</th>
            <th>Возраст/<br>Мес</th>
            <th>Возраст/<br>Год</th>
            <th>Корм дни</th>
            <th>Привес/кг</th>
            <th>Среднесут.<br>привес/кг</th>
            <th>Приход/<br>Продажа</th>
            <th>Примечание</th>
        </tr>
    </thead>

    <tbody>
        <?=$mainTable?>
    </tbody>
</table>

<h2>Общие показатели</h2>
<table class="table f-table-list table-striped table-hover table-condensed">

    <thead>
        <tr class="info">
            <th>&nbsp;</th>
            <th>Телок</th>
            <th>Бычков</th>
            <th>Общее количество</th>
        </tr>
    </thead>

    <tbody>
        <?=$baseResultTable?>
    </tbody>
</table>

<h2>Переведено</h2>
<table class="table f-table-list table-striped table-hover table-condensed">
    <tbody>
        <?=$movedTable?>
    </tbody>
</table>

<h3>Руководство</h3>
<table class="table f-table-list table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-5">
        <col class="col-xs-7">
    </colgroup>

    <tbody>
        <?=$leadershipTable?>
    </tbody>
</table>

<div style="width:75%;">
    <canvas id="canvas"></canvas>
</div>

<script type="text/javascript">
    window.result = <?=json_encode($result)?>
</script>