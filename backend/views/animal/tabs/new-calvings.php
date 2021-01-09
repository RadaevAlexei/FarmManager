<?php

use \yii\helpers\Url;
use \yii\helpers\Html;
use common\models\Animal;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;
use \hail812\adminlte3\widgets\Alert;
use common\models\Calving;
use yii\helpers\ArrayHelper;

/**
 * @var Animal $animal
 * @var ArrayDataProvider $dataProviderCalvings
 */

?>

<div class="container-fluid">
    <div class="row pb-2">
        <div class="col-md-12">
            <?php if (Yii::$app->user->can('animalEdit')) : ?>
                <?= Html::button('Добавить отёл', [
                    'class' => 'btn btn-success',
                    'data'  => [
                        'toggle' => 'modal',
                        'target' => '#add-calving-form-button',
                    ]
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($dataProviderCalvings->getModels()) : ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('calving-tabs', [
                    'dataProvider' => $dataProviderCalvings
                ]) ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно добавления отёла -->
<div class="modal fade" id="add-calving-form-button" tabindex="-1" role="dialog"
     aria-labelledby="addCalvingLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0ead0e78">
                <h5 class="modal-title" id="addCalvingLabel">Добавление отёла</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->render('/animal/forms/add-calving',
                    compact('animal')
                ) ?>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования отёла -->
<div class="modal fade" id="edit-calving-modal" tabindex="-1" role="dialog"
     aria-labelledby="editCalvingLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
