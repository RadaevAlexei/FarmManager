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
<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">
            <i class="far fa-calendar-alt"></i>
        </span>
    </div>
    <?= DatePicker::widget([
        'name' => $name,
        'value' => $date,
        'language' => 'ru',
        'dateFormat' => 'dd.MM.yyyy',
        'options' => $options
    ]) ?>
</div>

