<?php

use \yii\grid\GridView;
use \common\models\search\CowSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Animal;
use \common\models\Bull;

$this->title = Yii::t('app/animal', 'ANIMAL_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel CowSearch */

?>

<div class="form-group">
    <?= Html::a(
        Yii::t('app/animal', 'ANIMAL_ADD'),
        Url::toRoute(['animal/new']),
        [
            'class' => 'btn btn-primary'
        ]
    ) ?>
</div>

<?php
echo GridView::widget([
    "dataProvider" => $dataProvider,
//    "filterModel"  => $searchModel,
    'tableOptions' => [
        'style' => 'display:block; width:100%; overflow-x:auto',
        'class' => 'table table-striped',
    ],
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'nickname',
            'content'   => function ($model) {
                return Html::a(
                    $model->nickname,
                    Url::toRoute(['animal/detail', 'id' => $model->id]),
                    [
                        "target" => "_blank"
                    ]
                );
            }
        ],
        'label',
        'birthday',
        [
            'attribute' => 'cowshed_id',
            'content'   => function ($model) {
                return $model->cowshed->name;
            }
        ],
        [
            'attribute' => 'farm_id',
            'content'   => function ($model) {
                return $model->farm->name;
            }
        ],
        [
            'attribute' => 'sex',
            'content'   => function ($model) {
                $class = ($model->sex == Bull::ANIMAL_SEX_TYPE) ? "primary" : "success";
                return "<span class='label label-$class'>" . Animal::getSexType($model->sex) . "</span>";
            }
        ],
        [
            'attribute' => 'physical_state',
            'content'   => function ($model) {
                return '<span class="label label-danger">' . Animal::getPhysicalState($model->physical_state) . '</span>';
            }
        ],
        [
            'attribute' => 'status',
            'content'   => function ($model) {
                return '<span class="label label-warning">' . Animal::getStatus($model->status) . '</span>';
            }
        ],
        [
            'attribute' => 'rectal_examination',
            'content'   => function ($model) {
                return '<span class="label label-primary">' . Animal::getRectalExamination($model->rectal_examination) . '</span>';
            }
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => Yii::t('app/animal', 'ACTIONS'),
            'template' => '<div class="btn-group">{edit} {delete}</div>',
            'buttons'  => [
                'edit'   => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['animal/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['animal/delete', 'id' => $model->id]),
                        [
                            'class' => 'btn btn-danger',
                            'data'  => ['confirm' => 'Вы уверены, что хотите удалить этот элемент?']
                        ]
                    );
                },
            ],
        ]
    ]
]);