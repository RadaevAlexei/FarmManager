<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/**
 * @var array $details
 * @var bool $overdue
 * @var bool $disable
 */

$items = [];
if (!empty($details)) :
    $index = 0;
    foreach ($details as $scheme_id => $animalActionData) :
        $dayName = "{$animalActionData["animal_nickname"]} - #{$animalActionData["animal_label"]}";

        $items[] = [
            'label' => $dayName,
            'content' => $this->render('animal-actions', [
                'actions_data' => $animalActionData["data"],
                'overdue' => $overdue,
                'disable' => $disable,
            ]),
        ];

        $index++;
    endforeach;
endif;

$dataProvider = new ArrayDataProvider([
    'allModels' => $items
]);

if (!empty($items)) : ?>
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
                                    'label' => 'Животное',
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
<?php
endif;