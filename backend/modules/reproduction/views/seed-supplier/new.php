<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\reproduction\models\SeedSupplier;

/**
 * @var SeedSupplier $model
 */

$this->title = 'Добавление поставщика';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Заполните форму для создания</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['seed-supplier/create'])]); ?>

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?= $form->field($model, 'name')->textInput([
                                    'autofocus' => true,
                                    'class'     => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('seedSupplierEdit')) : ?>
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-sm btn-primary']) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>