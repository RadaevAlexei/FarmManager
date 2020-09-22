<?php

use \yii\grid\GridView;
use \yii\data\ActiveDataProvider;
use \backend\modules\pharmacy\models\search\StorageSearch;
use \backend\modules\pharmacy\models\Storage;
use \yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use \yii\helpers\Url;

$this->title = 'Хранение препаратов';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel StorageSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="form-group">
                        <?php if (Yii::$app->user->can('managePharmacyEdit')) : ?>
                            <?= Html::a(
                                'Переместить препарат на другой склад',
                                Url::toRoute(['stock-migration/new']),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        <?php endif; ?>
                        <?= Html::a(
                            'История перемещений',
                            Url::toRoute(['stock-migration/index']),
                            ['class' => 'btn btn-success']
                        ) ?>
                    </div>

                    <?php echo GridView::widget([
                        "dataProvider" => $dataProvider,
                        "filterModel" => $searchModel,
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'preparation_id',
                                'content' => function (Storage $model) {
                                    return Html::a(
                                        ArrayHelper::getValue($model, "preparation.name"),
                                        Url::toRoute(['preparation/edit', 'id' => $model->preparation_id]),
                                        ["target" => "_blank"]
                                    );
                                }
                            ],
                            [
                                'attribute' => 'stock_id',
                                'content' => function (Storage $model) {
                                    return Html::a(
                                        ArrayHelper::getValue($model, "stock.name"),
                                        Url::toRoute(['stock/edit', 'id' => $model->stock_id]),
                                        ["target" => "_blank"]
                                    );
                                }
                            ],
                            'count',
                            'volume',
                            [
                                'label' => 'Общий объём',
                                'content' => function (Storage $model) {
                                    return $model->count * $model->volume;
                                }
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

