<?php

use \backend\modules\scheme\models\SchemeDay;
use \backend\modules\scheme\models\GroupsAction;
use \yii\helpers\Html;
use \yii\helpers\Url;

/**
 * @var SchemeDay $day
 * @var GroupsAction[] $groupsAction
 * @var GroupsAction[] $groupsActionList
 */

$groupsAction = $day->groupsAction;
?>

<div class="row day_block" style="margin-left: auto">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Что нужно выполнить в этот день</h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
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
                                'day'   => $day,
                                'model' => $group,
                            ]); ?>
                        <?php endforeach;
                    endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="box-footer clearfix">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button add-groups-action
                                data-day-id="<?= $day->id ?>"
                                data-add-group-action-url="<?= Url::to(['add-new-groups-action']) ?>"
                                type="button"
                                class="btn btn-sm btn-info btn-flat pull-left"
                                disabled="true">Добавить группу действий
                        </button>
                    </div>
                    <?= Html::dropDownList('groups-action-list', null, $groupsActionList, [
                        'class'  => 'form-control',
                        'prompt' => 'Выберите группу',
                    ]) ?>
                </div>
            </div>

        </div>
    </div>
</div>