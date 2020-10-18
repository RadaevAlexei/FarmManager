<?php

use \backend\modules\scheme\models\SchemeDay;
use \backend\modules\scheme\models\GroupsAction;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \backend\modules\scheme\models\Scheme;

/**
 * @var Scheme $scheme
 * @var SchemeDay $day
 * @var GroupsAction[] $groupsAction
 * @var GroupsAction[] $groupsActionList
 */

$groupsAction = $day->groupsAction;
?>

<div class="row day_block" style="margin-left: auto">
    <div class="col-sm-12">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Что нужно выполнить в этот день</h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <?= Html::a('<i class="fas fa-trash"></i>',
                                            Url::to(['remove-day', 'scheme_id' => $scheme->id, 'scheme_day_id' => $day->id]),
                                            [
                                                "class" => "nav-link bg-red",
                                                "data" => ["confirm" => "Вы действительно хотите удалить этот день?"],
                                            ]
                                        );
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table table-sm table-striped table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Группа действий</th>
                                    <th>Список действий</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($groupsAction)) :
                                    foreach ($groupsAction as $index => $group) :
                                        echo $this->render('new-groups-action', [
                                            'index' => $index,
                                            'day' => $day,
                                            'model' => $group,
                                            'scheme' => $scheme,
                                        ]); ?>
                                    <?php endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button add-groups-action
                                                data-day-id="<?= $day->id ?>"
                                                data-add-group-action-url="<?= Url::to(['add-new-groups-action']) ?>"
                                                type="button"
                                                class="btn btn-sm btn-info btn-flat pull-left"
                                                disabled="true">Добавить группу действий
                                        </button>
                                    </div>
                                    <?= Html::dropDownList('groups-action-list', null, $groupsActionList, [
                                        'class' => 'form-control form-control-sm',
                                        'prompt' => 'Выберите группу',
                                        'disabled' => $scheme->approve || !Yii::$app->user->can('schemeManageEdit') ? true : false
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>