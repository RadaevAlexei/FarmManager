<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\scheme\models\Diagnosis;
use backend\modules\scheme\models\Scheme;
use \backend\modules\scheme\models\SchemeDay;
use \backend\modules\scheme\models\GroupsAction;
use \backend\modules\scheme\assets\SchemeAsset;

/**
 * @var Scheme $model
 * @var Diagnosis[] $diagnosisList
 * @var SchemeDay[] $schemeDayList
 * @var GroupsAction[] $groupsActionList
 * @var bool $canApprove
 */

SchemeAsset::register($this);
$this->title = Yii::t('app/scheme', 'SCHEME_EDIT');
$this->params['breadcrumbs'][] = $this->title;

if ($model->approve) {
    $boxClass = "success";
    $boxHeaderBackgroundColor = "#65e065";
    $title = "Схема утверждена";
} else {
    $boxClass = "warning";
    $boxHeaderBackgroundColor = "#f3d75a";
    $title = "Схема не утверждена";
}

?>

<div class="box box-<?= $boxClass ?>">

    <div class="box-header with-border" style="background-color: <?= $boxHeaderBackgroundColor ?>">
        <h3 class="box-title"><?= $title ?></h3>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['scheme/update', 'id' => $model->id]),
        'id' => 'scheme-form',
        'class' => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'name')->textInput([
                    'autofocus' => true,
                    'class' => 'form-control',
                    'disabled' => $model->approve ? true : false
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <?= $form->field($model, 'diagnosis_id')->dropDownList(
                    $diagnosisList,
                    [
                        'class' => 'form-control',
                        'prompt' => 'Выберите диагноз',
                        'disabled' => $model->approve ? true : false
                    ])
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <label>Дни: </label>
                <div class="input-group">
                    <div class="input-group-btn">
                        <button data-scheme-id="<?= $model->id ?>"
                                data-add-day-url="<?= Url::to(['add-new-day', 'scheme_id' => $model->id]) ?>"
                                id="add-day"
                                type="button"
                                class="btn btn-danger"
                                disabled="true">Добавить
                        </button>
                    </div>
                    <?= Html::input('number', 'day', null, [
                        'id' => 'new-day',
                        'class' => 'form-control',
                        'disabled' => $model->approve || !Yii::$app->user->can('schemeManageEdit') ? true : false
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-group" id="scheme_days_block">
            <div class="col-sm-12">
                <?= $this->render('days', [
                    'model' => $model,
                    'groupsActionList' => $groupsActionList
                ]) ?>
            </div>
        </div>

        <div class="hidden">
            <?= $form->field($model, 'created_by')->hiddenInput(
                ['value' => $model->created_by, 'class' => 'hidden'])
            ?>
            <?= $form->field($model, 'created_at')->hiddenInput(
                ['value' => $model->created_at, 'class' => 'hidden'])
            ?>
        </div>

    </div>

    <div class="box-footer">
        <?php
        if ($canApprove) :
            echo Html::a('Утвердить', Url::to(['approve', 'id' => $model->id]),
                [
                    'class' => 'btn btn-success',
                    'name' => 'approve-button',
                    'data' => [
                        'confirm' => 'Вы действительно хотите утвердить схему?'
                    ]
                ]);

            echo Html::submitButton(Yii::t('app/scheme', 'EDIT'),
                ['class' => 'btn btn-info pull-right', 'name' => 'contact-button']);
        endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
