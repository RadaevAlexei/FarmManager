<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\data\ActiveDataProvider;
use \backend\modules\scheme\models\search\SchemeSearch;
use \backend\modules\scheme\models\Scheme;
use \yii\helpers\ArrayHelper;

$this->title = Yii::t('app/scheme', 'SCHEME_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider ActiveDataProvider */
/** @var $searchModel SchemeSearch */

?>

    <div class="form-group">
        <?= Html::a(
            Yii::t('app/scheme', 'SCHEME_ADD'),
            Url::toRoute(['scheme/new']),
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
        'name',
        [
            'attribute' => 'diagnosis_id',
            'value'     => function (Scheme $model) {
                return ArrayHelper::getValue($model, "diagnosis.name");
            }
        ],
        [
            'attribute' => 'created_by',
            'value'     => function (Scheme $model) {
                return ArrayHelper::getValue($model, "createdBy.username");
            }
        ],
        [
            'attribute' => 'created_at',
            'value'     => function (Scheme $model) {
                if (!empty($model->created_at)) {
                    return date('d.m.Y H:i:s', $model->created_at);
                } else {
                    return null;
                }
            }
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => Yii::t('app/scheme', 'ACTIONS'),
            'template' => '<div class="btn-group">{update} {delete} </div>',
            'buttons'  => [
                'detail' => function ($url, Scheme $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        Url::toRoute(['scheme/detail', 'id' => $model->id]),
                        ['class' => 'btn btn-primary']
                    );
                },
                'update' => function ($url, Scheme $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['scheme/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, Scheme $model) {
                    if ($model->approve) {
                        return '';
                    }

                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['scheme/delete', 'id' => $model->id]),
                        ['class' => 'btn btn-danger']
                    );
                },
            ],
        ],
    ]
]);