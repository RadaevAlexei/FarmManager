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
$note = ArrayHelper::getValue(current($calving), 'note');
$userLastname = ArrayHelper::getValue(current($calving), 'lastname');
$calvingId = current($calving)['calving_id'];

?>

<div class="calving_table" id="<?= $calvingId ?>">
    <p>
        <strong>Отёл: </strong>
        <span class='label label-danger'><?= $dateCalving ?></span>
        <span class='label label-success'><?= Calving::getStatusLabel($dateStatus) ?></span>
        <span class='label label-primary'><?= Calving::getPositionLabel($datePosition) ?></span>
        <?= Html::button('<span class="glyphicon glyphicon-edit"></span>', [
            'id'    => 'edit-calving-button',
            'class' => 'btn btn-warning btn-sm',
            'data'  => [
                'toggle' => 'modal',
                'url'    => Url::toRoute([
                    'animal/edit-calving-form',
                    'calvingId' => $calvingId
                ])
            ]
        ]) ?>
        <?= Html::a(
            '<span class="glyphicon glyphicon-trash"></span>',
            Url::toRoute(['animal/remove-calving', 'id' => $calvingId]),
            [
                'class' => 'btn btn-danger btn-sm',
                'data'  => ['confirm' => 'Вы действительно хотите удалить отёл?'],
            ]
        ) ?>
    </p>
    <p><strong>Провёл: </strong><?= $userLastname ?></p>

    <?php echo GridView::widget([
            'formatter'    => [
                'class'       => 'yii\i18n\Formatter',
                'nullDisplay' => ''
            ],
            "dataProvider" => $dataProvider,
            'summary'      => false,
            'tableOptions' => [
                'style' => 'display:block; width:100%; overflow-x:auto',
                'class' => 'table table-striped'
            ],
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label'   => 'Бирка',
                    'content' => function ($model) {
                        if ($model['health_status'] == Animal::HEALTH_STATUS_DEAD) {
                            return 'Мертвый';
                        }

                        return Html::a(
                            ArrayHelper::getValue($model, "label"),
                            Url::toRoute(['/animal/detail/', 'id' => $model['child_animal_id']]),
                            ["target" => "_blank"]
                        );
                    }
                ],
                [
                    'label'   => 'Пол',
                    'content' => function ($model) {
                        $isFremartinText = $model['fremartin'] ? "(фримартин)" : "";
                        $class = ($model['sex'] == Animal::SEX_TYPE_WOMAN) ? "primary" : "success";

                        return "<span class='label label-$class'>" . Animal::getPhysicalState($model['physical_state']) . $isFremartinText . "</span>";
                    }
                ],
                [
                    'label'   => 'Вес при рождении, кг',
                    'content' => function ($model) {
                        return ArrayHelper::getValue($model, 'birth_weight');
                    }
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'header'   => 'Действия',
                    'template' => '<div class="btn-group">{edit} {delete}</div>',
                    'buttons'  => ['delete' => function ($url, $model) use ($calvingId) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            Url::toRoute([
                                'animal/remove-animal-from-calving',
                                'animalId'  => $model['child_animal_id'],
                                'calvingId' => $calvingId
                            ]),
                            ['class' => 'btn btn-danger btn-sm',
                             'data'  => ['confirm' => 'Вы действительно хотите удалить животного из отёла?'],]
                        );
                    }],
                ]
            ]
        ]
    ); ?>

    <p><strong>Примечание: </strong><?= $note ?></p>

</div>