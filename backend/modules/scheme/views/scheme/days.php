<?php

use \backend\modules\scheme\models\Scheme;
use \backend\modules\scheme\models\SchemeDay;
use \backend\modules\scheme\models\GroupsAction;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use \yii\helpers\ArrayHelper;

/**
 * @var Scheme $model
 * @var SchemeDay[] $schemeDays
 * @var GroupsAction[] $groupsActionList
 */
$schemeDays = $model->schemeDays;

$items = [];
if (!empty($schemeDays)) :
    foreach ($schemeDays as $index => $day) :
        $dayName = "День {$day->number}-й";

        $items[] = [
            'label' => $dayName,
            'content' => $this->render('groups-action', [
                'scheme' => $model,
                'day' => $day,
                'groupsActionList' => $groupsActionList,
            ]),
        ];
    endforeach;
endif;

$dataProvider = new ArrayDataProvider([
    'allModels' => $items
]); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">

                    <?= GridView::widget([
                        "dataProvider" => $dataProvider,
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'summary' => false,
                        'columns' => [
                            [
                                'label' => 'День',
                                'value' => function ($item) {
                                    return ArrayHelper::getValue($item, "label");
                                }
                            ],
                            [
                                'label' => 'Действия',
                                'format' => 'raw',
                                'value' => function ($item) {
                                    return ArrayHelper::getValue($item, "content");
                                }
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>