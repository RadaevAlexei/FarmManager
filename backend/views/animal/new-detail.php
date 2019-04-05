<?php

use yii\helpers\ArrayHelper;
use \yii\helpers\Url;
use common\models\Animal;
use \backend\modules\scheme\models\Scheme;
use \backend\modules\scheme\models\AppropriationScheme;
use \backend\modules\scheme\models\AnimalHistory;
use \backend\assets\AnimalAsset;

//ChartAsset::register($this);

/**
 * @var Animal $model
 * @var Scheme[] $schemeList
 * @var AppropriationScheme $appropriationScheme
 * @var AppropriationScheme $animalOnScheme
 * @var AppropriationScheme $actionsToday
 * @var AnimalHistory[] $history
 */

AnimalAsset::register($this);

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/animal', "ANIMAL_LIST"),
    'url'   => Url::toRoute(['/animal/index'])
];

$this->params['breadcrumbs'][] = ['label' => Yii::t('app/animal', "ANIMAL_DETAIL")];
?>

<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-warning">
            <div class="box-body box-profile" style="padding: 10px 0 10px 0px">
                <div class="col-md-12 box-primary no-padding no-margin" style="background-color: #efdb14; margin: -10px 0 10px 0 !important">
                    <h3 class="profile-username text-center"><?= ArrayHelper::getValue($model, "label") ?></h3>
                </div>
            </div>
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle"
                     src="<?= Yii::getAlias('@web') . '/images/1.png' ?>" alt="User profile picture">
                <h3 class="profile-username text-center"><?= ArrayHelper::getValue($model, "nickname") ?></h3>
            </div>

            <div class="box-body box-profile" style="padding: 10px 0 10px 0px">
                <div class="col-md-12 box-primary no-padding no-margin" style="background-color: #efdb14; margin: -10px 0 10px 0 !important">
                    <h3 class="profile-username text-center"><?= (new DateTime((string)ArrayHelper::getValue($model, "birthday")))->format('d.m.Y') ?></h3>
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
                        <b>Бирка</b> <a class="pull-right"><?= ArrayHelper::getValue($model, "label") ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Возраст</b> <a class="pull-right">1 год 2 месяца 6 дней</a>
                    </li>
                    <li class="list-group-item">
                        <b>Физ состояние</b><span
                                class="pull-right label label-danger"><?= Animal::getPhysicalState($model->physical_state) ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>Рект. иссл-е</b><span
                                class="pull-right label label-primary"><?= Animal::getPhysicalState($model->rectal_examination) ?></span>
                    </li>
                    <li class="list-group-item">
                        <b>Состояние здоровья</b><span
                                class="pull-right label label-<?= ($model->health_status == Animal::HEALTH_STATUS_HEALTHY ? "success" : "danger") ?>"><?= $model->getHealthStatus() ?></span>
                    </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">About Me</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

                <p class="text-muted">
                    B.S. in Computer Science from the University of Tennessee at Knoxville
                </p>

                <hr>

                <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

                <p class="text-muted">Malibu, California</p>

                <hr>

                <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

                <p>
                    <span class="label label-danger">UI Design</span>
                    <span class="label label-success">Coding</span>
                    <span class="label label-info">Javascript</span>
                    <span class="label label-warning">PHP</span>
                    <span class="label label-primary">Node.js</span>
                </p>

                <hr>

                <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#info" data-toggle="tab" aria-expanded="true">Основная информация</a></li>
                <li class=""><a href="#calvings" data-toggle="tab" aria-expanded="true">Отёлы</a></li>
                <li class=""><a href="#inseminations" data-toggle="tab" aria-expanded="true">Осеменения</a></li>
                <li class=""><a href="#scheme" data-toggle="tab" aria-expanded="true">Схема лечения</a></li>
                <li class=""><a href="#animal-history" data-toggle="tab" aria-expanded="true">Амбулаторная карта
                        животного</a></li>
            </ul>
            <div class="tab-content">

                <div class="active tab-pane" id="info">
                    <?= $this->render('/animal/tabs/info', [
                        "model" => $model
                    ]) ?>
                </div>

                <div class="tab-pane" id="calvings">
                    <?= $this->render('/animal/tabs/calvings', [

                    ]) ?>
                </div>

                <div class="tab-pane" id="inseminations">
                    <?= $this->render('/animal/tabs/inseminations', [
                    ]) ?>
                </div>

                <div class="tab-pane" id="scheme">
                    <?= $this->render('/animal/tabs/scheme', [
                        'animal'              => $model,
                        'schemeList'          => $schemeList,
                        'appropriationScheme' => $appropriationScheme,
                        'animalOnScheme'      => $animalOnScheme,
                        'actionsToday'        => $actionsToday,
                    ]) ?>
                </div>

                <div class="tab-pane" id="animal-history">
                    <?= $this->render('/animal/tabs/animal-history', [
                        'history' => $history
                    ]) ?>
                </div>

            </div>
        </div>
    </div>
</div>
