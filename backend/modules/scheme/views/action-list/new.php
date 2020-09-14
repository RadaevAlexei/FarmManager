<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\scheme\models\ActionList;
use \backend\modules\scheme\assets\ActionListAsset;
use \yii\helpers\ArrayHelper;

/**
 * @var ActionList $model
 * @var array $typeList
 * @var array $listItems
 */

ActionListAsset::register($this);

$this->title = Yii::t('app/action-list', 'ACTION_LIST_NEW');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Заполните форму для создания</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['action-list/create'])]); ?>

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
                                <?= $form->field($model, 'type')->dropDownList(
                                    $typeList,
                                    ['class' => 'form-control form-control-sm', 'prompt' => 'Выберите тип списка'])
                                ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('schemeManageEdit')) : ?>
                        <?= Html::submitButton(
                            Yii::t('app/action-list', 'ADD'),
                            ['class' => 'btn btn-sm btn-primary',]
                        ) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>