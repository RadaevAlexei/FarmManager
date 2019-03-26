<?php

use \yii\helpers\Url;
use \common\models\Animal;
use \backend\modules\scheme\models\Scheme;
use \yii\bootstrap\ActiveForm;
use \backend\modules\scheme\models\AppropriationScheme;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use \yii\helpers\ArrayHelper;
use \backend\modules\scheme\models\ActionHistory;

/**
 * @var Animal $animal
 * @var Scheme[] $schemeList
 * @var AppropriationScheme $appropriationScheme
 * @var AppropriationScheme $animalOnScheme
 * @var ActionHistory[] $actionsToday
 */

if ($animalOnScheme) {
    echo Html::tag('span', 'Животное находится на схеме ' .
        Html::tag('span', ArrayHelper::getValue($animalOnScheme, 'scheme.name'), [
            'class' => 'label label-danger'
        ])
    );

    echo Html::tag('p',
        Html::a(
            'Снять со схемы?',
            Url::toRoute(['animal/remove-from-scheme', 'id' => $animalOnScheme->id]),
            [
                'data' => [
                    'confirm' => 'Вы действительно хотите убрать животное со схемы?'
                ]
            ]
        )
    );

    echo $this->render('/animal/tabs/actions-today', [
        'actionsToday' => $actionsToday
    ]);

} else {
    $form = ActiveForm::begin([
        'action' => Url::toRoute(['animal/appropriation-scheme']),
        'id'     => 'animal-form',
        'layout' => 'horizontal',
        'method' => 'post',
        'class'  => 'form-horizontal'
    ]); ?>

    <div class="form-group">
        <div class="col-sm-10">
            <?= $form->field($appropriationScheme, 'animal_id')->textInput(['class' => 'hidden'])->label(false); ?>
            <?= $form->field($appropriationScheme, 'status')->textInput(['class' => 'hidden'])->label(false); ?>

            <?= $form->field($appropriationScheme, 'started_at')->widget(DatePicker::class, [
                'language'   => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'class'      => 'form-control'
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10">
            <?= $form->field($appropriationScheme, 'scheme_id')->dropDownList(
                $schemeList,
                [
                    'class'  => 'form-control',
                    'prompt' => 'Выберите схему'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-danger">Поставить на схему</button>
        </div>
    </div>
    <?php ActiveForm::end();

} ?>