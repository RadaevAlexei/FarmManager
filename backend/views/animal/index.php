<?php

use \yii\grid\GridView;
use \common\models\search\CowSearch;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Animal;
use \common\models\Bull;
use \yii\widgets\ActiveForm;
use \backend\models\forms\UploadForm;
use \backend\assets\AnimalAsset;
use \backend\modules\scheme\models\AppropriationScheme;
use \yii\helpers\ArrayHelper;

$this->title = Yii::t('app/animal', 'ANIMAL_LIST');
$this->params['breadcrumbs'][] = $this->title;
$uploadModel = new UploadForm();

AnimalAsset::register($this);

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel CowSearch */

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">

                <div class="card-header">
                    <h3 class="card-title">Обновление данных</h3>
                </div>

                <div class="card-body">

                    <?php if (Yii::$app->user->can('animalEdit')) : ?>

                        <?php $form = ActiveForm::begin([
                            'action' => Url::toRoute(['update-from-file']),
                            'options' => ['enctype' => 'multipart/form-data']
                        ]) ?>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= Html::a(
                                        Yii::t('app/animal', 'ANIMAL_ADD'),
                                        Url::toRoute(['animal/new']),
                                        [
                                            'class' => 'btn btn-primary'
                                        ]
                                    ) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= $form->field($uploadModel, 'file')->fileInput() ?>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Обновить</button>
                                </div>
                            </div>
                        </div>

                        <?php ActiveForm::end() ?>
                    <?php endif; ?>


                    <?php echo GridView::widget([
                        "dataProvider" => $dataProvider,
                        "filterModel" => $searchModel,
                        'formatter' => [
                            'class' => 'yii\i18n\Formatter',
                            'nullDisplay' => '',
                        ],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'rowOptions' => function (Animal $model, $key, $index, $grid) {
                            /** @var AppropriationScheme $onScheme */
                            $onScheme = $model->onScheme();
                            $class = $onScheme ? "animal-on-scheme" : "";

                            /*if ($onScheme) {
                                $existNewActions = ActionHistory::find()
                                    ->where([
                                        'appropriation_scheme_id' => $onScheme->id,
                                        'status' => ActionHistory::STATUS_NEW
                                    ])
                                    ->exists();

                                if (!$existNewActions) {
                                    $class = "executed-scheme";
                                }
                            }*/

                            return [
                                'class' => $class
                            ];
                        },
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'nickname',
                            'collar',
                            [
                                'attribute' => 'label',
                                'content' => function ($model) {
                                    return Html::a(
                                        $model->label,
                                        Url::toRoute(['animal/detail', 'id' => $model->id]),
                                        [
                                            "target" => "_blank"
                                        ]
                                    );
                                }
                            ],
                            [
                                'attribute' => 'birthday',
                                'content' => function (Animal $model) {
                                    return (new DateTime($model->birthday))->format('d.m.Y');
                                }
                            ],
                            [
                                'attribute' => 'cowshed_id',
                                'content' => function ($model) {
                                    return $model->cowshed->name;
                                }
                            ],
                            [
                                'attribute' => 'animal_group_id',
                                'content' => function ($model) {
                                    return ArrayHelper::getValue($model, "animalGroup.name");
                                }
                            ],
                            [
                                'attribute' => 'farm_id',
                                'content' => function ($model) {
                                    return $model->farm->name;
                                }
                            ],
                            [
                                'attribute' => 'sex',
                                'content' => function ($model) {
                                    $class = ($model->sex == Bull::ANIMAL_SEX_TYPE) ? "primary" : "success";
                                    return "<span class='label label-$class'>" . Animal::getSexType($model->sex) . "</span>";
                                }
                            ],
                            [
                                'attribute' => 'physical_state',
                                'content' => function ($model) {
                                    return '<span class="label label-danger">' . Animal::getPhysicalState($model->physical_state) . '</span>';
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'content' => function ($model) {
                                    return '<span class="label label-warning">' . Animal::getStatus($model->status) . '</span>';
                                }
                            ],
                            [
                                'attribute' => 'rectal_examination',
                                'content' => function ($model) {
                                    return '<span class="label label-primary">' . Animal::getRectalExamination($model->rectal_examination) . '</span>';
                                }
                            ],
                            [
                                'label' => 'Схема',
                                'content' => function (Animal $model) {
                                    /** @var AppropriationScheme[] $appropriationSchemes */
                                    $appropriationSchemes = $model->onScheme();

                                    $result = '';
                                    foreach ($appropriationSchemes as $appropriationScheme) {
                                        $result .= '<span class="label label-primary">' . ArrayHelper::getValue($appropriationScheme,
                                                "scheme.name") . '</span><br>';
                                    }

                                    return $result;
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app/animal', 'ACTIONS'),
                                'template' => '<div class="btn-group">{edit} {delete}</div>',
                                'visibleButtons' => [
                                    'delete' => Yii::$app->user->can('animalEdit')
                                ],
                                'buttons' => [
                                    'edit' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-edit"></span>',
                                            Url::toRoute(['animal/edit', 'id' => $model->id]),
                                            ['class' => 'btn btn-warning']
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fas fa-trash"></span>',
                                            Url::toRoute(['animal/delete', 'id' => $model->id]),
                                            [
                                                'class' => 'btn btn-danger',
                                                'data' => ['confirm' => 'Вы уверены, что хотите удалить этот элемент?']
                                            ]
                                        );
                                    },
                                ],
                            ]
                        ]
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>