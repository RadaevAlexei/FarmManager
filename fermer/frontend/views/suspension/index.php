<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use \frontend\widgets\Suspension;
?>

<?=Suspension::widget([
    'data' => $calfs,
    'view' => 'suspension'
]);?>