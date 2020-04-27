<?php

namespace backend\controllers;

use common\models\rectal\InseminationRectalLink;
use common\models\rectal\RectalSettings;
use DateInterval;
use Yii;
use backend\models\forms\AnimalDiagnosisForm;
use backend\models\forms\CloseSchemeForm;
use backend\models\forms\HealthForm;
use backend\models\forms\UploadForm;
use backend\models\search\AnimalSearch;
use backend\models\search\AnimalSickSearch;
use backend\models\search\AwaitingAnimalSearch;
use backend\modules\reproduction\models\ContainerDuara;
use backend\modules\reproduction\models\Insemination;
use backend\modules\reproduction\models\SeedBull;
use backend\modules\reproduction\models\SeedBullStorage;
use backend\modules\reproduction\models\SeedCashBook;
use backend\modules\scheme\models\AnimalHistory;
use backend\modules\scheme\models\Diagnosis;
use backend\modules\scheme\models\Scheme;
use common\helpers\Excel\ExcelHelper;
use common\models\Calving;
use common\models\rectal\Rectal;
use common\models\User;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Throwable;
use backend\modules\scheme\models\AppropriationScheme;
use common\helpers\DataHelper;
use common\models\Animal;
use common\models\Cow;
use common\models\Color;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

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

    const TEMPLATE_NAME = "template_animal_sick_list.xlsx";
    const TEMPLATE_FILE_NAME = "animal_sick_list";
    const DIRECTORY_REPORTS = "animal_sick_list";

    const READER_TYPE = "Xlsx";
    const WRITER_TYPE = "Xlsx";

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AnimalSearch([
            "scenario" => AnimalSearch::SCENARIO_FILTER
        ]);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        /** @var Animal $model */
        $model = Animal::findOne($id);

        $model->setScenario(Animal::SCENARIO_CREATE_EDIT);

        $isLoading = $model->load(\Yii::$app->request->post());

        if ($isLoading && $model->validate()) {
            $model->birthday = (new DateTime($model->birthday))->format('Y-m-d H:i:s');
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
     * @param $id
     *
     * @return Response
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
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
     * @return string
     * @throws \Exception
     */
    public function actionDetail($id)
    {
        /** @var Animal $model */
        $model = Animal::findOne($id);

        $schemeList = [];
        if ($model) {
            $schemeList = $model->getListSchemes();
            $schemeList = ArrayHelper::map($schemeList, "id", "name");
        }

        /** @var AppropriationScheme[] $animalOnSchemes */
        $animalOnSchemes = $model->onScheme();
        $dataProvider = new ArrayDataProvider(['allModels' => $animalOnSchemes]);

        $appropriationScheme = new AppropriationScheme([
            'animal_id' => $id,
            'status'    => AppropriationScheme::STATUS_IN_PROGRESS,
        ]);

        /** @var AnimalHistory[] $history */
        $history = $model->getHistory();

        $inseminations = $model->getInseminations();
        $inseminationDataProvider = new ArrayDataProvider(['allModels' => $inseminations]);

        $usersList = ArrayHelper::map(User::getAllList(), "id", "lastName");
        $seedBullList = ArrayHelper::map(SeedBull::getAllList(), "id", "nickname");
        $containerDuaraList = ArrayHelper::map(ContainerDuara::getAllList(), "id", "name");

        $calvings = ArrayHelper::map($model->calvings, 'label', function ($item) {
            return $item;
        }, 'calving_id');

        $dataProviderCalvings = new ArrayDataProvider(['allModels' => $calvings]);

        $rectalResults = [];
        $rectalHistory = [];
        if ($model->isWoman()) {
            $rectalResults = Rectal::getListResults();
            $rectalHistory = $model->rectalHistory();
        }
        $dataProviderRectal = new ArrayDataProvider(['allModels' => $rectalHistory]);
        $addRectal = $model->getAddRectalData();

        return $this->render('new-detail',
            compact(
                'model',
                'schemeList',
                'appropriationScheme',
                'history',
                'dataProvider',
                'inseminationDataProvider',
                'usersList',
                'seedBullList',
                'containerDuaraList',
                'dataProviderCalvings',
                'rectalResults',
                'dataProviderRectal',
                'addRectal'
            )
        );
    }

    /**
     * Добавление осеменения
     *
     * @return Response
     * @throws Exception
     */
    public function actionAddInsemination()
    {
        /** @var Insemination $model */
        $model = new Insemination();

        $isLoading = $model->load(Yii::$app->request->post());

        $transaction = Yii::$app->db->beginTransaction();

        try {

            if ($isLoading && $model->validate()) {
                $model->date = (new DateTime($model->date))->format('Y-m-d H:i:s');

                $animal = Animal::findOne($model->animal_id);

                // Если это первое осеменение после отёла
                if (!$animal->cur_insemination_id) {
                    if (!$model->save()) {
                        throw new Exception('Ошибка при добавлении осеменения');
                    }

                    $model->createFirst();
                } else {
                    if (!$model->save()) {
                        throw new Exception('Ошибка при добавлении осеменения');
                    }

                    $model->createNewInsemination();
                }

                // Смена статуса
                $animal->updateAttributes(['status' => Animal::STATUS_INSEMINATED]);

                // Вычесть из склада
                SeedBullStorage::substractSeedBull($model->seed_bull_id, $model->container_duara_id, $model->count);

                $seedBullPrice = ArrayHelper::getValue(SeedBull::findOne($model->seed_bull_id), "price");

                // Добавить в расход
                $seedCashBook = new SeedCashBook();
                $seedCashBook->user_id = $model->user_id;
                $seedCashBook->date = $model->date;
                $seedCashBook->type = SeedCashBook::TYPE_KREDIT;
                $seedCashBook->seed_bull_id = $model->seed_bull_id;
                $seedCashBook->container_duara_id = $model->container_duara_id;
                $seedCashBook->count = $model->count;
                $seedCashBook->total_price_with_vat = $seedCashBook->count * $seedBullPrice;
                $seedCashBook->total_price_without_vat = $seedCashBook->total_price_with_vat;
                $seedCashBook->vat_percent = 0;
                $seedCashBook->save();

                $userId = Yii::$app->getUser()->getId();
                $actionText = "Создал осеменение #$model->id";

                /** @var AnimalHistory $newAnimalHistory */
                $newAnimalHistory = new AnimalHistory([
                    'animal_id'   => $model->animal_id,
                    'user_id'     => $userId,
                    'date'        => (new DateTime($model->date))->format('Y-m-d H:i:s'),
                    'action_type' => AnimalHistory::ACTION_TYPE_CREATE_INSEMINATION,
                    'action_text' => $actionText
                ]);

                if (!$newAnimalHistory->save()) {
                    throw new Exception('Ошибка при добавлении осеменения');
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Успешное добавление осеменения');
                return $this->redirect(["detail", "id" => $model->animal_id]);
            } else {
                throw new Exception('Ошибка при добавлении осеменения');
            }
        } catch (\Exception $exception) {
            $transaction->rollBack();

            Yii::$app->session->setFlash('error', $exception->getMessage());
            return $this->redirect(["detail", "id" => $model->animal_id]);
        }

    }

    /**
     * Добавление отёла
     * @return Response
     * @throws Exception
     */
    public function actionAddCalving()
    {
        /** @var Calving $model */
        $model = new Calving();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $post = Yii::$app->request->post();
            $childAnimals = ArrayHelper::getValue($post, "Calving.child", []);

            if (empty($childAnimals)) {
                throw new Exception('Вы не добавили приплод!');
            }

            $isLoading = $model->load($post);
            $date = (new DateTime($model->date))->format('Y-m-d H:i:s');
            $model->date = $date;

            if ($isLoading && $model->validate()) {
                // Сохраняем данные по отелу
                if (!$model->save()) {
                    throw new Exception('При сохрании возникли ошибки');
                }

                $isFremartin = Calving::isFremartin($childAnimals);

                // Добавляем приплод в базу
                foreach ($childAnimals as $childAnimal) {
                    $sex = ArrayHelper::getValue($childAnimal, 'sex');
                    $dead = ArrayHelper::getValue($childAnimal, 'dead');
                    $weight = ArrayHelper::getValue($childAnimal, 'weight');

                    $childLabel = ($dead == $sex) ?
                        $childLabel = md5(microtime(true)) :
                        ArrayHelper::getValue($childAnimal, 'label');

                    if (!$dead && (empty($childLabel) || empty($weight))) {
                        throw new Exception('Ошибка! Бирка и вес не у мертворода должна быть заполнена');
                    }

                    $newAnimal = new Animal([
                        'label'          => $childLabel,
                        'birthday'       => $date,
                        'birth_weight'   => $weight,
                        'sex'            => $sex,
                        'mother_id'      => $model->animal_id,
                        'health_status'  => $dead ? Animal::HEALTH_STATUS_DEAD : Animal::HEALTH_STATUS_HEALTHY,
                        'physical_state' => $sex == Cow::ANIMAL_SEX_TYPE ?
                            Animal::PHYSICAL_STATE_CALF : Animal::PHYSICAL_STATE_BULL,
                        'fremartin'      => (($sex == Cow::ANIMAL_SEX_TYPE) && $isFremartin) ? 1 : 0
                    ]);
                    $newAnimal->setScenario(Animal::SCENARIO_CREATE_EDIT);

                    if ($newAnimal->save()) {
                        $model->link('animals', $newAnimal);
                    } else {
                        throw new Exception('При сохрании приплода возникли ошибки');
                    }
                }

                $parentAnimal = Animal::findOne($model->animal_id);
                $actionText = "Провёл отёл у #$parentAnimal->label";

                /** @var AnimalHistory $newAnimalHistory */
                $newAnimalHistory = new AnimalHistory([
                    'animal_id'   => $model->animal_id,
                    'user_id'     => $model->user_id,
                    'date'        => (new DateTime('now',
                        new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                    'action_type' => AnimalHistory::ACTION_TYPE_CREATE_CALVING,
                    'action_text' => $actionText
                ]);

                if (!$newAnimalHistory->save()) {
                    throw new Exception('Ошибка при записи действия в историю');
                }

            } else {
                throw new Exception('Ошибка правильности ввода данных');
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Успешное добавление отёла');
            return $this->redirect(["detail", "id" => $model->animal_id]);
        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $exception->getMessage());
            return $this->redirect(["detail", "id" => $model->animal_id]);
        }
    }

    /**
     * @param $id
     * @return Response
     * @throws Exception
     * @throws Throwable
     */
    public function actionRemoveCalving($id)
    {
        /** @var Calving $model */
        $model = Calving::findOne($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {

            if ($model) {
                $model->deleteCalving();

                $parentAnimal = Animal::findOne($model->animal_id);
                $actionText = "Удалил отёл у животного #$parentAnimal->label";

                /** @var AnimalHistory $newAnimalHistory */
                $newAnimalHistory = new AnimalHistory([
                    'animal_id'   => $model->animal_id,
                    'user_id'     => $model->user_id,
                    'date'        => (new DateTime('now',
                        new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                    'action_type' => AnimalHistory::ACTION_TYPE_REMOVE_CALVING,
                    'action_text' => $actionText
                ]);

                if (!$newAnimalHistory->save()) {
                    throw new Exception('Ошибка при записи действия в историю');
                }

                Yii::$app->session->setFlash('success', 'Успешное удаление отёла');
                $transaction->commit();
            } else {
                Yii::$app->session->setFlash('warning', 'Данное отёл найден');
            }

            return $this->redirect(['detail', 'id' => $model->animal_id]);
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении отёла');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * Удаление животного из отёла
     * @param $animalId
     * @param $calvingId
     * @return Response
     * @throws Exception
     * @throws Throwable
     */
    public function actionRemoveAnimalFromCalving($animalId, $calvingId)
    {
        /** @var Calving $model */
        $calving = Calving::findOne($calvingId);

        /** @var Animal $animal */
        $animal = Animal::findOne($animalId);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($calving && $animal) {
                $calving->deleteChildAnimal($animalId);
                Yii::$app->session->setFlash('success', 'Успешное удаление животного из отёла');
                $transaction->commit();
            } else {
                Yii::$app->session->setFlash('warning', 'Ошибка удаления.Отёл или животное отсутствует!');
            }

            return $this->redirect(['detail', 'id' => $calving->animal_id]);
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении животного из отёла');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $calving->animal_id]);
        }
    }

    /**
     * Поставить животное на схему
     * @return Response
     * @throws Exception
     */
    public function actionAppropriationScheme()
    {
        /** @var AppropriationScheme $model */
        $model = new AppropriationScheme();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $isLoading = $model->load(Yii::$app->request->post());
            if ($isLoading && $model->validate()) {

                $appropriationScheme = AppropriationScheme::find()
                    ->where([
                        'animal_id'   => $model->animal_id,
                        'scheme_id'   => $model->scheme_id,
                        'status'      => AppropriationScheme::STATUS_IN_PROGRESS,
                        'finished_at' => null
                    ])
                    ->one();

                if ($appropriationScheme) {
                    throw new Exception('Животное уже стоит на этой схеме лечения');
                }

                $model->save();
                $model->createActionHistory();
            }

            Yii::$app->session->setFlash('success', 'Успешное назначение схемы на животного');
            $transaction->commit();

            return $this->redirect(["detail", "id" => $model->animal_id]);
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
            $transaction->rollBack();

            return $this->redirect(["detail", "id" => $model->animal_id]);
        }
    }

    /**
     * @param $id
     * @return Response
     * @throws Exception
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
     * @return string|Response
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
     * @return string|Response
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

    /**
     * @return Response
     * @throws \yii\db\Exception
     */
    public function actionUpdateFromFile()
    {
        $model = new UploadForm();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (Yii::$app->request->isPost) {
                $model->file = UploadedFile::getInstance($model, 'file');

                if ($model->file && $model->validate()) {
                    ExcelHelper::updateFields($model->file->tempName);
                }

                Yii::$app->session->setFlash('success', 'Успешное обновление данных');
                $transaction->commit();
                return $this->redirect(['index']);
            }
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при обновлении данных');
            $transaction->rollBack();
            return $this->redirect(['index']);
        }

    }

    /**
     * @return Response
     * @throws Throwable
     * @throws \yii\db\Exception
     */
    public function actionUpdateHealth()
    {
        $model = new HealthForm();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $animal = Animal::findOne($model->animal_id);

                    if ($animal) {
                        $animal->updateAttributes([
                            'health_status'         => $model->health_status,
                            'health_status_comment' => $model->health_status_comment,
                            'date_health'           => (new DateTime($model->date_health))->format('Y-m-d H:i:s'),
                        ]);

                        $userId = Yii::$app->getUser()->getIdentity()->getId();

                        $health_status = ($model->health_status == Animal::HEALTH_STATUS_HEALTHY) ? "Здоровая" : "Больная";

                        /** @var AnimalHistory $newAnimalHistory */
                        $newAnimalHistory = new AnimalHistory([
                            'animal_id'   => $animal->id,
                            'user_id'     => $userId,
                            'date'        => (new DateTime('now',
                                new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                            'action_type' => AnimalHistory::ACTION_TYPE_SET_HEALTH_STATUS,
                            'action_text' => "Поставил статус \"$health_status\""
                        ]);

                        $newAnimalHistory->save();
                    }
                }

                Yii::$app->session->setFlash('success', 'Успешное смена состояния здоровья');
                $transaction->commit();
                return $this->redirect(['detail', 'id' => $model->animal_id]);
            }
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при смене состояния здоровья');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * @return string
     */
    public function actionCloseSchemeForm()
    {
        $response = Yii::$app->response;
        $post = Yii::$app->request->post();

        $animalId = ArrayHelper::getValue($post, "animal_id");
        $appropriationSchemeId = ArrayHelper::getValue($post, "appropriation_scheme_id");

        $animal = Animal::findOne($animalId);
        $appropriationScheme = AppropriationScheme::findOne($appropriationSchemeId);

        $response->format = Response::FORMAT_HTML;

        return $this->renderPartial('tabs/close-scheme', compact('animal', 'appropriationScheme'));
    }

    /**
     * Редактирование отёла
     * @param $calvingId
     * @return string
     */
    public function actionEditCalvingForm($calvingId)
    {
        $response = Yii::$app->response;

        $editModel = Calving::findOne($calvingId);

        $statusesList = Calving::getListStatuses();
        $positionsList = Calving::getListPositions();
        $usersList = User::getAllList();

        $response->format = Response::FORMAT_HTML;
        return $this->renderPartial('forms/edit-calving', compact(
            'editModel',
            'statusesList',
            'positionsList',
            'usersList'
        ));
    }

    /**
     * Редактирование отёла
     * @param $calvingId
     * @return Response
     * @throws Exception
     */
    public function actionEditCalving($calvingId)
    {
        /** @var Calving $model */
        $model = new Calving();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $updateModel = Calving::findOne($calvingId);
                    $model->setAttribute('date', (new DateTime($model->date))->format('Y-m-d H:i:s'));

                    $attributes = $model->getAttributes();
                    unset($attributes['id']);
                    $updateModel->updateAttributes($attributes);

                    $animal = Animal::findOne($model->animal_id);
                    $actionText = "Отредактировал отёл #$animal->label";

                    /** @var AnimalHistory $newAnimalHistory */
                    $newAnimalHistory = new AnimalHistory([
                        'animal_id'   => $model->animal_id,
                        'user_id'     => $model->user_id,
                        'date'        => (new DateTime('now',
                            new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                        'action_type' => AnimalHistory::ACTION_TYPE_EDIT_CALVING,
                        'action_text' => $actionText
                    ]);

                    if (!$newAnimalHistory->save()) {
                        throw new Exception('Ошибка при сохранении в амбулаторный журнал');
                    }

                    Yii::$app->session->setFlash('success', 'Успешное редактирование отёла');
                    $transaction->commit();
                }
                return $this->redirect(['detail', 'id' => $model->animal_id]);
            }
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при редактировании отёла');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * @param $inseminationId
     *
     * @return string
     */
    public function actionEditInseminationForm($inseminationId)
    {
        $response = Yii::$app->response;

        $editModel = Insemination::findOne($inseminationId);
        $userList = ArrayHelper::map(User::getAllList(), "id", "username");
        $seedBullList = ArrayHelper::map(SeedBull::getAllList(), "id", "nickname");
        $containerDuaraList = ArrayHelper::map(ContainerDuara::getAllList(), "id", "name");

        $response->format = Response::FORMAT_HTML;

        return $this->renderPartial('forms/edit-insemination', compact(
            'editModel',
            'userList',
            'seedBullList',
            'containerDuaraList'
        ));
    }

    /**
     * @param $inseminationId
     *
     * @return Response
     */
    public function actionEditInsemination($inseminationId)
    {
        /** @var Insemination $model */
        $model = new Insemination();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $updateModel = Insemination::findOne($inseminationId);
                    $model->setAttribute('date', (new DateTime($model->date))->format('Y-m-d H:i:s'));

                    $attributes = $model->getAttributes();
                    unset($attributes['id']);
                    $updateModel->updateAttributes($attributes);

                    $userId = Yii::$app->getUser()->getId();
                    $actionText = "Отредактировал осеменение #$updateModel->id";

                    /** @var AnimalHistory $newAnimalHistory */
                    $newAnimalHistory = new AnimalHistory([
                        'animal_id'   => $model->animal_id,
                        'user_id'     => $userId,
                        'date'        => (new DateTime($model->date))->format('Y-m-d H:i:s'),
                        'action_type' => AnimalHistory::ACTION_TYPE_EDIT_INSEMINATION,
                        'action_text' => $actionText
                    ]);

                    $newAnimalHistory->save();

                    Yii::$app->session->setFlash('success', 'Успешное редактирование осеменения');
                    $transaction->commit();
                }
                return $this->redirect(['detail', 'id' => $model->animal_id]);
            }
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при редактировании осеменения');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function actionDeleteInsemination($id)
    {
        $model = Insemination::findOne($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {

            if ($model) {
                $model->delete();

                $userId = Yii::$app->getUser()->getId();
                $actionText = "Удалил осеменение #$model->id";

                /** @var AnimalHistory $newAnimalHistory */
                $newAnimalHistory = new AnimalHistory([
                    'animal_id'   => $model->animal_id,
                    'user_id'     => $userId,
                    'date'        => (new DateTime($model->date))->format('Y-m-d H:i:s'),
                    'action_type' => AnimalHistory::ACTION_TYPE_DELETE_INSEMINATION,
                    'action_text' => $actionText
                ]);

                $newAnimalHistory->save();

                Yii::$app->session->setFlash('success', 'Успешное редактирование осеменения');
                $transaction->commit();
            } else {
                Yii::$app->session->setFlash('warning', 'Данное осеменение не найдено');
            }

            return $this->redirect(['detail', 'id' => $model->animal_id]);
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при редактировании осеменения');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * @return Response
     * @throws Throwable
     * @throws \yii\db\Exception
     */
    public function actionCloseScheme()
    {
        $model = new CloseSchemeForm();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                    /** @var Animal $animal */
                    $animal = Animal::findOne($model->animal_id);

                    if ($animal) {
                        $dateHealth = (new DateTime($model->date_health))->format('Y-m-d H:i:s');

                        AppropriationScheme::findOne($model->appropriation_scheme_id)
                            ->updateAttributes([
                                'status'      => $model->health_status,
                                'comment'     => $model->comment,
                                'finished_at' => $dateHealth
                            ]);

                        $updateParameters = [
                            'date_health' => $dateHealth,
                        ];

                        // Если мы выписываем её здоровой
                        if ($model->health_status == AppropriationScheme::RESULT_STATUS_HEALTHY) {
                            if (!$animal->onScheme()) {
                                $diagnosis = null;
                                $healthStatus = Animal::HEALTH_STATUS_HEALTHY;
                            } else {
                                $diagnosis = $animal->diagnosis;
                                $healthStatus = $animal->health_status;
                            }
                        } else {
                            $diagnosis = $animal->diagnosis;
                            $healthStatus = $model->health_status - 1;
                        }

                        $updateParameters = array_merge($updateParameters, [
                            'diagnosis'             => $diagnosis,
                            'health_status'         => $healthStatus,
                            'health_status_comment' => $model->comment,
                        ]);
                        $animal->updateAttributes($updateParameters);

                        $userId = Yii::$app->getUser()->getIdentity()->getId();
                        $health_status = $animal->getHealthStatus();

                        /** @var AnimalHistory $newAnimalHistory */
                        $newAnimalHistory = new AnimalHistory([
                            'animal_id'   => $animal->id,
                            'user_id'     => $userId,
                            'date'        => (new DateTime('now',
                                new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                            'action_type' => AnimalHistory::ACTION_TYPE_CLOSE_SCHEME,
                            'action_text' => "Выписал животное со статусом \"$health_status\""
                        ]);

                        $newAnimalHistory->save();
                    }
                }

                Yii::$app->session->setFlash('success', 'Успешное смена состояния здоровья');
                $transaction->commit();
                return $this->redirect(['detail', 'id' => $model->animal_id]);
            }
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при смене состояния здоровья');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * @return Response
     * @throws Throwable
     * @throws \yii\db\Exception
     */
    public function actionUpdateDiagnoses()
    {
        $model = new AnimalDiagnosisForm();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $animal = Animal::findOne($model->animal_id);

                    if ($animal) {
                        $animal->updateAttributes([
                            'health_status' => $model->health_status,
                            'diagnosis'     => $model->diagnosis,
                        ]);

                        $userId = Yii::$app->getUser()->getIdentity()->getId();

                        /** @var Diagnosis $diagnosis */
                        $diagnosis = Diagnosis::findOne($model->diagnosis);
                        $diagnosisName = "";
                        if ($diagnosis) {
                            $diagnosisName = ArrayHelper::getValue($diagnosis, "name");
                        }

                        /** @var AnimalHistory $newAnimalHistory */
                        $newAnimalHistory = new AnimalHistory([
                            'animal_id'   => $animal->id,
                            'user_id'     => $userId,
                            'date'        => (new DateTime('now',
                                new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                            'action_type' => AnimalHistory::ACTION_TYPE_SET_DIAGNOSIS,
                            'action_text' => "Поставил диагноз \"$diagnosisName\""
                        ]);

                        $newAnimalHistory->save();
                    }
                }

                Yii::$app->session->setFlash('success', 'Успешное смена состояния здоровья');
                $transaction->commit();
                return $this->redirect(['detail', 'id' => $model->animal_id]);
            }
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при смене состояния здоровья');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * @return string
     */
    public function actionSickIndex()
    {
        /** @var AnimalSickSearch $searchModel */
        $searchModel = new AnimalSickSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('sick/index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * Список животных в ожидании
     * @return string
     */
    public function actionAwaitingIndex()
    {
        /** @var AwaitingAnimalSearch $searchModel */
        $searchModel = new AwaitingAnimalSearch();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('awaiting/index',
            compact('searchModel', 'dataProvider')
        );
    }

    private function getPathTemplate()
    {
        return Yii::getAlias('@webroot') . '/templates/' . self::TEMPLATE_NAME;
    }

    private function getTimePrefix()
    {
        return (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('Y_m_d_H_i_s');
    }

    public function actionDownloadSickList()
    {
        /** @var BaseReader $reader */
        $templatePath = $this->getPathTemplate();

        $reader = new Xlsx();

        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = $reader->load($templatePath);

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue("G1", (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('d.m.Y'));

        /** @var Animal[] $animals */
        $animals = Animal::find()
            ->alias('a')
            ->with([
                'diagnoses'           => function (ActiveQuery $query) {
                    $query->alias('d');
                },
                'appropriationScheme' => function (ActiveQuery $query) {
                    $query->alias('as');
                    $query->joinWith([
                        'scheme' => function (ActiveQuery $query) {
                            $query->alias('s');
                            $query->where(['s.status' => Scheme::STATUS_ACTIVE]);
                            $query->joinWith([
                                'schemeDays' => function (ActiveQuery $query) {
                                    $query->alias('sd');
                                }
                            ]);
                        }
                    ]);
                    $query->andFilterWhere(['as.status' => AppropriationScheme::STATUS_IN_PROGRESS]);
                    $query->orderBy(['as.started_at' => SORT_ASC]);
                }
            ])
            ->where([
                'a.health_status' => Animal::HEALTH_STATUS_SICK
            ])->all();

        $count = count($animals);
        if ($count > 1) {
            $sheet->insertNewRowBefore(4, $count - 1);
        }

        $offset = 4;
        foreach ($animals as $index => $animal) {
            $sheet->setCellValue("A$offset", $index + 1);
            $sheet->setCellValue("B$offset", ArrayHelper::getValue($animal, "collar"));
            $sheet->setCellValue("C$offset", ArrayHelper::getValue($animal, "label"));
            $sheet->setCellValue("D$offset",
                (new DateTime(ArrayHelper::getValue($animal,
                    "date_health")))->format('d.m.Y'));
            $sheet->setCellValue("E$offset", ArrayHelper::getValue($animal, "diagnoses.name"));
            $sheet->setCellValue("F$offset",
                (new DateTime(ArrayHelper::getValue($animal,
                    "appropriationScheme.started_at")))->format('d.m.Y'));


            /** @var AppropriationScheme[] $appropriationSchemes */
            $appropriationSchemes = $animal->onScheme();

            $appropriationSchemesResult = '';
            foreach ($appropriationSchemes as $appropriationScheme) {
                $appropriationSchemesResult .= ArrayHelper::getValue($appropriationScheme, "scheme.name") . "\n";
            }

            $sheet->setCellValue("G$offset", $appropriationSchemesResult);
            $sheet->setCellValue("H$offset",
                count(ArrayHelper::getValue($animal, "appropriationScheme.scheme.schemeDays")));

            $offset++;
        }

        $end = $offset + 1;
        $spreadsheet->getActiveSheet()->getStyle("A4:H$end")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("A4:H$end")->getFont()->setBold(false)->setSize(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $sheet->setTitle('Список больных животных');

        $writer = IOFactory::createWriter($spreadsheet, self::WRITER_TYPE);
        $prefix = $this->getTimePrefix();
        $newFileName = self::DIRECTORY_REPORTS . "/" . self::TEMPLATE_FILE_NAME . '_' . $prefix . '.xlsx';
        $writer->save($newFileName);

        return Yii::$app->response->sendFile($newFileName);
    }

    /**
     * Проведение ректального исследования
     * @return Response
     * @throws Exception
     */
    public function actionAddRectal()
    {
        /** @var Rectal $model */
        $model = new Rectal();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $isLoading = $model->load(Yii::$app->request->post());
            $model->date = (new DateTime($model->date))->format('Y-m-d H:i:s');

            if ($isLoading && $model->validate()) {
                // Сохраняем данные по отелу
                if (!$model->save()) {
                    throw new Exception('При сохрании РИ возникли ошибки');
                }

                $animal = Animal::findOne($model->animal_id);
                $createDate = (new DateTime($model->date))->format('d.m.Y');
                $actionText = "Создал запись ректального исследования #$animal->label от ($createDate)";

                /** @var AnimalHistory $newAnimalHistory */
                $newAnimalHistory = new AnimalHistory([
                    'animal_id'   => $model->animal_id,
                    'user_id'     => Yii::$app->getUser()->getId(),
                    'date'        => (new DateTime('now',
                        new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                    'action_type' => AnimalHistory::ACTION_TYPE_CREATE_RECTAL,
                    'action_text' => $actionText
                ]);

                if (!$newAnimalHistory->save()) {
                    throw new Exception('Ошибка при сохранении в амбулаторный журнал');
                }

            } else {
                throw new Exception('Ошибка правильности ввода данных');
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Успешное проведение ректального исследования');
            return $this->redirect(["detail", "id" => $model->animal_id]);
        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $exception->getMessage());
            return $this->redirect(["detail", "id" => $model->animal_id]);
        }
    }

    /**
     * Обновление РИ
     * @param $id
     * @return Response
     * @throws Exception
     */
    public function actionUpdateRectal($id)
    {
        $model = Rectal::findOne($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $isLoading = $model->load(Yii::$app->request->post());
            $model->date = (new DateTime($model->date))->format('Y-m-d H:i:s');

            if ($isLoading && $model->validate()) {
                $model->save();

                $animal = Animal::findOne($model->animal_id);
                $curInsemination = Insemination::findOne(ArrayHelper::getValue($animal, "cur_insemination_id"));

                if ($model->result == Rectal::RESULT_NOT_STERILE) {
                    $curInsemination->changeStatus(Insemination::STATUS_NOT_SEMINAL);
                    $animal->resetCurInsemination();
                    $animal->updateRectalStatus(Animal::RECTAL_EXAMINATION_NOT_STERILE);
                } else if ($model->result == Rectal::RESULT_DUBIOUS) {
                    $animal->updateRectalStatus(Animal::RECTAL_EXAMINATION_DUBIOUS);
                } else if ($model->result == Rectal::RESULT_STERILE) {
                    if ($model->rectal_stage == Rectal::STAGE_CONFIRM_SECOND) {
                        $curInsemination->changeStatus(Insemination::STATUS_SEMINAL);
                        $animal->resetCurInsemination();
                        $animal->updateRectalStatus(Animal::RECTAL_EXAMINATION_NOT_STERILE);
                    } else {
                        $nextStage = $model->getNextStage();
                        $nextRectalDate = RectalSettings::calculateRectalDate($model->date, $nextStage);

                        $nextRectal = new Rectal([
                            'user_id'      => $model->user_id,
                            'date'         => $nextRectalDate,
                            'animal_id'    => $model->animal_id,
                            'result'       => Rectal::RESULT_NOT_RESULT,
                            'rectal_stage' => $nextStage,
                        ]);

                        $nextRectal->save();

                        $prevLink = InseminationRectalLink::findOne([
                            'animal_id'       => $model->animal_id,
                            'insemination_id' => $curInsemination->id,
                            'rectal_id'       => $model->id
                        ]);

                        $newInseminationRectalLink = new InseminationRectalLink([
                            'prev_id'         => ArrayHelper::getValue($prevLink, 'id'),
                            'animal_id'       => $model->animal_id,
                            'insemination_id' => $curInsemination->id,
                            'rectal_id'       => $nextRectal->id
                        ]);

                        $newInseminationRectalLink->save();
                        $animal->updateRectalStatus(Animal::RECTAL_EXAMINATION_STERILE);
                    }
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Успешное проведение ректального исследования');
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Возникла ошибка при проведении ректального исследования');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * Удаление РИ
     * @param $id
     * @return Response
     * @throws Exception
     * @throws Throwable
     */
    public function actionRemoveRectal($id)
    {
        /** @var Rectal $model */
        $model = Rectal::findOne($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {

            if ($model) {
                $model->delete();

                $animal = Animal::findOne($model->animal_id);
                $removeDate = (new DateTime($model->date))->format('d.m.Y');
                $actionText = "Удалил ректальное исследование #$animal->label от ($removeDate)";

                /** @var AnimalHistory $newAnimalHistory */
                $newAnimalHistory = new AnimalHistory([
                    'animal_id'   => $model->animal_id,
                    'user_id'     => Yii::$app->getUser()->getId(),
                    'date'        => (new DateTime('now',
                        new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                    'action_type' => AnimalHistory::ACTION_TYPE_REMOVE_RECTAL,
                    'action_text' => $actionText
                ]);

                if (!$newAnimalHistory->save()) {
                    throw new Exception('Ошибка при сохранении в амбулаторный журнал');
                }

                Yii::$app->session->setFlash('success', 'Успешное удаление ректального исследования');
                $transaction->commit();
            } else {
                Yii::$app->session->setFlash('warning', 'Данное ректальное исследование не найдено');
            }

            return $this->redirect(['detail', 'id' => $model->animal_id]);
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении ректального исследования');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }

    /**
     * Форма редактирования ректального исследования
     * @param $id
     * @return string
     */
    public function actionEditRectalForm($id)
    {
        $response = Yii::$app->response;

        $editModel = Rectal::findOne($id);
        $usersList = User::getAllList();
        $rectalResults = Rectal::getListResults();

        $response->format = Response::FORMAT_HTML;
        return $this->renderPartial('forms/edit-rectal', compact(
            'editModel',
            'usersList',
            'rectalResults'
        ));
    }

    /**
     * Форма проведения ректального исследования
     *
     * @param $id
     * @return string
     */
    public function actionAddRectalForm($id)
    {
        $response = Yii::$app->response;

        $model = Rectal::findOne($id);

        $usersList = ArrayHelper::map(User::getAllList(), "id", "lastName");
        $rectalResults = Rectal::getListResults();

        $response->format = Response::FORMAT_HTML;

        return $this->renderPartial('forms/add-rectal', compact(
            'model',
            'usersList',
            'rectalResults'
        ));
    }

    /**
     * Редактирование ректального исследования
     * @param $id
     * @return Response
     * @throws Exception
     */
    public function actionEditRectal($id)
    {
        /** @var Rectal $model */
        $model = new Rectal();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $updateModel = Rectal::findOne($id);
                    $model->setAttribute('date', (new DateTime($model->date))->format('Y-m-d H:i:s'));

                    $attributes = $model->getAttributes();
                    unset($attributes['id']);
                    $updateModel->updateAttributes($attributes);

                    $animal = Animal::findOne($model->animal_id);
                    $editDate = (new DateTime($model->date))->format('d.m.Y');
                    $actionText = "Отредактировал ректальное исследование #$animal->label от ($editDate)";

                    /** @var AnimalHistory $newAnimalHistory */
                    $newAnimalHistory = new AnimalHistory([
                        'animal_id'   => $model->animal_id,
                        'user_id'     => Yii::$app->getUser()->getId(),
                        'date'        => (new DateTime('now',
                            new DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
                        'action_type' => AnimalHistory::ACTION_TYPE_EDIT_RECTAL,
                        'action_text' => $actionText
                    ]);

                    if (!$newAnimalHistory->save()) {
                        throw new Exception('Ошибка при сохранении в амбулаторный журнал');
                    }

                    Yii::$app->session->setFlash('success', 'Успешное редактирование ректального исследования');
                    $transaction->commit();
                }
                return $this->redirect(['detail', 'id' => $model->animal_id]);
            }
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при редактировании ректального исследования');
            $transaction->rollBack();
            return $this->redirect(['detail', 'id' => $model->animal_id]);
        }
    }
}
