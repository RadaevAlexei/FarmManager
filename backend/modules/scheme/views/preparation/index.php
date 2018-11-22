<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use common\models\Packing;
use \backend\modules\scheme\models\Preparation;
use \backend\modules\scheme\models\search\PreparationSearch;

$this->title = Yii::t('app/preparation', 'PREPARATION_LIST');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $searchModel PreparationSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<div class="form-group">
    <?= Html::a(
        Yii::t('app/preparation', 'PREPARATION_ADD'),
        Url::toRoute(['preparation/new']),
        ['class' => 'btn btn-primary']
    ) ?>
</div>

<?php echo GridView::widget([
	"dataProvider" => $dataProvider,
	"filterModel"  => $searchModel,
	'columns'      => [
		['class' => 'yii\grid\SerialColumn'],
		'name',
		[
			'attribute' => 'receipt_date',
			'value'     => function (Preparation $model) {
				if (!empty($model->receipt_date)) {
					return Yii::$app->formatter->asDate($model->receipt_date, "d.M.Y");
				} else {
					return null;
				}
			}
		],
		[
			'attribute' => 'packing',
			'value'     => function (Preparation $model) {
				return Packing::getType($model->packing);
			}
		],
        'volume',
        'price',
		[
			'class'    => 'yii\grid\ActionColumn',
			'header'   => Yii::t('app/preparation', 'ACTIONS'),
			'template' => '<div class="btn-group">{update} {delete} </div>',
			'buttons'  => [
				'update' => function ($url, $model) {
					return Html::a(
						'<span class="glyphicon glyphicon-edit"></span>',
						Url::toRoute(['preparation/edit', 'id' => $model->id]),
						['class' => 'btn btn-warning']
					);
				},
				'delete' => function ($url, $model) {
					return Html::a(
						'<span class="glyphicon glyphicon-trash"></span>',
						Url::toRoute(['preparation/delete', 'id' => $model->id]),
						['class' => 'btn btn-danger']
					);
				},
			],
		],
	]
]);