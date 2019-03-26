<?php

namespace backend\controllers;

use Yii;
use backend\modules\scheme\models\AppropriationScheme;
use backend\modules\scheme\models\Scheme;
use common\helpers\DataHelper;
use common\models\Animal;
use common\models\Cow;
use common\models\Color;
use common\models\search\CowSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class AnimalController
 * @package backend\controllers
 */
class AnimalController extends BackendController
{
    /**
     * Какой-то коефициент нормы, нужно дать название правильное
     */
    const NORM_VALUE_KOEF = 0.9;

    /**
     * @return string
     */
    public function actionIndex()
    {
//        ExcelHelper::import('1.xlsx');

        /** @var CowSearch $searchModel */
        /*$searchModel = new CowSearch([
            "scenario" => Cow::SCENARIO_FILTER
        ]);*/

        /** @var ActiveDataProvider $dataProvider */
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /** @var ActiveDataProvider $provider */
        $dataProvider = new ActiveDataProvider([
            'query'      => Animal::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            "searchModel"  => $searchModel,
            "dataProvider" => $dataProvider,
        ]);

        /*$query = Calf::find()->innerJoinWith(['suit', 'calfGroup']);

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $calfs = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $this->viewDataCalfs($calfs);

        return $this->render('index', [
            'calfs' => $calfs,
            'pagination' => $pagination,
        ]);*/
    }

    /**
     * Добавление нового животного
     */
    public function actionCreate()
    {
        /** @var Animal $model */
        $model = new Animal([
            'scenario' => Animal::SCENARIO_CREATE_EDIT
        ]);

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app/animal', 'ANIMAL_CREATE_SUCCESS'));
            return $this->redirect(["animal/index"]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/animal', 'ANIMAL_CREATE_ERROR'));
            return $this->render('new',
                compact("model")
            );
        }
    }

    /**
     * Страничка добавления нового животного
     *
     * @return string
     */
    public function actionNew()
    {
        $model = new Animal([
            'scenario' => Animal::SCENARIO_CREATE_EDIT
        ]);

        return $this->render('new',
            compact("model")
        );
    }

    /**
     * Страничка редактирования животного
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $model = Animal::findOne($id);

        return $this->render('edit',
            compact("model")
        );
    }

    /**
     * Обновление животного
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        /** @var Animal $model */
        $model = Animal::findOne($id);

