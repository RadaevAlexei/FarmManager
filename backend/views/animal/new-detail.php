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
        <div class="col-md-3">

            <div class="card card-primary">
                <div class="card-header">
                    <h1 class="card-title">БИРКА: <?= ArrayHelper::getValue($model, "label") ?></h1>
                </div>
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="<?= Yii::getAlias('@web') . '/images/1.png' ?>"
                        >
                    </div>

                    <h3 class="profile-username text-center"><?= ArrayHelper::getValue($model, "nickname") ?></h3>

                    <p class="text-muted text-center">
                        ДАТА РОЖДЕНИЯ:
                        <?= (new DateTime((string)ArrayHelper::getValue($model, "birthday")))
                            ->format('d.m.Y')
                        ?>
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Происхождение</b> <a
                                    class="float-right"><?= ArrayHelper::getValue($model, "farm.name") ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Коровник</b> <a
                                    class="float-right"><?= ArrayHelper::getValue($model, "cowshed.name") ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Возраст</b> <a class="float-right"><?= $model->getAge() ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Физ состояние</b>
                            <span class="float-right badge badge-danger">
                                <?= Animal::getPhysicalState($model->physical_state) . $isFremartinText ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Рект. иссл-е</b>
                            <span class="float-right badge badge-primary">
                                <?= Animal::getPhysicalState($model->rectal_examination) ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Состояние здоровья</b>
                            <span class="float-right badge badge-<?= ($model->health_status == Animal::HEALTH_STATUS_HEALTHY ? "success" : "danger") ?>">
                                <?= $model->getHealthStatus() ?>
                            </span>
                        </li>

                        <?php if ($model->health_status == Animal::HEALTH_STATUS_SICK) : ?>
                            <li class="list-group-item">
                                <b>Диагноз</b>
                                <span class="float-right badge badge-danger">
                                    <?= ArrayHelper::getValue($model, "diagnoses.name") ?>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#scheme" data-toggle="tab">Схема лечения</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#animal-history" data-toggle="tab">Амбулаторная карта
                                животного</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#inseminations" data-toggle="tab">Осеменения</a>
                        </li>

                        <?php if ($model->isWoman()) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#rectalings" data-toggle="tab">Ректальные исследования</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#calvings" data-toggle="tab">Отёлы</a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane active" id="scheme">
                            <?= $this->render('/animal/tabs/scheme', [
                                'animal' => $model,
                                'schemeList' => $schemeList,
                                'appropriationScheme' => $appropriationScheme,
                                'dataProvider' => $dataProvider,
                            ]) ?>
                        </div>

                        <div class="tab-pane" id="animal-history">
                            <?= $this->render('/animal/tabs/animal-history', [
                                'history' => $history
                            ]) ?>
                        </div>

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
                                <?= $this->render('/animal/tabs/calvings', [
                                    'animal' => $model,
                                    'dataProviderCalvings' => $dataProviderCalvings,
                                    'countSterileDays' => $countSterileDays,
                                ]) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
