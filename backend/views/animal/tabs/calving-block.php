<?php

use \yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use common\models\Calving;

/**
 * @var Calving $model
 */

?>


<div class="row">
    <h4>Общие данные по отёлу</h4>

    <div class="col-sm-6">
        <div class="form-group">
            <? /*= $form->field($model, 'number')->input(
                            'number',
                            [
                                'class' => 'form-control',
                                'min' => 1,
                            ]
                        )*/ ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
        </div>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <?php /*if (Yii::$app->user->can('schemeManageEdit')) : */?><!--
            <div class="form-group">
                <?/*= Html::a(
                    Yii::t('app/groups-action', 'GROUPS_ACTION_ADD'),
                    Url::toRoute(['groups-action/new']),
                    [
                        'class' => 'btn btn-primary'
                    ]
                ) */?>
            </div>
        --><?php /*endif;*/ ?>

        <?php /*echo GridView::widget([
            "dataProvider" => $dataProvider,
            'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '',],
            'tableOptions' => ['class' => 'table table-sm table-striped table-hover table-condensed'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app/groups-action', 'ACTIONS'),
                    'template' => '<div class="btn-group">{update} {delete} </div>',
                    'visibleButtons' => [],
                    'buttons' => [],
                ],
            ]
        ]);*/ ?>
    </div>
</div>
