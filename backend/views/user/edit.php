<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \common\models\User;
use \common\models\Position;
use yii\jui\DatePicker;

/** @var User $model */

$this->title = Yii::t('app/user', 'USER_EDIT');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Какие данные хотите изменить?</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['user/update', 'id' => $model->id])]); ?>

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'username')->textInput([
                                    'autofocus' => true,
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'email')->input(
                                    'email',
                                    ['class' => 'form-control form-control-sm']
                                ) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'lastName')->textInput([
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
                                    'language' => 'ru',
                                    'dateFormat' => 'dd.MM.yyyy',
                                    'options' => ['class' => 'form-control form-control-sm', 'autocomplete' => 'off']
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'firstName')->textInput([
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'gender')->dropDownList(User::getGenderList(), [
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'middleName')->textInput([
                                    'class' => 'form-control form-control-sm'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'position_id')->dropDownList(Position::getAllPositions(), [
                                    'class' => 'form-control form-control-sm',
                                    'prompt' => Yii::t('app/position', 'POSITION_CHOOSE'),
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('userEdit')) : ?>
                        <?= Html::submitButton(
                            Yii::t('app/user', 'EDIT'),
                            ['class' => 'btn btn-sm btn-primary', 'name' => 'contact-button']
                        ) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>