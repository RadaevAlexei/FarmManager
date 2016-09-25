<?php

use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app/front', "CalfDetailInfo")];
?>

<h1><?=Yii::t('app/front', 'Calf', ['number' => ArrayHelper::getValue($calf, "number", "")])?></h1>
<table class="table table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-9">
    </colgroup>

    <tbody>
        <tr>
            <td><?=Yii::t('app/front', 'Nickname')?></td>
            <td><?=ArrayHelper::getValue($calf, "nickname", "")?></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'Group')?></td>
            <td><?=ArrayHelper::getValue($calf, "group", "")?></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'Birthday')?></td>
            <td><?=(!empty($calf["birthday"]) ? date("d/m/Y", strtotime($calf["birthday"])) : "")?></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'Gender')?></td>
            <td><?=ArrayHelper::getValue($calf, "gender", "")?></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'BirthWeight')?></td>
            <td><?=ArrayHelper::getValue($calf, "birthWeight", "")?></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'PreviousWeighing')?></td>
            <td><?=ArrayHelper::getValue($calf, "previousWeighing", "")?></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'LastWeighing')?></td>
            <td><?=ArrayHelper::getValue($calf, "lastWeighing", "")?></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'Color')?></td>
            <td><?=ArrayHelper::getValue($calf->suit, "name", "")?></td>
        </tr>

        <?php if (!empty($calf["motherId"])) : ?>
            <tr>
                <td><?=Yii::t('app/front', 'Mother')?></td>
                <td>
                    <a href="<?=\yii\helpers\Url::toRoute(['detail', 'id' => $calf["motherId"]])?>">Посмотреть</a>
                </td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($calf["fatherId"])) : ?>
            <tr>
                <td><?=Yii::t('app/front', 'Father')?></td>
                <td>
                    <a href="<?=\yii\helpers\Url::toRoute(['detail', 'id' => $calf["fatherId"]])?>">Посмотреть</a>
                </td>
            </tr>
        <?php endif; ?>

    </tbody>
</table>