<?php

use yii\data\ArrayDataProvider;

/**
 * @var ArrayDataProvider $dataProvider
 */

?>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-pills">
            <?php foreach ($dataProvider->getModels() as $index => $tab) : ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#animal-calving-<?= ($index + 1) ?>">
                        <?= ($index + 1) ?>-й отёл
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <?php foreach ($dataProvider->getModels() as $index => $tab) : ?>
                <div class="tab-pane" id="animal-calving-<?= ($index + 1) ?>">
                    <?= ($index + 1) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
