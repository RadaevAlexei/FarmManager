<?php

use \yii\grid\GridView;
use \yii\data\ActiveDataProvider;
use \backend\modules\pharmacy\models\search\CashBookSearch2;
use \yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\pharmacy\models\CashBook;

$this->title = 'Приход/Расход';
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel CashBookSearch2
 * @var $dataProvider ActiveDataProvider
 */

?>

    <div class="form-group">
        <?= Html::a(
            'Добавить приход',
            Url::toRoute(['cash-book/new', 'type' => CashBook::TYPE_DEBIT]),
            ['class' => 'btn btn-primary']
        ) ?>
        <?= Html::a(
            'Добавить расход',
            Url::toRoute(['cash-book/new', 'type' => CashBook::TYPE_KREDIT]),
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

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
            'label' => 'Наименование препарата',
            'content'   => function ($model) {
                return ArrayHelper::getValue($model, "preparation_name");
            }
        ],
        [
            'label' => 'Приход/Количество',
            'content'   => function ($model) {
                return ArrayHelper::getValue($model, "debit.count");
            }
        ],
        [
            'label' => 'Приход/Цена без НДС',
            'content'   => function ($model) {
                return ArrayHelper::getValue($model, "debit.price");
            }
        ],
        [
            'label' => 'Расход/Количество',
            'content'   => function ($model) {
                return ArrayHelper::getValue($model, "kredit.count");
            }
        ],
        [
            'label' => 'Расход/Цена без НДС',
            'content'   => function ($model) {
                return ArrayHelper::getValue($model, "kredit.price");
            }
        ],
        [
            'label' => 'Остатки/Количество',
            'content'   => function ($model) {
                return ArrayHelper::getValue($model, "remainder.count");
            }
        ],
        [
            'label' => 'Остатки/Цена без НДС',
            'content'   => function ($model) {
                return ArrayHelper::getValue($model, "remainder.price");
            }
        ],
    ]
]);