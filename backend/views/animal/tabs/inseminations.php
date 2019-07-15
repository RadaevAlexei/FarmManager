<?php

use \yii\helpers\Url;
use \yii\bootstrap\ActiveForm;
use \yii\jui\DatePicker;
use \yii\helpers\Html;
use \backend\modules\reproduction\models\Insemination;
use common\models\Animal;
use \common\models\User;
use \yii\helpers\ArrayHelper;
use backend\modules\reproduction\models\SeedBull;
use \yii\grid\GridView;
use \yii\data\ArrayDataProvider;
use backend\modules\reproduction\models\ContainerDuara;

/**
 * @var Insemination $model
 * @var ArrayDataProvider $dataProvider
 * @var Animal $animal
 */

$model = new Insemination([
    'animal_id' => $animal
]);

$userList = ArrayHelper::map(User::getAllList(), "id", "username");
$seedBullList = ArrayHelper::map(SeedBull::getAllList(), "id", "nickname");
$containerDuaraList = ArrayHelper::map(ContainerDuara::getAllList(), "id", "name");

?>

<div class="box box-success">
    <div class="box-header with-border" style="background-color: #0ead0e78">
        <h3 class="box-title">История осеменений</h3>
    </div>

    <div class="box-body">
        <?php echo GridView::widget([
            'formatter'    => [
                'class'       => 'yii\i18n\Formatter',
                'nullDisplay' => '',
            ],
            "dataProvider" => $dataProvider,
            'summary'      => false,
            'tableOptions' => [
                'style' => 'display:block; width:100%; overflow-x:auto',
                'class' => 'table table-striped',
            ],
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'date',
                    'content'   => function (Insemination $model) {
                        return (new DateTime($model->date))->format('d.m.Y');
                    }
                ],
                [
                    'attribute' => 'user_id',
                    'content'   => function (Insemination $model) {
                        return ArrayHelper::getValue($model, "user.username");
                    }
                ],
                'count',
                [
                    'attribute' => 'type_insemination',
                    'content'   => function (Insemination $model) {
                        return $model->getTypeInsemination();
                    }
                ],
                'comment',
                [
                    'attribute' => 'seed_bull_id',
                    'content'   => function (Insemination $model) {
                        return Html::a(
                            ArrayHelper::getValue($model, "seedBull.nickname"),
                            Url::toRoute(['reproduction/seed-bull/edit/', 'id' => $model->seed_bull_id]),
                            ["target" => "_blank"]
                        );
                    }
                ],
            ]
        ]); ?>
    </div>
</div>

<div class="box box-success">
    <div class="box-header with-border" style="background-color: #0ead0e78">
        <h3 class="box-title">Добавить осеменение</h3>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Url::toRoute(['add-insemination']),
        'id'     => 'insemination-form',
        'method' => 'post',
        'class'  => 'form-horizontal'
    ]); ?>
    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($model, 'animal_id')->hiddenInput()->label(false); ?>

                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'language'   => 'ru',
                    'dateFormat' => 'dd.MM.yyyy',
                    'options'    => [
                        'class'        => 'form-control',
                        'autocomplete' => 'off'
                    ]
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($model, 'user_id')->dropDownList(
                    $userList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Кто проводил?'
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($model, 'seed_bull_id')->dropDownList(
                    $seedBullList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Выберите быка'
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($model, 'container_duara_id')->dropDownList(
                    $containerDuaraList,
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Выберите Сосуд Дьюара'
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($model, 'count')->input(
                    'number',
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Выберите быка',
                        'min'    => 1
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($model, 'type_insemination')->dropDownList(
                    Insemination::getTypesInsemination(),
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Выберите ип осеменения?'
                    ]
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
                <?= $form->field($model, 'comment')->textInput(
                    [
                        'class'  => 'form-control',
                        'prompt' => 'Примечание'
                    ]
                ) ?>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <?= Html::submitButton('Добавить осеменение', [
            'class' => 'btn btn-primary',
            'data'  => ['confirm' => 'Вы действительно хотите добавить осеменение?']
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>