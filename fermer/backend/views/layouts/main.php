<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\assets\MainBackendAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use \yii\helpers\Url;

AppAsset::register($this);
MainBackendAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">

        <div class="btn-group">
            <button type="button" class="btn btn-info">Администрирование</button>
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?=Url::toRoute(['/functions'])?>">Должности</a></li>
                <li><a href="<?=Url::toRoute(['/employees'])?>">Сотрудники</a></li>
                <li><a href="<?=Url::toRoute(['/groups'])?>">Группы</a></li>
                <li class="divider"></li>
                <li><a href="<?=Url::toRoute(['/calf/add'])?>">Добавить теленка</a></li>
                <li><a href="<?=Url::toRoute(['/group/add'])?>">Добавить группу</a></li>
                <li><a href="<?=Url::toRoute(['/employee/new/'])?>">Добавить сотрудника</a></li>
                <li><a href="<?=Url::toRoute(['/function/new/'])?>">Добавить должность</a></li>
                <li><a href="<?=Url::toRoute(['/suspension/add'])?>">Добавить взвешивание</a></li>
            </ul>
        </div>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Ферма <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
