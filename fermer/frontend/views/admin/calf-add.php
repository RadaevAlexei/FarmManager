<?php

use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app/front', "CalfDetailInfo")];
?>

<h1>Добавление теленка</h1>
<table class="table table-striped table-hover table-condensed">

    <colgroup>
        <col class="col-xs-3">
        <col class="col-xs-9">
    </colgroup>

    <tbody>
        <tr>
            <td><?=Yii::t('app/front', 'Nickname')?></td>
            <td></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'Group')?></td>
            <td></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'Birthday')?></td>
            <td></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'Gender')?></td>
            <td></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'BirthWeight')?></td>
            <td></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'PreviousWeighing')?></td>
            <td></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'LastWeighing')?></td>
            <td></td>
        </tr>
        <tr>
            <td><?=Yii::t('app/front', 'Color')?></td>
            <td></td>
        </tr>
    </tbody>
</table>