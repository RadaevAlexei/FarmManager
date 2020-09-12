<?php

use yii\helpers\ArrayHelper;
use \hail812\adminlte3\widgets\Menu;
use \yii\helpers\Url;

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= Url::home() ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">LIFE CATTLE</span>
    </a>

    <div class="sidebar">

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

        <nav class="mt-2">

            <?php
            echo Menu::widget([
                'items' => [
                    ['label' => 'ПОЛЬЗОВАТЕЛИ', 'header' => true],
                    [
                        'label' => 'Сотрудники',
                        'icon' => 'users',
                        'url' => ['/user/index'],
                    ],
                    [
                        'label' => 'Должности',
                        'icon' => 'th',
                        'url' => ['/position/index'],
                    ],
                    ['label' => 'СТАДО', 'header' => true],
                    [
                        'label' => 'Общий список скота',
                        'icon' => 'list',
                        'url' => ['/animal/index'],
                    ],
                    [
                        'label' => 'Масти',
                        'icon' => 'copy',
                        'url' => ['/color/index'],
                    ],
                    [
                        'label' => 'Коровники',
                        'icon' => 'home',
                        'url' => ['/cowshed/index'],
                    ],
                    [
                        'label' => 'Фермы',
                        'icon' => 'home',
                        'url' => ['/farm/index'],
                    ],
                    [
                        'label' => 'Группы животных',
                        'icon' => 'file',
                        'url' => ['/animal-group/index'],
                    ],
                    ['label' => 'АМБУЛАТОРНЫЙ ЖУРНАЛ', 'header' => true],
                    [
                        'label' => 'Управление схемами',
                        'icon' => 'file',
                        'items' => [
                            [
                                'label' => 'Группы действий',
                                'iconStyle' => 'far',
                                'url' => ['/scheme/groups-action/index'],
                            ],
                            [
                                'label' => 'Действия',
                                'iconStyle' => 'far',
                                'url' => ['/scheme/action/index'],
                            ],
                            [
                                'label' => 'Списки',
                                'iconStyle' => 'far',
                                'url' => ['/scheme/action-list/index'],
                            ],
                            [
                                'label' => 'Схемы лечения',
                                'iconStyle' => 'far',
                                'url' => ['/scheme/scheme/index'],
                            ],
                        ]
                    ],
                    [
                        'label' => 'Диагнозы',
                        'icon' => 'file',
                        'url' => ['/scheme/diagnosis/index'],
                    ],
                    [
                        'label' => 'Управление аптекой',
                        'icon' => 'file',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Упр-е препаратами',
                                'iconStyle' => 'far',
                                'url' => ['/pharmacy/preparation/index'],
                            ],
                            [
                                'label' => 'Управление складами',
                                'iconStyle' => 'far',
                                'url' => ['/pharmacy/stock/index'],
                            ],
                            [
                                'label' => 'Хранилище препаратов',
                                'iconStyle' => 'far',
                                'url' => ['/pharmacy/storage/index'],
                            ],
                            [
                                'label' => 'Расход / Приход',
                                'iconStyle' => 'far',
                                'url' => ['/pharmacy/cash-book/index'],
                            ],
                        ],
                    ],
                    [
                        'label' => 'Список дел',
                        'icon' => 'file',
                        'items' => [
                            [
                                'label' => 'Просроченные',
                                'iconStyle' => 'far',
                                'url' => ['/scheme/action-day/overdue'],
                            ],
                            [
                                'label' => 'Текущие и будущие',
                                'iconStyle' => 'far',
                                'url' => ['/scheme/action-day/index'],
                            ],
                        ]
                    ],
                    [
                        'label' => 'Список больных ж-х',
                        'icon' => 'minus',
                        'url' => ['/animal/sick-index'],
                    ],
                    [
                        'label' => 'Список ж-х в ожидании',
                        'icon' => 'eye',
                        'url' => ['/animal/awaiting-index'],
                    ],
                    ['label' => 'ВОСПРОИЗВОДСТВО', 'header' => true],
                    [
                        'label' => 'Поставщики семени',
                        'icon' => 'file',
                        'url' => ['/reproduction/seed-supplier/index'],
                    ],
                    [
                        'label' => 'Список быков',
                        'icon' => 'file',
                        'url' => ['/reproduction/seed-bull/index'],
                    ],
                    [
                        'label' => 'Упр-е Сосудами Дьюара',
                        'icon' => 'file',
                        'url' => ['/reproduction/container-duara/index'],
                    ],
                    [
                        'label' => 'Расход / Приход',
                        'icon' => 'table',
                        'url' => ['/reproduction/seed-cash-book/index'],
                    ],
                    ['label' => 'РЕКТАЛЬНОЕ ИССЛЕДОВАНИЕ', 'header' => true],
                    [
                        'label' => 'Список животных под РИ',
                        'icon' => 'list',
                        'url' => ['/rectal/rectal-list/index'],
                    ],
                    [
                        'label' => 'Настройки РИ',
                        'icon' => 'list',
                        'url' => ['/rectal/rectal-settings/index'],
                    ],
                    ['label' => 'ЗООТЕХНИЧЕСКАЯ СЛУЖБА', 'header' => true],
                    [
                        'label' => 'Формирование актов',
                        'icon' => 'file',
                        'url' => ['/livestock/livestock-report/index'],
                    ],
                ],
            ]);
            ?>
        </nav>
    </div>
</aside>
