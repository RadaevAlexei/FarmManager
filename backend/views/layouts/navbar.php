<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \common\models\User;

?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['site/logout'],
                ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="user-image img-circle elevation-2"
                     alt="User Image">
                <span class="d-none d-md-inline">
                    <?= ArrayHelper::getValue(Yii::$app->getUser(), 'identity.email') ?>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <li class="user-header bg-primary">
                    <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">

                    <p>
                        <?= ArrayHelper::getValue(Yii::$app->getUser(), 'identity.email') ?>
                        <small></small>
                    </p>
                </li>

                <li class="user-footer">
                    <?= Html::a(
                        'Профиль',
                        ['profile/index'],
                        ['class' => 'btn btn-default btn-flat']
                    ) ?>
                    <?= Html::a(
                        'Выйти',
                        ['/site/logout/'],
                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat float-right']
                    ) ?>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
                        class="fas fa-th-large"></i></a>
        </li>
    </ul>
</nav>
