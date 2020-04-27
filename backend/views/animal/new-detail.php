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
 * @var ArrayDataProvider $dataProviderRectal
 * @var array $addRectal
 */

AnimalAsset::register($this);

$this->title = 'Детальная карточка животного';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/animal', "ANIMAL_LIST"),
    'url'   => Url::toRoute(['/animal/index'])
];

$this->params['breadcrumbs'][] = ['label' => $this->title];

$isFremartinText = $model->fremartin ? "(фримартин)" : ""

?>

<div class="row">
    <div class="col-md-3">

        <div class="box box-warning">
            <div class="box-body box-profile" style="padding: 10px 0 10px 0px">
                <div class="col-md-12 box-primary no-padding no-margin"
                     style="background-color: #efdb14; margin: -10px 0 10px 0 !important">
                    <h1 class="text-center" style="margin: 10px 0 10px 0px;"><?= ArrayHelper::getValue($model,
                            "label") ?></h1>
                </div>
            </div>
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle"
                     src="<?= Yii::getAlias('@web') . '/images/1.png' ?>" alt="User profile picture">
                <h3 class="profile-username text-center"><?= ArrayHelper::getValue($model, "nickname") ?></h3>
            </div>

            <div class="box-body box-profile" style="padding: 10px 0 10px 0px">
                <div class="col-md-12 box-primary no-padding no-margin"
                     style="background-color: #efdb14; margin: -10px 0 10px 0 !important">
                    <h3 class="profile-username text-center"><?= (new DateTime((string)ArrayHelper::getValue($model,
                            "birthday")))->format('d.m.Y') ?></h3>
                </div>
            </div>

            <div class="box-body box-profile">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Происхождение</b> <a class="pull-right"><?= ArrayHelper::getValue($model, "farm.name") ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Коровник</b> <a class="pull-right"><?= ArrayHelper::getValue($model, "cowshed.name") ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Возраст</b> <a class="pull-right">1 год 2 месяца 6 дней</a>
                    </li>
                    <li class="list-group-item">
                        <b>Физ состояние</b><span
                                class="pull-right label label-danger"><?= Animal::getPhysicalState($model->physical_state) . $isFremartinText ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>Рект. иссл-е</b><span
                                class="pull-right label label-primary"><?= Animal::getPhysicalState($model->rectal_examination) ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>Состояние здоровья</b>
                        <span class="pull-right label label-<?= ($model->health_status == Animal::HEALTH_STATUS_HEALTHY ? "success" : "danger") ?>"><?= $model->getHealthStatus() ?></span>
                    </li>
                    <?php if ($model->health_status == Animal::HEALTH_STATUS_SICK) : ?>
                        <li class="list-group-item">
                            <b>Диагноз</b>
                            <span class="pull-right label label-danger">
                                <?= ArrayHelper::getValue($model, "diagnoses.name") ?>
                            </span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <!--                <li class="active"><a href="#info" data-toggle="tab" aria-expanded="true">Основная информация</a></li>-->
                <!--                <li class=""><a href="#calvings" data-toggle="tab" aria-expanded="true">Отёлы</a></li>-->
                <li class="active">
                    <a href="#scheme" data-toggle="tab" aria-expanded="true">Схема лечения</a>
                </li>
                <li class="">
                    <a href="#animal-history" data-toggle="tab" aria-expanded="true">Амбулаторная карта
                        животного</a>
                </li>
                <li class="">
                    <a href="#inseminations" data-toggle="tab" aria-expanded="true">Осеменения</a>
                </li>

                <?php if ($model->isWoman()) : ?>
                    <li class="">
                        <a href="#calvings" data-toggle="tab" aria-expanded="true">Отёлы</a>
                    </li>
                <?php endif; ?>

                <?php if ($model->isWoman()) : ?>
                    <li class="">
                        <a href="#rectalings" data-toggle="tab" aria-expanded="true">Ректальные исследования</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="tab-content">

                <!--<div class="tab-pane" id="info">
                    <? /*= $this->render('/animal/tabs/info', [
                        "model" => $model
                    ]) */ ?>
                </div>-->

                <!--<div class="tab-pane" id="calvings">
                    <? /*= $this->render('/animal/tabs/calvings', [

                    ]) */ ?>
                </div>-->

                <div class="active tab-pane" id="scheme">
                    <?= $this->render('/animal/tabs/scheme', [
                        'animal'              => $model,
                        'schemeList'          => $schemeList,
                        'appropriationScheme' => $appropriationScheme,
                        'dataProvider'        => $dataProvider,
                    ]) ?>
                </div>

                <div class="tab-pane" id="animal-history">
                    <?= $this->render('/animal/tabs/animal-history', [
                        'history' => $history
                    ]) ?>
                </div>

                <div class="tab-pane" id="inseminations">
                    <?= $this->render('/animal/tabs/inseminations', [
                        'animal'             => $model,
                        'dataProvider'       => $inseminationDataProvider,
                        'usersList'          => $usersList,
                        'seedBullList'       => $seedBullList,
                        'containerDuaraList' => $containerDuaraList,
                    ]) ?>
                </div>

                <?php if ($model->isWoman()) : ?>
                    <div class="tab-pane" id="calvings">
                        <?= $this->render('/animal/tabs/calvings', [
                            'animal'               => $model,
                            'dataProviderCalvings' => $dataProviderCalvings,
                        ]) ?>
                    </div>
                <?php endif; ?>

                <?php if ($model->isWoman()) : ?>
                    <div class="tab-pane" id="rectalings">
                        <?= $this->render('/animal/tabs/rectalings', [
                            'animal'             => $model,
                            'usersList'          => $usersList,
                            'rectalResults'      => $rectalResults,
                            'dataProviderRectal' => $dataProviderRectal,
                            'addRectal'          => $addRectal,
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
