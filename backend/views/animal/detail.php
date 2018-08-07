<?php

use yii\helpers\ArrayHelper;
use \backend\assets\ChartAsset;
use \yii\helpers\Url;
use \yii\helpers\Html;
use common\models\Animal;

//ChartAsset::register($this);

/** @var Animal $model */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/animal', "ANIMAL_LIST"),
    'url'   => Url::toRoute(['/animal/index'])
];

$this->params['breadcrumbs'][] = ['label' => Yii::t('app/animal', "ANIMAL_DETAIL")];
?>

<h1>
    <?= Yii::t(
        'app/animal',
        'ANIMAL_NAME',
        [
            'sex'   => ArrayHelper::getValue($model, "sex"),
            'label' => ArrayHelper::getValue($model, "label")
        ]
    ) ?>
</h1>

<table class="table table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-9">
    </colgroup>

    <tbody>
    <tr>
        <td><?= $model->getAttributeLabel("nickname") ?></td>
        <td><?= ArrayHelper::getValue($model, "nickname") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("birthday") ?></td>
        <td><?= ArrayHelper::getValue($model, "birthday") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("physical_state") ?></td>
        <td><?= ArrayHelper::getValue($model, "physical_state") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("cowshed_id") ?></td>
        <td><?= ArrayHelper::getValue($model, "cowshed_id") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("farm_id") ?></td>
        <td><?= ArrayHelper::getValue($model, "farm_id") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("box") ?></td>
        <td><?= ArrayHelper::getValue($model, "box") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("group_id") ?></td>
        <td><?= ArrayHelper::getValue($model, "animalGroup.name") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("birth_weight") ?></td>
        <td><?= ArrayHelper::getValue($model, "birth_weight") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("previous_weighing") ?></td>
        <td><?= ArrayHelper::getValue($model, "previous_weighing") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("current_weighing") ?></td>
        <td><?= ArrayHelper::getValue($model, "current_weighing") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("color") ?></td>
        <td><?= ArrayHelper::getValue($model, "color.name") ?></td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("mother_id") ?></td>
        <td>
            <?php if (ArrayHelper::getValue($model, "mother_id")) : ?>
                <a href="<?= Url::toRoute(['/animal/detail/' . ArrayHelper::getValue($model, "mother_id") . "/"]) ?>">Посмотреть</a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td><?= $model->getAttributeLabel("father_id") ?></td>
        <td>
            <?php if (ArrayHelper::getValue($model, "father_id")) : ?>
                <a href="<?= Url::toRoute(['/animal/detail/' . ArrayHelper::getValue($model, "father_id") . "/"]) ?>">Посмотреть</a>
            <?php endif; ?>
        </td>
    </tr>
    </tbody>
</table>

<!--<h1>Перевески</h1>
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
        <?php /*if (!empty($suspensions)) : */ ?>
            <?php /*foreach ($suspensions as $index => $suspension) : */ ?>
                <tr>
                    <td><? /*=($index + 1)*/ ?></td>
                    <td><? /*=ArrayHelper::getValue($suspension, "date")*/ ?></td>
                    <td><? /*=ArrayHelper::getValue($suspension, "weight")*/ ?></td>
                </tr>
            <?php /*endforeach; */ ?>
        <?php /*else : */ ?>
            <tr>
                <td align="center" colspan="3">Теленок ни разу не взвешивался</td>
            </tr>
        <?php /*endif; */ ?>
    </tbody>
</table>
-->
<!--<div style="width:75%;">
    <canvas id="canvas"></canvas>
</div>-->

<!--<script type="text/javascript">
    window.dates = <? /*=json_encode($dates)*/ ?>;
    window.weights = <? /*=json_encode($weights)*/ ?>;
    window.norm = <? /*=json_encode($norm)*/ ?>;
    console.log(window.norm);
</script>-->