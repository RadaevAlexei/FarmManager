<?php

use \yii\grid\GridView;
use \common\models\User;
use \common\models\search\UserSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Position;
use \hail812\adminlte3\widgets\FlashAlert;

$this->title = Yii::t('app/user', 'USER_LIST');
$this->params['breadcrumbs'][] = $this->title;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel UserSearch */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?= FlashAlert::widget(); ?>
            <div class="card card-primary">
                <div class="card-body">
                    <?php if (Yii::$app->user->can('userEdit')) : ?>
                        <div class="form-group">
                            <?= Html::a(
                                Yii::t('app/user', 'USER_ADD'),
                                Url::toRoute(['user/new']),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                    <?php endif; ?>

                    <?php echo GridView::widget([
                        "dataProvider" => $dataProvider,
                        "filterModel" => $searchModel,
                        'formatter' => [
                            'class' => 'yii\i18n\Formatter',
                            'nullDisplay' => '',
                        ],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'lastName',
                            'firstName',
                            'middleName',
                            'username',
                            [
                                'attribute' => 'gender',
                                'value' => function (User $model) {
                                    return Yii::t('app/user', 'USER_GENDER_' . $model->gender);
                                },
                                'filter' => Html::activeDropDownList(
                                    $searchModel,
                                    "gender",
                                    User::getGenderList(),
                                    [
                                        "prompt" => Yii::t('app/user', 'USER_GENDER'),
                                        'class' => 'form-control'
                                    ]
                                )
                            ],
                            [
                                'attribute' => 'posName',
                                'value' => 'posName',
                                'filter' => Html::activeDropDownList(
                                    $searchModel,
                                    "posName",
                                    Position::getAllPositions(),
                                    [
                                        "prompt" => Yii::t('app/position', 'POSITION'),
                                        'class' => 'form-control'
                                    ]
                                )
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/user', 'ACTIONS'),
                                'template' => '<div class="btn-group">{update} {delete} </div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('userEdit'),
                                ],
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-edit"></span>',
                                            Url::toRoute(['user/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-sm fa-trash"></span>',
                                            Url::toRoute(['user/delete', 'id' => $model->id]),
                                            ['class' => 'btn btn-danger']
                                        );
                                    },
                                ],
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
