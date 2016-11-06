<?php

use \yii\helpers\Html;
use \frontend\assets\SuspensionAsset;

SuspensionAsset::register($this);

?>

<div class="form-group clearfix" id="suspensionBlock">
    <div class="col-sm-2">
        <select class="form-control" name="groups" id="groupsId">
            <option value="">Выберите группу</option>
            <?php if (!empty($groups)) : ?>
                <?php foreach ($groups as $group) : ?>
                    <option value="<?=$group->id?>"><?=$group->name?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <div class="date-form" id="dateSuspensionBlock" hidden>
            <div class="form-horizontal">
                <div class="control-group">
                    <label for="date-picker-2" class="control-label">Дата перевески</label>
                    <div class="controls">
                        <div id="sandbox-container">
                            <div class="input-group date">
                                <input type="text" id="suspensionDatepicker" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

</div>