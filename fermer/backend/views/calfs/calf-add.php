<?php

use \yii\widgets\ActiveForm;
use \yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app/front', "CalfDetailInfo")];
$headerText = !empty($action) ? \Yii::t('app/back', 'CALF_' . strtoupper($action)) : "";

?>

<h1><?=$headerText?></h1>
<?php $form = ActiveForm::begin(['action' => $url, 'id' => 'calf-form']); ?>
    <table class="table table-striped table-hover table-condensed">
        <tbody>
            <tr>
                <td><?= $form->field($model, 'number')->textInput(['autofocus' => true]) ?></td>
            </tr>
            <tr>
                <td><?= $form->field($model, 'nickname')->textInput() ?></td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'groupId')->dropDownList(
                        $groups,
                        ['prompt'=>'Выберите группу']
                    )?>
                </td>
            </tr>
            <tr>
                <td><?= $form->field($model, 'birthday')->input("date") ?></td>
            </tr>
            <tr>
                <td><?= $form->field($model, 'birthWeight')->textInput() ?></td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'gender')->dropDownList([
                        "Т" => "Телёнок",
                        "Б" => "Бычок"
                    ], ['prompt' => 'Выберите пол'])?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'color')->dropDownList(
                        $colors,
                        ['prompt'=>'Выберите масть']
                    )?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'motherId')->dropDownList(
                        $mothers,
                        ['prompt'=>'Выберите мать']
                    )?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=$form->field($model, 'fatherId')->dropDownList(
                        $fathers,
                        ['prompt'=>'Выберите отца']
                    )?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="pull-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'calf-button']) ?>
    </div>
<?php ActiveForm::end(); ?>