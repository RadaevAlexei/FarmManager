<?php

use \yii\grid\GridView;
use \yii\data\ActiveDataProvider;
use \backend\modules\pharmacy\models\search\StorageSearch;
use \backend\modules\pharmacy\models\Storage;
use \yii\helpers\ArrayHelper;

$this->title = 'Хранение препаратов';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel StorageSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<?php echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel"  => $searchModel,
    'tableOptions' => [
        'class' => 'table table-striped',
    ],
    'formatter'    => [
        'class'       => 'yii\i18n\Formatter',
        'nullDisplay' => '',
    ],
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'preparation_id',
            'content'   => function (Storage $model) {
                return ArrayHelper::getValue($model, "preparation.name");
            }
        ],
        [
            'attribute' => 'stock_id',
            'content'   => function (Storage $model) {
                return ArrayHelper::getValue($model, "stock.name");
            }
        ],
        'count',
        'volume',
        'measure'
    ]
]);