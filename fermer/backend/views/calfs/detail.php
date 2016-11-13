<?php

use yii\helpers\ArrayHelper;
use \backend\assets\ChartAsset;
use \yii\helpers\Url;
use \yii\helpers\Html;

ChartAsset::register($this);

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/back', "CALF_LIST"),
    'url' => Url::toRoute(['/calfs/list'])
];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/back', "CALF_DETAIL_INFO")];
?>

<h1>
    <?=Yii::t(
        'app/back',
        'CALF_NAME',
        [
            'gender' => ArrayHelper::getValue($calf, "gender"),
            'number' => ArrayHelper::getValue($calf, "number")
        ]
    )?>
</h1>

<table class="table table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-9">
    </colgroup>

    <tbody>
        <tr>
            <td><?=$calf->getAttributeLabel("nickname")?></td>
            <td><?=ArrayHelper::getValue($calf, "nickname")?></td>
        </tr>
        <tr>
            <td><?=$calf->getAttributeLabel("groupId")?></td>
            <td><?=ArrayHelper::getValue($calf, "calfGroup.name")?></td>
        </tr>
        <tr>
            <td><?=$calf->getAttributeLabel("birthday")?></td>
            <td><?=ArrayHelper::getValue($calf, "birthday")?></td>
        </tr>
        <tr>
            <td><?=$calf->getAttributeLabel("birthWeight")?></td>
            <td><?=ArrayHelper::getValue($calf, "birthWeight")?></td>
        </tr>
        <tr>
            <td><?=$calf->getAttributeLabel("previousWeighing")?></td>
            <td><?=ArrayHelper::getValue($calf, "previousWeighing")?></td>
        </tr>
        <tr>
            <td><?=$calf->getAttributeLabel("currentWeighing")?></td>
            <td><?=ArrayHelper::getValue($calf, "currentWeighing")?></td>
        </tr>
        <tr>
            <td><?=$calf->getAttributeLabel("color")?></td>
            <td><?=ArrayHelper::getValue($calf, "suit.name")?></td>
        </tr>

        <?php if (!empty($calf["motherId"])) : ?>
            <tr>
                <td><?=$calf->getAttributeLabel("motherId")?></td>
                <td>
                    <a href="<?=Url::toRoute(['/calf/detail/' . $calf["motherId"] . "/"])?>">Посмотреть</a>
                </td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($calf["fatherId"])) : ?>
            <tr>
                <td><?=$calf->getAttributeLabel("fatherId")?></td>
                <td>
                    <a href="<?=Url::toRoute(['/calf/detail/' . $calf["fatherId"] . "/"])?>">Посмотреть</a>
                </td>
            </tr>
        <?php endif; ?>

    </tbody>
</table>

<h1>Перевески</h1>
<table class="table table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
    </colgroup>

    <thead>
        <tr>
            <th>№</th>
            <th>Дата Взвешивания</th>
            <th>Вес</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($suspensions)) : ?>
            <?php foreach ($suspensions as $index => $suspension) : ?>
                <tr>
                    <td><?=($index + 1)?></td>
                    <td><?=ArrayHelper::getValue($suspension, "date")?></td>
                    <td><?=ArrayHelper::getValue($suspension, "weight")?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td align="center" colspan="3">Теленок ни разу не взвешивался</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div style="width:75%;">
    <canvas id="canvas"></canvas>
</div>

<script type="text/javascript">
    window.dates = <?=json_encode($dates)?>;
    window.weights = <?=json_encode($weights)?>;
</script>