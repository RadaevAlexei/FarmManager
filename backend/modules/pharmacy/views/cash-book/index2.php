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
            'attribute' => 'user_id',
            'content'   => function (CashBook $model) {
                return ArrayHelper::getValue($model, "user.lastName");
            }
        ],
        [
            'attribute' => 'type',
            'content' => function (CashBook $model) {
                $class = ($model->type == CashBook::TYPE_DEBIT) ? "primary" : "danger";
                return "<span class='label label-$class'>" . $model->getTypeName() . "</span>";
            }
        ],
        'date',
        [
            'attribute' => 'preparation_id',
            'content'   => function (CashBook $model) {
                return ArrayHelper::getValue($model, "preparation.name");
            }
        ],
        [
            'attribute' => 'stock_id',
            'content'   => function (CashBook $model) {
                return ArrayHelper::getValue($model, "stock.name");
            }
        ],
        'count',
        'measure',
        'volume',
        'price',
        'total_price',
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => 'Действия',
            'template' => '<div class="btn-group">{delete} </div>',
            'buttons'  => [
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['cash-book/delete', 'id' => $model->id]),
                        [
                            'class' => 'btn btn-danger',
                            'data'  => ['confirm' => 'Вы действительно хотите удалить эту запись?']
                        ]
                    );
                },
            ],
        ],
    ]
]);