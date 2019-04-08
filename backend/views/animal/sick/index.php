<?php

use \yii\helpers\Html;
use \yii\helpers\Url;
use \yii\grid\GridView;
use \yii\helpers\ArrayHelper;
use \yii\data\ArrayDataProvider;
use \backend\models\search\AnimalSickSearch;

$this->title = 'Список больных животных';
$this->params['breadcrumbs'][] = $this->title;

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
    "dataProvider" => $dataProvider,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => '№ Ошейника',
            'value' => function ($model) {
                return ArrayHelper::getValue($model, "collar");
            }
        ],
        [
            'label' => '№ Бирки',
            'value' => function ($model) {
                return ArrayHelper::getValue($model, "label");
            }
        ],
        [
            'label' => 'Дата заболевания',
            'value' => function ($model) {
                return (new DateTime(ArrayHelper::getValue($model,
                    "date_health")))->format('d.m.Y');
            }
        ],
        [
            'label' => 'Дата постановки на схему',
            'value' => function ($model) {
                return (new DateTime(ArrayHelper::getValue($model,
                    "appropriationScheme.started_at")))->format('d.m.Y');
            }
        ],
        [
            'label' => 'Диагноз',
            'value' => function ($model) {
                return ArrayHelper::getValue($model, "diagnoses.name");
            }
        ],
        [
            'label' => 'Наименование схемы',
            'value' => function ($model) {
                return ArrayHelper::getValue($model, "appropriationScheme.scheme.name");
            }
        ],
        [
            'label' => 'Сколько дней лечения',
            'value' => function ($model) {
                return count(ArrayHelper::getValue($model, "appropriationScheme.scheme.schemeDays"));
            }
        ]
    ]
]);