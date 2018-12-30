<?php

use \yii\helpers\Url;
use \common\models\Animal;
use \backend\modules\scheme\models\Scheme;

/**
 * @var Animal $animal
 * @var Scheme[] $schemeList
 */
?>

<form class="form-horizontal" action="<?= Url::to(['appropriation-scheme']) ?>" data-animal="<?=$animal->id?>">
    <div class="form-group">
        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

        <div class="col-sm-10">
            <input type="email" class="form-control" id="inputEmail" placeholder="Email">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </div>
</form>
