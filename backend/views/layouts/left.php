<?php

use \yii\helpers\Url;
use common\models\User;
use common\models\UserRole;
use \yii\helpers\ArrayHelper;
use dmstr\widgets\Menu;

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
                <p><?= ArrayHelper::getValue(Yii::$app->getUser(), "identity.username") ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i><?= User::getCurRoleCode() ?></a>
            </div>
        </div>

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'items' => [

                    [
                        'label' => 'Пользователи',
                        'options' => ['class' => 'header'],
                        'visible' => User::getCurRoleCode() !== UserRole::ROLE_VIEWER,
                    ],
                    [
                        'label' => 'Сотрудники',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/user/index']),
                        'visible' => User::getCurRoleCode() !== UserRole::ROLE_VIEWER,
                    ],
                    [
                        'label' => 'Должности',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/position/index']),
                        'visible' => User::getCurRoleCode() !== UserRole::ROLE_VIEWER,
                    ],

                    ['label' => 'Стадо', 'options' => ['class' => 'header']],
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
                    /*[
                        'label' => 'Группы',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/group/index'])
                    ],*/
                    /*[
                        'label' => 'Переводы',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/transfer/index'])
                    ],
                    [
                        'label' => 'Перевески',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/suspension/index'])
                    ],*/

                    ['label' => 'Амбулаторный журнал', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Управление схемами',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Группы действий',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/scheme/groups-action/index']),
                            ],
                            [
                                'label' => 'Действия',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/scheme/action/index']),
                            ],
                            [
                                'label' => 'Списки',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/scheme/action-list/index']),
                            ],
                            [
                                'label' => 'Схемы лечения',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/scheme/scheme/index'])
                            ],
                        ],
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
                                'label' => 'Управление препаратами',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/pharmacy/preparation/index']),
                            ],
                            [
                                'label' => 'Управление складами',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/pharmacy/stock/index']),
                            ],
                            [
                                'label' => 'Хранилище препаратов',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/pharmacy/storage/index']),
                            ],
                            [
                                'label' => 'Расход / Приход',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/pharmacy/cash-book/index']),
                            ],
                            /*[
                                'label' => 'Движения препаратов',
                                'icon'  => 'users',
                                'url'   => Url::toRoute(['/pharmacy/stock-migration/index']),
                            ],
                            ,*/
                        ],
                    ],
                    [
                        'label' => 'Список дел',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Просроченные',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/scheme/action-day/overdue'])
                            ],
                            [
                                'label' => 'Текущие и будущие',
                                'icon' => 'users',
                                'url' => Url::toRoute(['/scheme/action-day/index'])
                            ],
                        ]
                    ],
                    [
                        'label' => 'Список больных животных',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/animal/sick-index'])
                    ],
                    [
                        'label' => 'Список животных в ожидании',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/animal/awaiting-index'])
                    ],
                    /*[
                        'label' => 'Календарь',
                        'icon'  => 'users',
                        'url'   => Url::toRoute(['/scheme/calendar/index'])
                    ],*/
                    ['label' => 'Воспроизводство', 'options' => ['class' => 'header']],
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
                        'label' => 'Управление Сосудами Дьюара',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/reproduction/container-duara/index'])
                    ],
                    [
                        'label' => 'Расход / Приход',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/reproduction/seed-cash-book/index']),
                    ],
                    ['label' => 'Ректальное исследование', 'options' => ['class' => 'header']],
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
                    ['label' => 'Зоотехническая служба', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Формирование актов',
                        'icon' => 'users',
                        'url' => Url::toRoute(['/livestock/livestock-report/index']),
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
