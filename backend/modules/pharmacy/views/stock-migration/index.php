<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\pharmacy\models\search\StockMigrationSearch;
use \backend\modules\pharmacy\models\StockMigration;
use yii\helpers\ArrayHelper;

$this->title = 'Движения препаратов';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel StockMigrationSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<div class="box box-info">
    <div class="box-body">

        <?php echo GridView::widget([
            "dataProvider" => $dataProvider,
            "filterModel"  => $searchModel,
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'date',
                    'content' => function (StockMigration $model) {
                        return (new DateTime($model->date))->format('d.m.Y');
                    }
                ],
                [
                    'attribute' => 'user_id',
                    'content' => function (StockMigration $model) {
                        return ArrayHelper::getValue($model, "user.lastName");
                    }
                ],
                [
                    'attribute' => 'preparation_id',
                    'content' => function (StockMigration $model) {
                        return ArrayHelper::getValue($model, "preparation.name");
                    }
                ],
                [
                    'attribute' => 'stock_from_id',
                    'content' => function (StockMigration $model) {
                        return ArrayHelper::getValue($model, "stockFrom.name");
                    }
                ],
                [
                    'attribute' => 'stock_to_id',
                    'content' => function (StockMigration $model) {
                        return ArrayHelper::getValue($model, "stockTo.name");
                    }
                ],
                'count',
                'volume'
            ]
        ]); ?>

    </div>
</div>