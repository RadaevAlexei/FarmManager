<?php

use \yii\grid\GridView;
use \yii\data\ActiveDataProvider;
use \yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\reproduction\models\search\SeedCashBookSearch;
use backend\modules\reproduction\models\SeedCashBook;

$this->title = 'Приход/Расход';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel SeedCashBookSearch
 * @var $dataProvider ActiveDataProvider
 */

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <?php if (Yii::$app->user->can('seedCashBookEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                'Добавить приход',
                                Url::toRoute(['seed-cash-book/new', 'type' => SeedCashBook::TYPE_DEBIT]),
                                ['class' => 'btn btn-primary']
                            ) ?>
                            <?= Html::a(
                                'Добавить расход',
                                Url::toRoute(['seed-cash-book/new', 'type' => SeedCashBook::TYPE_KREDIT]),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                    <?php endif; ?>

                    <?php echo GridView::widget([
                        "dataProvider" => $dataProvider,
                        "filterModel" => $searchModel,
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'label' => 'Бык',
                                'content' => function ($model) {
                                    return ArrayHelper::getValue($model, "seed_bull_name");
                                }
                            ],
                            [
                                'label' => 'Приход/Количество',
                                'content' => function ($model) {
                                    return ArrayHelper::getValue($model, "debit.count");
                                }
                            ],
                            [
                                'label' => 'Приход/Цена без НДС',
                                'content' => function ($model) {
                                    return ArrayHelper::getValue($model, "debit.price");
                                }
                            ],
                            [
                                'label' => 'Расход/Количество',
                                'content' => function ($model) {
                                    return ArrayHelper::getValue($model, "kredit.count");
                                }
                            ],
                            [
                                'label' => 'Расход/Цена без НДС',
                                'content' => function ($model) {
                                    return ArrayHelper::getValue($model, "kredit.price");
                                }
                            ],
                            [
                                'label' => 'Остатки/Количество',
                                'content' => function ($model) {
                                    return ArrayHelper::getValue($model, "remainder.count");
                                }
                            ],
                            [
                                'label' => 'Остатки/Цена без НДС',
                                'content' => function ($model) {
                                    return ArrayHelper::getValue($model, "remainder.price");
                                }
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