        $model->setScenario(Animal::SCENARIO_CREATE_EDIT);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', Yii::t('app/animal', 'ANIMAL_EDIT_SUCCESS'));
            return $this->redirect(["animal/index"]);
        } else {
            \Yii::$app->session->setFlash('error', Yii::t('app/animal', 'ANIMAL_EDIT_ERROR'));
            return $this->render('edit',
                compact('model')
            );
        }
    }

    /**
     * Удаление животного
     *
     * @return string|\yii\web\Response
     */
    public function actionDelete($id)
    {
        /** @var Animal $model */
        $model = Animal::findOne($id);
        $model->delete();
        \Yii::$app->session->setFlash('success', Yii::t('app/animal', 'ANIMAL_DELETE_SUCCESS'));

        return $this->redirect(['animal/index']);
    }

    /**
     * Детальная карточка животного
     *
     * @param $id
     *
     * @return string
     */
    public function actionDetail($id)
    {
        /** @var Animal $model */
        $model = Animal::findOne($id);

        $schemeList = Scheme::getAllList();

        $animalOnScheme = $model->onScheme();

        $appropriationScheme = new AppropriationScheme([
            'animal_id' => $id,
            'status'    => AppropriationScheme::STATUS_IN_PROGRESS,
        ]);

        return $this->render('new-detail',
            compact('model', 'schemeList', 'appropriationScheme', 'animalOnScheme')
        );
    }

    /**
     * Поставить животное на схему
     *
     * @return \yii\web\Response
     */
    public function actionAppropriationScheme()
    {
        /** @var AppropriationScheme $model */
        $model = new AppropriationScheme();

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();
            $model->createActionHistory();

            Yii::$app->session->setFlash('success', 'Успешное назначение схемы на животного');

            return $this->redirect(["detail", "id" => $model->animal_id]);
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при назначении животного на схему');
            return $this->redirect(["detail", "id" => $model->animal_id]);
        }
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionRemoveFromScheme($id)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $appropriation = AppropriationScheme::findOne($id);
        try {
            $appropriation->removeFromScheme();
            $transaction->commit();

            \Yii::$app->session->setFlash('success', 'Животное было удалено со схемы лечения');
            return $this->redirect(["detail", "id" => $appropriation->animal_id]);
        } catch (\Exception $exception) {
            $transaction->rollBack();
            return $this->redirect(["detail", "id" => $appropriation->animal_id]);
        }

    }


    private function viewDataCalfs(&$calfs = null)
    {
        if (empty($calfs)) {
            return;
        }

        foreach ($calfs as &$calf) {
            $this->viewListDataCalf($calf);
        }
    }

    /**
     * @param null $id
     *
     * @return string
     */
    /*public function actionDetail($number = null)
    {
        $calf = Cow::find()
            ->where(['number' => $number])
            ->innerJoinWith(['suit', 'calfGroup'])
            ->one();

        $calfSuspension = Suspension::find()
            ->where(['calf' => $number])
            ->asArray()
            ->all();

        $this->viewDataDetailCalf($calf);
        $this->viewCalfSuspension($calfSuspension);

        $map = ArrayHelper::map($calfSuspension, 'date', 'weight');
        $dates = array_keys($map);
        $weights = array_values($map);
        $norm = $this->calculcateNorm($dates, $weights, ArrayHelper::getValue($calf, "birthWeight"));

        return $this->render('detail', [
            "calf"        => $calf,
            "suspensions" => $calfSuspension,
            "dates"       => $dates,
            "weights"     => $weights,
            "norm"        => $norm
        ]);
    }*/

    /**
     * Расчет нормы роста теленка
     *
     * @param array $dates
     * @param array $weights
     * @param int $birthWeight
     *
     * @return array
     */
    private function calculcateNorm($dates = [], $weights = [], $birthWeight = 0)
    {
        if (empty($dates) || empty($weights)) {
            return [];
        }

        $norm = [];

        for ($index = 0; $index < (count($dates)); $index++) {
            if ($index == 0) {
                $norm[] = self::NORM_VALUE_KOEF + $birthWeight;
                continue;
            }

            $birthDate = DataHelper::getTimeStamp($dates[0]);
            $curDate = DataHelper::getTimeStamp($dates[$index]);
            $countDays = DataHelper::getInterval($curDate, $birthDate);

            $norm[] = $countDays * self::NORM_VALUE_KOEF + $birthWeight;
        }

        return $norm;
    }

    private function viewDataDetailCalf(&$calf = null)
    {
        if (empty($calf)) {
            return;
        }

        $gender = ArrayHelper::getValue($calf, "gender");
        $calf["gender"] = empty($gender) ? "Тёлочка" : "Бычок";

        $calf["birthday"] = DataHelper::getDate(ArrayHelper::getValue($calf, "birthday"), "d.m.Y");

        $calf["previousWeighing"] = DataHelper::concatArrayIsNotEmptyElement([
            DataHelper::getDate(ArrayHelper::getValue($calf, "previousWeighingDate"), "d.m.Y"),
            ArrayHelper::getValue($calf, "previousWeighing")
        ], " / ");

        $calf["currentWeighing"] = DataHelper::concatArrayIsNotEmptyElement([
            DataHelper::getDate(ArrayHelper::getValue($calf, "currentWeighingDate"), "d.m.Y"),
            ArrayHelper::getValue($calf, "currentWeighing")
        ], " / ");
    }

    /**
     * @param null $suspensions
     */
    private function viewCalfSuspension(&$suspensions = null)
    {
        if (empty($suspensions)) {
            return;
        }

        foreach ($suspensions as &$suspension) {
            $suspension["date"] = DataHelper::getDate(ArrayHelper::getValue($suspension, "date"));
        }
    }

    /**
     * Преобразование данных для вывода
     *
     * @param null $calf
     */
    private function viewListDataCalf(&$calf = null)
    {
        if (empty($calf)) {
            return;
        }

        $gender = ArrayHelper::getValue($calf, "gender");
        $calf["gender_short"] = empty($gender) ? "Т" : "Б";

        $calf["birthday"] = DataHelper::getDate(ArrayHelper::getValue($calf, "birthday"), "d.m.Y");

        $calf["previousWeighing"] = DataHelper::concatArrayIsNotEmptyElement([
            DataHelper::getDate(ArrayHelper::getValue($calf, "previousWeighingDate"), "d.m.Y"),
            ArrayHelper::getValue($calf, "previousWeighing")
        ], " / ");

        $calf["currentWeighing"] = DataHelper::concatArrayIsNotEmptyElement([
            DataHelper::getDate(ArrayHelper::getValue($calf, "currentWeighingDate"), "d.m.Y"),
            ArrayHelper::getValue($calf, "currentWeighing")
        ], " / ");
    }

    /**
     * @param null $action
     * @param null $id
     *
     * @return string|Yii\web\Response
     * @throws \Exception
     */
    public function actionActions($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/calfs"]);
        } else {
            if ($action == "new") {
                $model = new Cow();
                $url = Url::toRoute(['/calf/save/']);
            } else {
                if ($action == "edit") {
                    $model = Cow::find()->where(['id' => $id])->one();
                    $model["birthday"] = DataHelper::getDate($model["birthday"], "Y-m-d");
                    $url = Url::toRoute(['/calf/update/' . $id . '/']);
                } else {
                    if ($action == "delete") {
                        $model = Cow::find()->where(['id' => $id])->one();
                        $model->delete();
                        return $this->redirect(['/calfs']);
                    }
                }
            }
        }

        $groups = Groups::find()->select([
            'name',
            'employeeId',
            'id'
        ])->indexBy("id")->orderBy(['id' => SORT_ASC])->column();
        $colors = Color::find()->select(['name', 'id'])->indexBy("id")->orderBy(['id' => SORT_ASC])->column();
        $mothers = [];
        $fathers = [];

        return $this->render('animal-add', [
            "action"  => $action,
            "url"     => $url,
            "model"   => $model,
            "groups"  => $groups,
            "colors"  => $colors,
            "mothers" => $mothers,
            "fathers" => $fathers,
        ]);
    }

    /**
     * @param null $action
     * @param null $id
     *
     * @return string|Yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSaveUpdate($action = null, $id = null)
    {
        if (empty($action)) {
            return $this->redirect(["/calfs"]);
        } else {
            if ($action == "save") {
                $model = new Cow();
            } else {
                if ($action == "update") {
                    $model = Cow::find()->where(['id' => $id])->one();
                } else {
                    throw new NotFoundHttpException("Такого действия нет");
                }
            }
        }

        $isLoading = $model->load(Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->save();

            Yii::$app->session->setFlash('success', Yii::t('app/back', 'CALF_' . strtoupper($action) . '_SUCCESS'));
            return $this->redirect(['/calfs']);
        } else {
            return $this->render('calf-add', [
                'model' => $model,
            ]);
        }
    }
}