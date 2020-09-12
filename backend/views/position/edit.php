<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\Position;

/** @var Position $model */

$this->title = Yii::t('app/position', 'POSITION_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Какие данные хотите изменить?</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['position/update', 'id' => $model->id])]); ?>

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
                    <?php if (Yii::$app->user->can('userPositionEdit')) : ?>
                        <?= Html::submitButton(
                            Yii::t('app/position', 'EDIT'),
                            ['class' => 'btn btn-sm btn-primary', 'name' => 'contact-button']
                        ) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>