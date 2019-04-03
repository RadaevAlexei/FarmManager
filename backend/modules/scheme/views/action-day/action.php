<?php

use \backend\modules\scheme\models\ActionHistory;
use \yii\helpers\ArrayHelper;
use \common\models\TypeField;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\scheme\assets\ActionDayAsset;
use \yii\bootstrap\ActiveForm;
use \yii\jui\DatePicker;
use \common\models\TypeList;

/**
 * @var ActionHistory $actionHistory
 * @var bool $overdue
 */

ActionDayAsset::register($this);
$type = ArrayHelper::getValue($actionHistory, "action.type");

$form = ActiveForm::begin([
    'action' => Url::toRoute(['action-day/execute', 'id' => $actionHistory->id, 'overdue' => $overdue]),
    'id'     => 'execute-action-form',
    'class'  => 'form-horizontal'
]);

if ($overdue) {
    $date = $actionHistory->scheme_day_at;
} else {
    $date = (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s');
}

if ($type === TypeField::TYPE_TEXT) { ?>
    <div class="form-group">
        <div class="col-sm-6" style="margin-top: 20px">
            <label><?= ArrayHelper::getValue($actionHistory, "action.name") ?></label>
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-warning execute-action">Применить</button>
                </div>
                <?= Html::textInput('ExecuteForm[value]', '', [
                    'autofocus' => true,
                    'class'     => 'form-control',
                ]) ?>
                <?= Html::hiddenInput('ExecuteForm[type]', TypeField::TYPE_TEXT) ?>
            </div>
        </div>
        <div class="col-sm-6" style="margin-top: 20px">
            <?= $this->render('date', [
                'label' => 'Дата выполнения:',
                'name'  => 'ExecuteForm[execute_at]',
                'date'  => $date,
            ]) ?>
        </div>
    </div>
<?php } else {
    if ($type === TypeField::TYPE_NUMBER) { ?>
        <div class="form-group">
            <div class="col-sm-6" style="margin-top: 20px">
                <label><?= ArrayHelper::getValue($actionHistory, "action.name") ?></label>
                <div class="input-group">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-warning execute-action">Применить</button>
                    </div>
                    <?= Html::input('number', 'ExecuteForm[value]', null, [
                        'autofocus' => true,
                        'class'     => 'form-control',
                    ]) ?>
                    <?= Html::hiddenInput('ExecuteForm[type]', TypeField::TYPE_NUMBER) ?>
                </div>
            </div>
            <div class="col-sm-6" style="margin-top: 20px">
                <?= $this->render('date', [
                    'label' => 'Дата выполнения:',
                    'name'  => 'ExecuteForm[execute_at]',
                    'date'  => $date,
                ]) ?>
            </div>
        </div>
    <?php } else {
        if ($type === TypeField::TYPE_LIST) {
            $listType = ArrayHelper::getValue($actionHistory, "action.actionList.type");
            $items = ArrayHelper::getValue($actionHistory, "action.actionList.items");
            $list = [];
            if ($items) {
                $list = ArrayHelper::map($items, "id", "name");
            } ?>
            <div class="form-group">
                <div class="col-sm-6" style="margin-top: 20px">
                    <label><?= ArrayHelper::getValue($actionHistory, "action.name") ?></label>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-warning execute-action">Применить</button>
                        </div>
                        <?= Html::dropDownList('ExecuteForm[value]',
                            [],
                            $list,
                            [
                                'class'    => 'form-control',
                                'multiple' => ($listType === TypeList::MULTIPLE) ? true : false,
                            ]
                        ) ?>
                        <?= Html::hiddenInput('ExecuteForm[type]', TypeField::TYPE_LIST) ?>
                    </div>
                </div>
                <div class="col-sm-6" style="margin-top: 20px">
                    <?= $this->render('date', [
                        'label' => 'Дата выполнения:',
                        'name'  => 'ExecuteForm[execute_at]',
                        'date'  => $date,
                    ]) ?>
                </div>
            </div>
        <?php }
    }
} ?>

<?php ActiveForm::end(); ?>