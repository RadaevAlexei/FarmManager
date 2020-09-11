<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= \yii\helpers\Url::home() ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">LIFE CATTLE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?= ArrayHelper::getValue(Yii::$app->getUser(), 'identity.email') ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">

            <?php
            echo \hail812\adminlte3\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Пользователи', 'header' => true],
                    [
                        'label' => 'Сотрудники',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/user/index']),
                    ],
                    [
                        'label' => 'Должности',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/position/index']),
                    ],
                    ['label' => 'Стадо', 'header' => true],
                    [
                        'label' => 'Общий список скота',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/animal/index'])
                    ],
                    [
                        'label' => 'Масти',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/color/index'])
                    ],
                    [
                        'label' => 'Коровники',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/cowshed/index'])
                    ],
                    [
                        'label' => 'Фермы',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/farm/index'])
                    ],
                    [
                        'label' => 'Группы животных',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/animal-group/index'])
                    ],
                    ['label' => 'Амбулаторный журнал', 'header' => true],
                    [
                        'label' => 'Управление схемами',
                        'icon' => 'users',
                        'items' => [
                            [
                                'label' => 'Группы действий',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/scheme/groups-action/index']),
                            ],
                            [
                                'label' => 'Действия',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/scheme/action/index']),
                            ],
                            [
                                'label' => 'Списки',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/scheme/action-list/index']),
                            ],
                            [
                                'label' => 'Схемы лечения',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/scheme/scheme/index'])
                            ],
                        ]
                    ],
                    [
                        'label' => 'Диагнозы',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/scheme/diagnosis/index'])
                    ],
                    [
                        'label' => 'Управление аптекой',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Упр-е препаратами',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/pharmacy/preparation/index']),
                            ],
                            [
                                'label' => 'Управление складами',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/pharmacy/stock/index']),
                            ],
                            [
                                'label' => 'Хранилище препаратов',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/pharmacy/storage/index']),
                            ],
                            [
                                'label' => 'Расход / Приход',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/pharmacy/cash-book/index']),
                            ],
                        ],
                    ],
                    [
                        'label' => 'Список дел',
                        'icon' => 'users',
                        'items' => [
                            [
                                'label' => 'Просроченные',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/scheme/action-day/overdue'])
                            ],
                            [
                                'label' => 'Текущие и будущие',
                                'iconStyle' => 'far',
                                'url' => Url::toRoute(['/scheme/action-day/index'])
                            ],
                        ]
                    ],
                    [
                        'label' => 'Список больных ж-х',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/animal/sick-index'])
                    ],
                    [
                        'label' => 'Список ж-х в ожидании',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/animal/awaiting-index'])
                    ],
                    ['label' => 'Воспроизводство', 'header' => true],
                    [
                        'label' => 'Поставщики семени',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/reproduction/seed-supplier/index'])
                    ],
                    [
                        'label' => 'Список быков',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/reproduction/seed-bull/index'])
                    ],
                    [
                        'label' => 'Упр-е Сосудами Дьюара',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/reproduction/container-duara/index'])
                    ],
                    [
                        'label' => 'Расход / Приход',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/reproduction/seed-cash-book/index']),
                    ],
                    ['label' => 'Ректальное исследование', 'header' => true],
                    [
                        'label' => 'Список животных под РИ',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/rectal/rectal-list/index']),
                    ],
                    [
                        'label' => 'Настройки РИ',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/rectal/rectal-settings/index']),
                    ],
                    ['label' => 'Зоотехническая служба', 'header' => true],
                    [
                        'label' => 'Формирование актов',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/livestock/livestock-report/index']),
                    ],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
