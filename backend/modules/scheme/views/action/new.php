<?php

use \yii\bootstrap4\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\modules\scheme\models\Action;
use \backend\modules\scheme\models\ActionList;
use \backend\modules\scheme\assets\ActionAsset;
use common\models\TypeField;
use backend\modules\pharmacy\models\Preparation;

/**
 * @var Action $model
 * @var array $typeFieldList
 * @var ActionList[] $actionList
 * @var integer $typeList
 * @var integer $typeNumber
 * @var Preparation[] $preparationList
 */

ActionAsset::register($this);

$this->title = Yii::t('app/action', 'ACTION_NEW');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Заполните форму для создания</h3>
                </div>

                <?php $form = ActiveForm::begin(['action' => Url::toRoute(['action/create'])]); ?>

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
                            <div class="form-group <?= ($model->type == TypeField::TYPE_NUMBER ? "" : "hidden") ?>"
                                 id="preparationListBlock">
                                <div class="col-sm-12">
                                    <?= $form->field($model, 'preparation_id')->dropDownList(
                                        $preparationList,
                                        [
                                            'class' => 'form-control form-control-sm',
                                            'prompt' => 'Какой препарат привязать к этому действию?'
                                        ])
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= $form->field($model, 'type')->dropDownList(
                                    $typeFieldList,
                                    [
                                        'id' => 'selectTypeField',
                                        'class' => 'form-control form-control-sm',
                                        'prompt' => 'Выберите тип поля',
                                        'data' => [
                                            'type-list' => $typeList,
                                            'type-number' => $typeNumber,
                                        ]
                                    ])
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="actionListBlock" class="form-group" style="display:none;">
                                <div class="col-sm-12">
                                    <?= $form->field($model, 'action_list_id')->dropDownList(
                                        $actionList,
                                        [
                                            'id' => 'selectList',
                                            'class' => 'form-control form-control-sm',
                                            'prompt' => 'Выберите список'
                                        ])
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <?php if (Yii::$app->user->can('schemeManageEdit')) : ?>
                        <?= Html::submitButton(
                            Yii::t('app/action', 'ADD'),
                            ['class' => 'btn btn-sm btn-primary',]
                        ) ?>
                    <?php endif; ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>