<?php

use yii\helpers\ArrayHelper;


$this->params['breadcrumbs'][] = ['label' => Yii::t('app/front', "CalfDetailInfo")];
?>

<h1><?= Yii::t('app/front', 'Calf', ['number' => ArrayHelper::getValue($calf, "number", "")]) ?></h1>
<table class="table table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-9">
    </colgroup>

    <tbody>
    <tr>
        <td><?= Yii::t('app/front', 'Nickname') ?></td>
        <td><?= ArrayHelper::getValue($calf, "nickname", "") ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app/front', 'Group') ?></td>
        <td><?= ArrayHelper::getValue($calf, "group", "") ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app/front', 'Birthday') ?></td>
        <td><?= (!empty($calf["birthday"]) ? date("d/m/Y", strtotime($calf["birthday"])) : "") ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app/front', 'Gender') ?></td>
        <td><?= ArrayHelper::getValue($calf, "gender", "") ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app/front', 'BirthWeight') ?></td>
        <td><?= ArrayHelper::getValue($calf, "birthWeight", "") ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app/front', 'PreviousWeighing') ?></td>
        <td><?= ArrayHelper::getValue($calf, "previousWeighing", "") ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app/front', 'LastWeighing') ?></td>
        <td><?= ArrayHelper::getValue($calf, "lastWeighing", "") ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app/front', 'Color') ?></td>
        <td><?= ArrayHelper::getValue($calf->suit, "name", "") ?></td>
    </tr>

    <?php if (!empty($calf["motherId"])) : ?>
        <tr>
            <td><?= Yii::t('app/front', 'Mother') ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::toRoute(['detail', 'id' => $calf["motherId"]]) ?>">Посмотреть</a>
            </td>
        </tr>
    <?php endif; ?>

    <?php if (!empty($calf["fatherId"])) : ?>
        <tr>
            <td><?= Yii::t('app/front', 'Father') ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::toRoute(['detail', 'id' => $calf["fatherId"]]) ?>">Посмотреть</a>
            </td>
        </tr>
    <?php endif; ?>

    </tbody>
</table>

<div style="width:75%;">
    <canvas id="canvas"></canvas>
</div>

<script type="text/javascript">
    function drawChart(weights, dates) {
        var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var randomScalingFactor = function () {
            return Math.ceil(Math.random() * 10.0) * Math.pow(10, Math.ceil(Math.random() * 5));
        };
        var randomColorFactor = function () {
            return Math.round(Math.random() * 255);
        };
        var randomColor = function (opacity) {
            return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
        };
        var config = {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: "My Second dataset",
                    data: weights,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Chart.js Line Chart - Logarithmic'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'x axis'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'y axis'
                        }
                    }]
                }
            }
        };
        $.each(config.data.datasets, function (i, dataset) {
            dataset.borderColor = randomColor(0.4);
            dataset.backgroundColor = randomColor(0.5);
            dataset.pointBorderColor = randomColor(0.7);
            dataset.pointBackgroundColor = randomColor(0.5);
            dataset.pointBorderWidth = 1;
        });

        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx, config);
    }

    drawChart([1, 2],
        [1, 2]);


</script>