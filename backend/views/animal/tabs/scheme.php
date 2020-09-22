<?php

use \yii\helpers\Url;
use \common\models\Animal;
use \backend\modules\scheme\models\Scheme;
use \yii\bootstrap4\ActiveForm;
use \backend\modules\scheme\models\AppropriationScheme;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use \yii\helpers\ArrayHelper;
use \backend\modules\scheme\models\Diagnosis;
use \backend\models\forms\HealthForm;
use \backend\models\forms\AnimalDiagnosisForm;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;

/**
 * @var Animal $animal
 * @var Scheme[] $schemeList
 * @var AppropriationScheme $appropriationScheme
 * @var ArrayDataProvider $dataProvider
 */

$healthModel = new HealthForm([
    'date_health' => $animal->date_health,
    'health_status_comment' => $animal->health_status_comment
]);
$animalDiagnosisForm = new AnimalDiagnosisForm();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Контроль здоровья</h3>
                </div>

                <?php $formHealth = ActiveForm::begin([
                    'action' => Url::toRoute(['update-health']),
                ]); ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $formHealth->field($healthModel, 'health_status')->dropDownList(
                                    Animal::getHealthStatusList(),
                                    [
                                        'id' => 'health_status_list',
                                        'prompt' => 'Выберите статус здоровья',
                                        'class' => 'form-control form-control-sm',
                                        'value' => $animal->health_status,
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $formHealth->field($healthModel, 'health_status_comment')->textInput(
                                    ['class' => 'form-control form-control-sm']
                                ) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $formHealth->field($healthModel, 'date_health')->widget(DatePicker::class, [
                                    'language' => 'ru',
                                    'dateFormat' => 'dd.MM.yyyy',
                                    'options' => ['class' => 'form-control form-control-sm', 'autocomplete' => 'off']
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <?= $formHealth->field($healthModel, 'animal_id')
                                ->hiddenInput(['value' => $animal->id])
                                ->label(false)
                            ?>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('animalEdit')) : ?>
                        <?= Html::submitButton('Сменить состояние здоровья', [
                            'class' => 'btn btn-sm btn-success',
                            'data' => ['confirm' => 'Вы действительно хотите сменить состояние здоровья?']
                        ]) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php if ($animal->isSick()) : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Диагноз</h3>
                    </div>

                    <?php $formDiagnosis = ActiveForm::begin([
                        'action' => Url::toRoute(['update-diagnoses']),
                    ]); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <?= $formDiagnosis->field($animalDiagnosisForm, 'diagnosis')->dropDownList(
                                        ArrayHelper::map(Diagnosis::getAllList(), "id", "name"),
                                        [
                                            'prompt' => 'Выберите диагноз',
                                            'class' => ['form-control form-control-sm'],
                                            'value' => $animal->diagnosis,
                                        ]
                                    ) ?>
                                </div>
                                <div class="form-group">
                                    <?= $formDiagnosis->field($animalDiagnosisForm, 'animal_id')
                                        ->hiddenInput(['value' => $animal->id])
                                        ->label(false)
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <?php if (Yii::$app->user->can('animalEdit')) : ?>
                            <?= Html::submitButton('Поставить диагноз', [
                                'class' => 'btn btn-sm btn-success',
                                'data' => ['confirm' => 'Вы действительно хотите поставить такой диагноз?']
                            ]) ?>
                        <?php endif; ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Поставить на схему лечения</h3>
                </div>

                <?php $formOnScheme = ActiveForm::begin([
                    'action' => Url::toRoute(['appropriation-scheme']),
                ]); ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <?= $formOnScheme->field($appropriationScheme, 'started_at')->widget(DatePicker::class, [
                                'language' => 'ru',
                                'dateFormat' => 'yyyy-MM-dd',
                                'options' => [
                                    'class' => 'form-control form-control-sm',
                                    'autocomplete' => 'off'
                                ]
                            ]) ?>
                        </div>
                        <div class="col-sm-7">
                            <?= $formOnScheme->field($appropriationScheme, 'scheme_id')->dropDownList(
                                $schemeList,
                                [
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => 'Выберите схему'
                                ]
                            ) ?>
                            <?= $formOnScheme->field($appropriationScheme, 'animal_id')->hiddenInput()->label(false); ?>
                            <?= $formOnScheme->field($appropriationScheme, 'status')->hiddenInput()->label(false); ?>
                        </div>
                        <div class="col-sm-2">
                            <label>&nbsp;</label>
                            <?php if (Yii::$app->user->can('animalEdit')) : ?>
                                <?= Html::submitButton('Поставить на схему', [
                                    'class' => 'btn btn-sm btn-success',
                                    'data' => ['confirm' => 'Вы действительно хотите поставить на эту схему?']
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Животное находится на следующих схемах</h3>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        "dataProvider" => $dataProvider,
                        'summary' => false,
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
                        'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'started_at',
                                'content' => function (AppropriationScheme $model) {
                                    return (new DateTime(ArrayHelper::getValue($model, 'started_at')))->format('d.m.Y');
                                }
                            ],
                            [
                                'attribute' => 'scheme_id',
                                'content' => function (AppropriationScheme $model) {
                                    return Html::a(
                                        ArrayHelper::getValue($model, "scheme.name"),
                                        Url::toRoute(['scheme/scheme/edit/', 'id' => $model->scheme_id]),
                                        ["target" => "_blank"]
                                    );
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '',
                                'template' => '<div class="btn-group">{close-scheme} {delete}</div>',
                                'visibleButtons' => [
                                    'close-scheme' => Yii::$app->user->can('animalEdit'),
                                    'delete' => Yii::$app->user->can('animalEdit'),
                                ],
                                'buttons' => [
                                    'close-scheme' => function ($url, AppropriationScheme $model) {
                                        return Html::button('Завершить схему', [
                                            'id' => 'close-form-button',
                                            'class' => 'btn btn-sm btn-warning',
                                            'data' => [
                                                'toggle' => 'modal',
                                                'url' => Url::toRoute([
                                                    'animal/close-scheme-form',
                                                    'id' => $model->id
                                                ]),
                                                'animal_id' => $model->animal_id,
                                                'appropriation_scheme_id' => $model->id,
                                            ],
                                            'disabled' => $model->getListNewActions() ? true : false,
                                        ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            'Убрать со схемы',
                                            Url::toRoute(['animal/remove-from-scheme', 'id' => $model->id]),
                                            [
                                                'class' => 'btn btn-sm btn-danger',
                                                'data' => ['confirm' => 'Вы действительно хотите убрать животное со схемы?']
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

<!-- Модальное окно завершения схемы -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="exampleModalLabel">Завершение схемы - выписка</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
