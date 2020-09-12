<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Color;

/** @var Color $model */

$this->title = Yii::t('app/color', 'COLOR_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Какие данные хотите изменить?</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['color/update', 'id' => $model->id])]); ?>

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'name')->textInput([
                                    'autofocus' => true,
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'short_name')->textInput([
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('animalColorEdit')) : ?>
                        <?= Html::submitButton(
                            Yii::t('app/color', 'EDIT'),
                            ['class' => 'btn btn-sm btn-primary']
                        ) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>