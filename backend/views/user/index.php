<?php

use \yii\grid\GridView;
use \common\models\User;
use \common\models\search\UserSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Position;

$this->title = Yii::t('app/user', 'USER_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel UserSearch */
?>

<div class="form-group">
    <?= Html::a(
        Yii::t('app/user', 'USER_ADD'),
        Url::toRoute(['user/new']),
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
        'lastName',
        'firstName',
        'middleName',
        'username',
        [
            'attribute' => 'gender',
            'value'     => function (User $model) {
                return Yii::t('app/user', 'USER_GENDER_' . $model->gender);
            },
            'filter'    => Html::activeDropDownList(
                $searchModel,
                "gender",
                User::getGenderList(),
                [
                    "prompt" => Yii::t('app/user', 'USER_GENDER'),
                    'class'  => 'form-control'
                ]
            )
        ],
        [
            'attribute' => 'posName',
            'value'     => 'posName',
            'filter'    => Html::activeDropDownList(
                $searchModel,
                "posName",
                Position::getAllPositions(),
                [
                    "prompt" => Yii::t('app/position', 'POSITION'),
                    'class'  => 'form-control'
                ]
            )
        ],
        [
            'class'  => 'yii\grid\ActionColumn',
            'header' => Yii::t('app/user', 'ACTIONS'),
            'template' => '<div class="btn-group"> {detail} {update} {delete} </div>',
            'buttons'  => [
                'detail'   => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        Url::toRoute(['user/detail', 'id' => $model->id]),
                        ['class' => 'btn btn-success']
                    );
                },
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        Url::toRoute(['user/edit', 'id' => $model->id]),
                        ['class' => 'btn btn-warning']
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::toRoute(['user/delete', 'id' => $model->id]),
                        ['class' => 'btn btn-danger']
                    );
                },
            ],
        ],
    ]
]);