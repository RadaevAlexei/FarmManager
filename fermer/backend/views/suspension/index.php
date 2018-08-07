<?php

use \yii\grid\GridView;
use \common\models\search\SuspensionSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Animal;

$this->title = Yii::t('app/suspension', 'SUSPENSION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel SuspensionSearch */

?>

<div class="form-group">
    <?= Html::a(
        Yii::t('app/suspension', 'SUSPENSION_ADD'),
        Url::toRoute(['suspension/new']),
        [
            'class' => 'btn btn-primary'
        ]
    ) ?>
</div>

<?php echo GridView::widget([
    "dataProvider" => $dataProvider,
    "filterModel"  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'animal',
            'content'   => function ($model) {
                return '<span class="label label-success">' . $model->animalInfo->label . '</span>';
            }
        ],
        [
            'label' => 'Дата Рождения',
            'content'   => function ($model) {
                return '';
            }
        ],
        [
            'label' => 'Вес при рождении',
            'content'   => function ($model) {
                return '';
            }
        ],
        [
            'label' => 'Пол телёнка',
            'content'   => function ($model) {
                return '';
            }
        ],
        'date',
        'weight',
        [
            'label' => 'Дата текущего взвешивания',
            'content'   => function ($model) {
                return '';
            }
        ],
        [
            'label' => 'Вес текущего взвешивания',
            'content'   => function ($model) {
                return '';
            }
        ],
        [
            'label' => 'Возраст телёнка',
            'content'   => function ($model) {
                return '';
            }
        ],
        [
            'label' => 'Кормовые дни',
            'content'   => function ($model) {
                return '';
            }
        ],
        [
            'label' => 'Привес, кг',
            'content'   => function ($model) {
                return '';
            }
        ],
        [
            'label' => 'Среднесуточный привес',
            'content'   => function ($model) {
                return '';
            }
        ],
        /*[
            'class'    => 'yii\grid\ActionColumn',
            'header'   => Yii::t('app/suspension', 'ACTIONS'),
            'template' => '<div class="btn-group">{update} {delete}</div>',
            'buttons'  => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['suspension/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['suspension/delete', 'id' => $model->id]),
                        ['class' => 'btn btn-danger']
                    );
                },
            ],
        ],*/
    ]
]);