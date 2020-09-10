<?php

use \yii\helpers\Url;
use \yii\helpers\Html;
use common\models\Animal;
use \yii\helpers\ArrayHelper;
use common\models\Calving;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;

/**
 * @var Animal $animal
 * @var ArrayDataProvider $dataProviderCalvings
 * @var integer $countSterileDays
 */


$countSterileDaysText = $countSterileDays ? "({$countSterileDays}-й день)" : "";
?>

<p>Статус: <?= Animal::getRectalStatusLabel($animal->rectal_examination) . $countSterileDaysText ?></p>

<div class="box-header">
    <?php if (Yii::$app->user->can('animalEdit')) : ?>
        <?= Html::button('Добавить отёл', [
            'class'    => 'btn btn-warning',
            'disabled' => !$animal->canAddCalving(),
            'data'     => [
                'toggle' => 'modal',
                'target' => '#add-calving-form-button',
            ]
        ]) ?>
    <?php endif; ?>
</div>

<div class="box box-success">
    <div class="box-header with-border" style="background-color: #0ead0e78">
        <h3 class="box-title">История отёлов</h3>
    </div>

    <div class="box-body">
        <?php foreach ($dataProviderCalvings->getModels() as $calving) :
            echo $this->render('/animal/tabs/calving-table', compact(
                'calving'
            ));
        endforeach; ?>
    </div>

</div>

<!-- Модальное окно добавления осеменения -->
<div class="modal fade" id="add-calving-form-button" tabindex="-1" role="dialog"
     aria-labelledby="addCalvingLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="addCalvingLabel">Добавление отёла</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->render('/animal/forms/add-calving', compact(
                    'animal'
                )) ?>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования осеменения -->
<div class="modal fade" id="edit-calving-modal" tabindex="-1" role="dialog"
     aria-labelledby="editCalvingLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="editCalvingLabel">Редактирование отёла</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
