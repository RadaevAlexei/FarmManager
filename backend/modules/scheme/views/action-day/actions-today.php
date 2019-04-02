<?php

use kartik\tabs\TabsX;

/**
 * @var array $details
 */

$items = [];
if (!empty($details)) :
    $index = 0;
    foreach ($details as $scheme_id => $animalActionData) :
        $dayName = "Животное #{$animalActionData["animal_label"]}";

        $items[] = [
            'label'   => '<i class="fa fa-user"></i>&nbsp;' . $dayName,
            'content' => $this->render('animal-actions', [
                'actions_data' => $animalActionData["data"]
            ]),
            'active'  => ($index == 0) ? true : false
        ];

        $index++;
    endforeach;
endif;

if (!empty($items)) :
    echo TabsX::widget([
        'items'            => $items,
        'position'         => TabsX::POS_LEFT,
        'encodeLabels'     => false,
        'bordered'         => true,
        'sideways'         => false,
        'containerOptions' => ['style' => 'margin-top: 15px']
    ]);

endif;