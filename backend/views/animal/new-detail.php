<?php

use yii\helpers\ArrayHelper;
use \yii\helpers\Url;
use common\models\Animal;
use \backend\modules\scheme\models\Scheme;
use \backend\modules\scheme\models\AppropriationScheme;
use \backend\modules\scheme\models\AnimalHistory;
use \backend\assets\AnimalAsset;
use yii\data\ArrayDataProvider;

//ChartAsset::register($this);

/**
 * @var Animal $model
 * @var Scheme[] $schemeList
 * @var AppropriationScheme $appropriationScheme
 * @var AnimalHistory[] $history
 * @var ArrayDataProvider $dataProvider
 * @var ArrayDataProvider $inseminationDataProvider
 * @var array $usersList
 * @var array $rectalResults
 * @var array $seedBullList
 * @var array $containerDuaraList
 * @var ArrayDataProvider $dataProviderCalvings
 * @var ArrayDataProvider $dataProviderDistributedCalvings
 * @var ArrayDataProvider $dataProviderRectal
 * @var array $addRectal
 * @var integer $countSterileDays
 */

AnimalAsset::register($this);

$this->title = 'Детальная карточка животного';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/animal', "ANIMAL_LIST"),
    'url' => Url::toRoute(['/animal/index'])
];

$this->params['breadcrumbs'][] = ['label' => $this->title];

$isFremartinText = $model->fremartin ? "(фримартин)" : ""

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-widget widget-user-2">
                        <div class="widget-user-header bg-warning">
                            <div class="widget-user-image">
                                <img class="img-circle elevation-2"
                                     src="<?= Yii::getAlias('@web') . '/images/cow1.png' ?>"
                                     alt="User Avatar">
                            </div>
                            <h3 class="widget-user-username">
                                <?= ArrayHelper::getValue($model, "nickname") ?>
                                <span class="badge bg-info">
                            <?= ArrayHelper::getValue($model, "label") ?>
                        </span>
                            </h3>
                            <h5 class="widget-user-desc">
                                Физиологическое состояние:
                                <span class="badge badge-danger">
                            <?= Animal::getPhysicalState($model->physical_state) . $isFremartinText ?>
                        </span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-widget widget-user-2">
                        <div class="card-body p-0">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        Происхождение
                                        <span class="float-right badge bg-primary">
                                    <?= ArrayHelper::getValue($model, "farm.name") ?>
                                </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        Коровник
                                        <span class="float-right badge bg-info">
                                    <?= ArrayHelper::getValue($model, "cowshed.name") ?>
                                </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        Возраст
                                        <span class="float-right badge bg-success">
                                    <?= $model->getAge() ?>
                                </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-widget widget-user-2">
                        <div class="card-body p-0">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        Ректальное исследование
                                        <span class="float-right badge bg-danger">
                                    <?= Animal::getPhysicalState($model->rectal_examination) ?>
                                </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        Состояние здоровья
                                        <span class="float-right badge bg-<?= ($model->health_status == Animal::HEALTH_STATUS_HEALTHY ? "success" : "danger") ?>">
                                    <?= $model->getHealthStatus() ?>
                                </span>
                                    </a>
                                </li>

                                <?php if ($model->health_status == Animal::HEALTH_STATUS_SICK) : ?>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            Диагноз
                                            <span class="float-right badge bg-danger">
                                    <?= ArrayHelper::getValue($model, "diagnoses.name") ?>
                                </span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#animal-history">
                                Амбулаторная карта животного
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#animal-scheme">Схема лечения</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#animal-calvings">Отёлы</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="animal-history">
                            <?= $this->render('/animal/tabs/animal-history', [
                                'history' => $history
                            ]) ?>
                        </div>
                        <div class="tab-pane" id="animal-scheme">
                            <?= $this->render('/animal/tabs/scheme', [
                                'animal' => $model,
                                'schemeList' => $schemeList,
                                'appropriationScheme' => $appropriationScheme,
                                'dataProvider' => $dataProvider,
                            ]) ?>
                        </div>
                        <div class="tab-pane" id="animal-calvings">
                            <div class="tab-pane" id="calvings">
                                <?= $this->render('/animal/tabs/new-calvings', [
                                    'animal' => $model,
                                    'dataProviderCalvings' => $dataProviderCalvings,
                                    'dataProviderDistributedCalvings' => $dataProviderDistributedCalvings,
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-success collapsed-card">
                <div class="card-header" data-card-widget="collapse">
                    <h3 class="card-title">Отёлы</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                                        <li class="pt-2 px-3">
                                            <h3 class="card-title">Отёлы</h3>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill"
                                               href="#custom-tabs-two-home" role="tab"
                                               aria-controls="custom-tabs-two-home"
                                               aria-selected="true">1-й отёл</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill"
                                               href="#custom-tabs-two-profile" role="tab"
                                               aria-controls="custom-tabs-two-profile"
                                               aria-selected="false">2-й отёл</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-two-tabContent">
                                        <div class="card">
                                            <div class="card-header p-2">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#inseminations" data-toggle="tab">Осеменения</a>
                                                    </li>

                                                    <?php if ($model->isWoman()) : ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="#rectalings" data-toggle="tab">Ректальные
                                                                исследования</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="#calvings"
                                                               data-toggle="tab">Отёлы</a>
                                                        </li>
                                                    <?php endif; ?>

                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content">

                                                    <div class="tab-pane" id="inseminations">
                                                        <?= $this->render('/animal/tabs/inseminations', [
                                                            'animal' => $model,
                                                            'dataProvider' => $inseminationDataProvider,
                                                            'usersList' => $usersList,
                                                            'seedBullList' => $seedBullList,
                                                            'containerDuaraList' => $containerDuaraList,
                                                            'addRectal' => $addRectal,
                                                        ]) ?>
                                                    </div>

                                                    <?php if ($model->isWoman()) : ?>
                                                        <div class="tab-pane" id="rectalings">
                                                            <?= $this->render('/animal/tabs/rectalings', [
                                                                'animal' => $model,
                                                                'usersList' => $usersList,
                                                                'rectalResults' => $rectalResults,
                                                                'dataProviderRectal' => $dataProviderRectal,
                                                                'addRectal' => $addRectal,
                                                            ]) ?>
                                                        </div>

                                                        <div class="tab-pane" id="calvings">
                                                            <?/*= $this->render('/animal/tabs/calvings', [
                                                                'animal' => $model,
                                                                'dataProviderCalvings' => $dataProviderCalvings,
                                                                'countSterileDays' => $countSterileDays,
                                                            ])*/ ?>
                                                        </div>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
