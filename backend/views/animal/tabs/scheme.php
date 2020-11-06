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
$disabledChooseDiagnosis = $animal->isHealthy();
$disabledAppropriationScheme = $animal->isHealthy() || !$animal->diagnosis;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <?= $this->render('../scheme/control-health', compact(
                'animal',
                'healthModel'
            )) ?>
        </div>

        <div class="col-md-4">
            <?= $this->render('../scheme/choose-diagnosis', compact(
                'animal',
                'animalDiagnosisForm',
                'disabledChooseDiagnosis'
            )) ?>
        </div>

        <div class="col-md-4">
            <?= $this->render('../scheme/appropriation-scheme', compact(
                'schemeList',
                'appropriationScheme',
                'disabledAppropriationScheme'
            )) ?>
        </div>
    </div>
</div>

<?php if ($animal->isSick()) : ?>
    <div class="container-fluid">
        <div class="row">

        </div>
    </div>
<?php endif; ?>

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
