<?php

use \yii\helpers\ArrayHelper;
use yii\grid\GridView;

/**
 * @var $history
 */

$historyDataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $history,
]);

?>

<?php echo GridView::widget([
    "dataProvider" => $historyDataProvider,
    'summary' => false,
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
    'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Дата',
            'value' => function ($action) {
                return (new DateTime(ArrayHelper::getValue($action, "date")))->format('d.m.Y H:i:s');
            }
        ],
        [
            'label' => 'Кто сделал?',
            'value' => function ($action) {
                return ArrayHelper::getValue($action, "user.lastName");
            }
        ],
        [
            'label' => 'Что сделал?',
            'value' => function ($action) {
                return ArrayHelper::getValue($action, "action_text");
            }
        ],
    ]
]); ?>