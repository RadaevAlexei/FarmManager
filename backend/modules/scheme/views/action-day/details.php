<?php

use \yii\bootstrap\ActiveForm;
use \backend\modules\scheme\models\ActionHistory;

/**
 * @var ActionHistory $details
 * @var bool $overdue
 * @var bool $disable
 */

//SchemeAsset::register($this);
$this->title = 'Животные на схеме';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-info">

    <div class="box-body">

        <div class="form-group">
            <div class="col-sm-12">
            </div>
        </div>

        <div class="form-group" id="scheme_days_block">
            <div class="col-sm-12">
                <?= $this->render('actions-today', [
                    'details' => $details,
                    'overdue' => $overdue,
                    'disable' => $disable,
                ]) ?>
            </div>
        </div>
    </div>

</div>