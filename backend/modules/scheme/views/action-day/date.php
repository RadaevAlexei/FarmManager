<?php

use \yii\jui\DatePicker;

/**
 * @var string $label
 * @var DateTime $date
 * @var string $name
 * @var array $options
 */

?>


<label><?= $label ?></label>
<div class="input-group date">
    <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
    </div>
    <?= DatePicker::widget([
        'name'       => $name,
        'value'      => $date,
        'language'   => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options'    => $options
    ]) ?>
</div>

