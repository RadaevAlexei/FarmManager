<?php

use \backend\modules\scheme\models\ActionHistory;
use \yii\helpers\ArrayHelper;
use \common\models\TypeField;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\scheme\assets\ActionDayAsset;
use \yii\bootstrap4\ActiveForm;
use \kartik\select2\Select2;
use \common\models\TypeList;
use \backend\modules\pharmacy\models\Stock;
use \common\models\Measure;

/**
 * @var ActionHistory $actionHistory
 * @var bool $overdue
 * @var bool $disable
 */

ActionDayAsset::register($this);
$type = ArrayHelper::getValue($actionHistory, "action.type");
$preparation = ArrayHelper::getValue($actionHistory, "action.preparation");

$form = ActiveForm::begin([
    'action' => Url::toRoute(['action-day/execute', 'id' => $actionHistory->id, 'overdue' => $overdue]),
    'id' => 'execute-action-form',
    'class' => 'form-horizontal',
]);

if ($overdue) {
    $date = $actionHistory->scheme_day_at;
} else {
    $date = (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s');
}

$disabled = $disable ? true : false;
$disabled = ($disabled || !Yii::$app->user->can('schemeActionDayEdit')) ? true : false;

if ($type === TypeField::TYPE_TEXT) : ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?= ArrayHelper::getValue($actionHistory, "action.name") ?></label>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <?= Html::button('Применить', [
                            'class' => 'btn btn-sm btn-warning execute-action',
                            'disabled' => $disabled,
                        ]) ?>
                    </div>
                    <?= Html::textInput('ExecuteForm[value]', '', [
                        'autofocus' => true,
                        'class' => 'form-control form-control-sm',
                        'disabled' => $disabled,
                    ]) ?>
                    <?= Html::hiddenInput('ExecuteForm[type]', TypeField::TYPE_TEXT, ['disabled' => $disabled]) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <?= $this->render('date', [
                    'label' => 'Дата выполнения:',
                    'name' => 'ExecuteForm[execute_at]',
                    'date' => $date,
                    'options' => [
                        'class' => 'form-control form-control-sm',
                        'disabled' => $disabled,
                    ],
                ]) ?>
            </div>
        </div>
    </div>
<?php else :
    if ($type === TypeField::TYPE_NUMBER) :
        if (!empty($preparation)) : ?>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label><?= ArrayHelper::getValue($actionHistory, "action.name") ?></label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <?= Html::button('Применить', [
                                    'class' => 'btn btn-sm btn-warning execute-action',
                                    'disabled' => $disabled,
                                ]) ?>
                            </div>
                            <?= Html::input('number', 'ExecuteForm[value]', null, [
                                'autofocus' => true,
                                'class' => 'form-control form-control-sm',
                                'disabled' => $disabled,
                            ]) ?>
                            <?= Html::hiddenInput('ExecuteForm[type]', TypeField::TYPE_NUMBER, ['disabled' => $disabled]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Препарат</label>
                        <?= Html::hiddenInput('ExecuteForm[preparation_id]',
                            ArrayHelper::getValue($actionHistory, "action.preparation.id")) ?>
                        <?= Html::textInput('ExecuteForm[preparation_name]',
                            ArrayHelper::getValue($actionHistory, "action.preparation.name"), [
                                'class' => 'form-control form-control-sm',
                                'disabled' => true,
                            ]) ?>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Склад</label>
                        <?= Html::dropDownList('ExecuteForm[stock_id]',
                            [],
                            ArrayHelper::map(Stock::getAllList(), "id", "name"),
                            [
                                'class' => 'form-control form-control-sm',
                                'prompt' => 'Из какого склада списать?',
                            ]
                        ) ?>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Объём</label>
                        <?= Html::hiddenInput('ExecuteForm[preparation_volume]',
                            ArrayHelper::getValue($actionHistory, "action.preparation.volume")) ?>
                        <?= Html::textInput('ExecuteForm[volume]',
                            ArrayHelper::getValue($actionHistory, "action.preparation.volume"), [
                                'class' => 'form-control form-control-sm',
                                'disabled' => true,
                            ]
                        ) ?>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Единица измерения</label>
                        <?= Html::textInput('ExecuteForm[measure]',
                            Measure::getName(ArrayHelper::getValue($actionHistory, "action.preparation.measure")), [
                                'class' => 'form-control form-control-sm',
                                'disabled' => true,
                            ]
                        ) ?>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <?= $this->render('date', [
                            'label' => 'Дата выполнения:',
                            'name' => 'ExecuteForm[execute_at]',
                            'date' => $date,
                            'options' => [
                                'class' => 'form-control form-control-sm',
                                'disabled' => $disabled,
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><?= ArrayHelper::getValue($actionHistory, "action.name") ?></label>
                        <div class="input-group input-group">
                            <div class="input-group-prepend">
                                <?= Html::button('Применить', [
                                    'class' => 'btn btn-sm btn-warning execute-action',
                                    'disabled' => $disabled,
                                ]) ?>
                            </div>
                            <?= Html::input('number', 'ExecuteForm[value]', null, [
                                'autofocus' => true,
                                'class' => 'form-control form-control-sm',
                                'disabled' => $disabled,
                            ]) ?>
                            <?= Html::hiddenInput('ExecuteForm[type]', TypeField::TYPE_NUMBER, ['disabled' => $disabled]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= $this->render('date', [
                            'label' => 'Дата выполнения:',
                            'name' => 'ExecuteForm[execute_at]',
                            'date' => $date,
                            'options' => [
                                'class' => 'form-control form-control-sm',
                                'disabled' => $disabled,
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif;
    else:
        if ($type === TypeField::TYPE_LIST) :
            $listType = ArrayHelper::getValue($actionHistory, "action.actionList.type");
            $items = ArrayHelper::getValue($actionHistory, "action.actionList.items");
            $list = [];
            if ($items) :
                $list = ArrayHelper::map($items, "id", "name");
            endif; ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><?= ArrayHelper::getValue($actionHistory, "action.name") ?></label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <?= Html::button('Применить', [
                                    'class' => 'btn btn-sm btn-warning execute-action',
                                    'disabled' => $disabled,
                                ]) ?>
                            </div>
                            <?= Select2::widget([
                                'name' => 'ExecuteForm[value]',
                                'data' => $list,
                                'size' => Select2::MEDIUM,
                                'options' => [
                                    'placeholder' => 'Выберите значения из списка',
                                    'class' => 'form-control form-control-sm',
                                    'disabled' => $disabled,
                                    'multiple' => ($listType === TypeList::MULTIPLE) ? true : false
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                            <?= Html::hiddenInput('ExecuteForm[type]', TypeField::TYPE_LIST,
                                ['disabled' => $disabled]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= $this->render('date', [
                            'label' => 'Дата выполнения:',
                            'name' => 'ExecuteForm[execute_at]',
                            'date' => $date,
                            'options' => [
                                'class' => 'form-control form-control-sm',
                                'disabled' => $disabled,
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php
        endif;
    endif;
endif; ?>

<?php ActiveForm::end(); ?>
