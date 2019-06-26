<?php

use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\grid\GridView;
use \yii\helpers\ArrayHelper;
use \yii\data\ArrayDataProvider;
use \backend\models\search\AnimalSickSearch;
use \backend\assets\AnimalAsset;
use \common\models\Animal;
use \backend\modules\scheme\models\AppropriationScheme;

$this->title = 'Список больных животных';
$this->params['breadcrumbs'][] = $this->title;

AnimalAsset::register($this);
/**
 * /** @var $dataProvider ArrayDataProvider
 * /** @var $searchModel AnimalSickSearch
 */
?>

    <div class="form-group">
        <?= Html::a(
            'Скачать список больных животных',
            $dataProvider->getModels() ? Url::toRoute(['animal/download-sick-list']) : "#",
            [
                'class'    => 'btn btn-success',
                'disabled' => $dataProvider->getModels() ? false : true
            ]
        ) ?>
    </div>

<?php echo GridView::widget([
    'formatter'    => [
        'class'       => 'yii\i18n\Formatter',
        'nullDisplay' => '',
    ],
    'tableOptions' => [
        'class' => 'table table-striped animal-table-hover',
    ],
    "dataProvider" => $dataProvider,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => '№ Ошейника',
            'value' => function (Animal $model) {
                return ArrayHelper::getValue($model, "collar");
            }
        ],
        [
            'label'   => '№ Бирки',
            'content' => function (Animal $model) {
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
            'label' => 'Дата заболевания',
            'value' => function (Animal $model) {
                return (new DateTime(ArrayHelper::getValue($model,
                    "date_health")))->format('d.m.Y');
            }
        ],
        [
            'label' => 'Дата постановки на схему',
            'value' => function (Animal $model) {
                return (new DateTime(ArrayHelper::getValue($model,
                    "appropriationScheme.started_at")))->format('d.m.Y');
            }
        ],
        [
            'label' => 'Диагноз',
            'value' => function (Animal $model) {
                return ArrayHelper::getValue($model, "diagnoses.name");
            }
        ],
        [
            'label'  => 'Наименование схемы',
            'format' => 'raw',
            'value'  => function (Animal $model) {
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
            'label' => 'Сколько дней лечения',
            'value' => function (Animal $model) {
                return count(ArrayHelper::getValue($model, "appropriationScheme.scheme.schemeDays"));
            }
        ]
    ]
]);