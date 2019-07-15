<?php

namespace backend\modules\reproduction\controllers;

use backend\modules\reproduction\models\Insemination;
use backend\modules\reproduction\models\SeedBullStorage;
use backend\modules\reproduction\models\SeedCashBook;
use common\models\Animal;
use Yii;
use backend\modules\reproduction\models\search\SeedBullSearch;
use backend\modules\reproduction\models\SeedBull;
use common\models\Breed;
use common\models\Color;
use common\models\ContractorSeed;
use \backend\controllers\BackendController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class InseminationController
 * @package backend\modules\reproduction\controllers
 */
class InseminationController extends BackendController
{

}