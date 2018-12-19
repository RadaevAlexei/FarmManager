<?php

use kartik\tabs\TabsX;
use \backend\modules\scheme\models\Scheme;
use \backend\modules\scheme\models\SchemeDay;
use \backend\modules\scheme\models\GroupsAction;

/**
 * @var Scheme $model
 */

/**
 * @var SchemeDay[] $schemeDays
 * @var GroupsAction[] $groupsActionList
 */
$schemeDays = $model->schemeDays;

$items = [];
if (!empty($schemeDays)) :
    foreach ($schemeDays as $index => $day) :
        $dayName = "День {$day->number}-й";

        $items[] = [
            'label'   => '<i class="fa fa-user"></i>&nbsp;' . $dayName,
            'content' => $this->render('groups-action', [
                'day'              => $day,
                'groupsActionList' => $groupsActionList,
            ]),
            'active'  => ($index == 0) ? true : false
        ];
    endforeach; ?>
<?php endif; ?>

<?php echo TabsX::widget([
    'items'            => $items,
    'position'         => TabsX::POS_LEFT,
    'encodeLabels'     => false,
    'bordered'         => true,
    'sideways'         => false,
    'containerOptions' => ['style' => 'margin-top: 15px']
]); ?>