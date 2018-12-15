<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\scheme\models\search\DiagnosisSearch;

$this->title = Yii::t('app/diagnosis', 'DIAGNOSIS_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider ActiveDataProvider */
/** @var $searchModel DiagnosisSearch */

?>

    <div class="form-group">
		<?= Html::a(
			Yii::t('app/diagnosis', 'DIAGNOSIS_ADD'),
			Url::toRoute(['diagnosis/new']),
			[
				'class' => 'btn btn-primary'
			]
		) ?>
    </div>

<?php echo GridView::widget([
	"dataProvider" => $dataProvider,
	"filterModel"  => $searchModel,
    'tableOptions' => [
        'style' => 'display:block; width:100%; overflow-x:auto',
        'class' => 'table table-striped',
    ],
	'columns'      => [
		['class' => 'yii\grid\SerialColumn'],
		'name',
		[
			'class'    => 'yii\grid\ActionColumn',
			'header'   => Yii::t('app/diagnosis', 'ACTIONS'),
			'template' => '<div class="btn-group"> {update} {delete} </div>',
			'buttons'  => [
				'update' => function ($url, $model) {
					return Html::a(
						'<span class="glyphicon glyphicon-edit"></span>',
						Url::toRoute(['diagnosis/edit', 'id' => $model->id]),
						['class' => 'btn btn-warning']
					);
				},
				'delete' => function ($url, $model) {
					return Html::a(
						'<span class="glyphicon glyphicon-trash"></span>',
						Url::toRoute(['diagnosis/delete', 'id' => $model->id]),
						['class' => 'btn btn-danger']
					);
				},
			],
		],
	]
]);