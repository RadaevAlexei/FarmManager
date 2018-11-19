<?php

use yii\helpers\ArrayHelper;
use \backend\assets\ChartAsset;
use \yii\helpers\Url;
use \yii\helpers\Html;
use common\models\Animal;

//ChartAsset::register($this);

/** @var Animal $model */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/animal', "ANIMAL_LIST"),
    'url'   => Url::toRoute(['/animal/index'])
];

$this->params['breadcrumbs'][] = ['label' => Yii::t('app/animal', "ANIMAL_DETAIL")];
?>

<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle"
                     src="<?= Yii::getAlias('@web') . '/images/1.png' ?>" alt="User profile picture">

                <h3 class="profile-username text-center"><?= ArrayHelper::getValue($model, "nickname") ?></h3>

                <p class="text-muted text-center"><?= ArrayHelper::getValue($model, "birthday") ?></p>

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
                        <b>Физ состояние</b><span class="pull-right label label-danger"><?=Animal::getPhysicalState($model->physical_state)?></span>
                    </li>
                    <li class="list-group-item">
                        <b>Рект. иссл-е</b><span class="pull-right label label-primary"><?=Animal::getPhysicalState($model->rectal_examination)?></span>
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

            </div>
        </div>
    </div>
</div>