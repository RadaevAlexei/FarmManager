<?php

use \yii\helpers\Url;

/** @var $directoryAsset string|false */

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Radaev</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items'   => [

                    ['label' => 'Пользователи', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Сотрудники',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/user/index'])
                    ],
                    [
                        'label' => 'Должности',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/position/index'])
                    ],

                    ['label' => 'Стадо', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Общий список скота',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/animal/index'])
                    ],
                    [
                        'label' => 'Масти',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/color/index'])
                    ],
                    [
                        'label' => 'Коровники',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/cowshed/index'])
                    ],
                    [
                        'label' => 'Фермы',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/farm/index'])
                    ],
                    [
                        'label' => 'Группы',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/group/index'])
                    ],
                    [
                        'label' => 'Переводы',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/transfer/index'])
                    ],
                    [
                        'label' => 'Перевески',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/suspension/index'])
                    ],


                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Same tools',
                        'icon'  => 'share',
                        'url'   => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon'  => 'circle-o',
                                'url'   => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon'  => 'circle-o',
                                        'url'   => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
