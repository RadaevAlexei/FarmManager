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
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
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

                    ['label' => 'Амбулаторный журнал', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Управление схемами',
                        'icon'  => 'users',
                        'url'   => '#',
                        'items' => [
                            [
                                'label' => 'Группы действий',
                                'icon'  => 'users',
                                'url'   => Url::toRoute(['/scheme/groups-action/index']),
                            ],
                            [
                                'label' => 'Действия',
                                'icon'  => 'users',
                                'url'   => Url::toRoute(['/scheme/action/index']),
                            ],
                            [
                                'label' => 'Списки',
                                'icon'  => 'users',
                                'url'   => Url::toRoute(['/scheme/action-list/index']),
                            ],
                            [
                                'label' => 'Схемы лечения',
                                'icon'  => 'users',
                                'url'   => Url::toRoute(['/scheme/scheme/index'])
                            ],
                        ],
                    ],
                    [
                        'label' => 'Диагнозы',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/scheme/diagnosis/index'])
                    ],
                    [
                        'label' => 'Управление аптекой',
                        'icon'  => 'users',
                        'url'   => '#',
                        'items' => [
                            [
                                'label' => 'Препараты',
                                'icon'  => 'users',
                                'url'   => Url::toRoute(['/pharmacy/preparation/index']),
                            ],
                            [
                                'label' => 'Склады',
                                'icon'  => 'users',
                                'url'   => Url::toRoute(['/pharmacy/stock/index']),
                            ],
                            /*[
                                'label' => 'Движения препаратов',
                                'icon'  => 'users',
                                'url'   => Url::toRoute(['/pharmacy/stock-migration/index']),
                            ],
                            [
                                'label' => 'Расход / Приход',
                                'icon'  => 'users',
                                'url'   => '#',
                            ],*/
                        ],
                    ],
                    [
                        'label' => 'Список дел',
                        'icon'  => 'users',
                        'url'   => '#',
                        'items' => [
                            [
                                'label' => 'Просроченные',
                                'icon'  => 'users',
                                'url'   =>  Url::toRoute(['/scheme/action-day/overdue'])
                            ],
                            [
                                'label' => 'Текущие и будущие',
                                'icon'  => 'users',
                                'url'   =>  Url::toRoute(['/scheme/action-day/index'])
                            ],
                        ]
                    ],
                    [
                        'label' => 'Список больных животных',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/animal/sick-index'])
                    ],
                    [
                        'label' => 'Календарь',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/scheme/calendar/index'])
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
