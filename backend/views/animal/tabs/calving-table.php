<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Bull;
use common\models\Animal;
use \common\models\Calving;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var array $calving
 */

$dataProvider = new ArrayDataProvider(['allModels' => $calving]);
$dateCalving = (new DateTime(current($calving)['date']))->format('d.m.Y');
$dateStatus = ArrayHelper::getValue(current($calving), 'status');
$datePosition = ArrayHelper::getValue(current($calving), 'position');
$calvingId = current($calving)['calving_id'];

?>

<div class="calving_table" id="<?= $calvingId ?>">
    <p1>Отёл:
        <span class='label label-danger'><?= $dateCalving ?></span>
        <span class='label label-success'><?= Calving::getStatusLabel($dateStatus) ?></span>
        <span class='label label-primary'><?= Calving::getPositionLabel($datePosition) ?></span>
        <?= Html::button('<span class="glyphicon glyphicon-edit"></span>', [
            'id' => 'edit-calving-button',
            'class' => 'btn btn-warning btn-sm',
            /*'data' => [
                'toggle' => 'modal',
                'url' => Url::toRoute([
                    'animal/edit-calving-form',
                    'calvingId' => $calvingId
                ])
            ]*/
        ]) ?>
        <?= Html::a(
            '<span class="glyphicon glyphicon-trash"></span>',
            Url::toRoute(['animal/remove-calving', 'id' => $calvingId]),
            [
                'class' => 'btn btn-danger btn-sm',
                'data' => ['confirm' => 'Вы действительно хотите удалить отёл?'],
            ]
        ) ?>
    </p1>

    <?php echo GridView::widget(['formatter' => ['class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',],
            "dataProvider" => $dataProvider,
            'summary' => false,
            'tableOptions' => ['style' => 'display:block; width:100%; overflow-x:auto',
                'class' => 'table table-striped',],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Бирка',
                    'content' => function ($model) {
                        return ArrayHelper::getValue($model, 'label');
                    }
                ],
                [
                    'label' => 'Пол',
                    'content' => function ($model) {
                        $class = ($model['sex'] == Bull::ANIMAL_SEX_TYPE) ? "primary" : "success";
                        return "<span class='label label-$class'>" . Animal::getPhysicalState($model['physical_state']) . "</span>";
                    }
                ],
                [
                    'label' => 'Вес при рождении, кг',
                    'content' => function ($model) {
                        return ArrayHelper::getValue($model, 'birth_weight');
                    }
                ],
                [
                    'label' => 'Провел',
                    'content' => function ($model) {
                        return ArrayHelper::getValue($model, "lastname");
                    }
                ],
                [
                    'label' => 'Примечание',
                    'content' => function ($model) {
                        return ArrayHelper::getValue($model, 'note');
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'template' => '<div class="btn-group">{edit} {delete}</div>',
                    'buttons' => ['delete' => function ($url, $model) use ($calvingId) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            Url::toRoute([
                                'animal/remove-animal-from-calving',
                                'animalId' => $model['child_animal_id'],
                                'calvingId' => $calvingId
                            ]),
                            ['class' => 'btn btn-danger btn-sm',
                                'data' => ['confirm' => 'Вы действительно хотите удалить животного из отёла?'],]
                        );
                    }],
                ]
            ]
        ]
    ); ?>

</div>