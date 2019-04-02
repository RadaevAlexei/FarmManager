<?php

use \backend\modules\scheme\models\ActionHistory;
use \yii\helpers\ArrayHelper;
use \common\models\TypeField;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\scheme\assets\ActionDayAsset;
use \yii\bootstrap\ActiveForm;

/**
 * @var ActionHistory $actionHistory
 */

ActionDayAsset::register($this);
$type = ArrayHelper::getValue($actionHistory, "action.type");

$form = ActiveForm::begin([
    'action' => Url::toRoute(['action-day/execute', 'id' => $actionHistory->id]),
    'id'     => 'execute-action-form',
    'class'  => 'form-horizontal'
]);

if ($type === TypeField::TYPE_TEXT) { ?>
    <div class="form-group">
        <div class="col-sm-12" style="margin-top: 20px">
            <label>Дни: </label>
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
    </div>
<?php } else {
    if ($type === TypeField::TYPE_NUMBER) { ?>
        <div class="form-group">
            <div class="col-sm-12" style="margin-top: 20px">
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
        </div>
    <?php } else {
        if ($type == TypeField::TYPE_LIST) { ?>
            <div class="form-group">
                <div class="col-sm-12" style="margin-top: 20px">
                    <label>Дни: </label>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button data-scheme-id=""
                                    data-add-day-url="<?= Url::to(['add-new-day', 'scheme_id' => 1]) ?>"
                                    type="button"
                                    class="btn btn-danger execute-action">Применить
                            </button>
                        </div>
                        <?= Html::input('number', 'day', null, [
                            'class' => 'form-control',
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php }
    }
} ?>

<?php ActiveForm::end(); ?>