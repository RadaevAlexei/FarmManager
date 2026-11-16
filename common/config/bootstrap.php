<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

// На площадке hail812/yii2-adminlte3 1.0.5: виджеты лежат в hail812\adminlte3.
// В коде после обновления пакета они вызываются как hail812\adminlte.
foreach (['Menu', 'FlashAlert'] as $adminLteWidget) {
    $currentWidget = 'hail812\\adminlte\\widgets\\' . $adminLteWidget;
    $legacyWidget = 'hail812\\adminlte3\\widgets\\' . $adminLteWidget;

    if (!class_exists($currentWidget, true) && class_exists($legacyWidget, true)) {
        class_alias($legacyWidget, $currentWidget);
    }
}
