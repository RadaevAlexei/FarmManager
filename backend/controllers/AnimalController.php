<?php

namespace backend\controllers;

use backend\models\forms\AnimalDiagnosisForm;
use backend\models\forms\CloseSchemeForm;
use backend\models\forms\HealthForm;
use backend\models\forms\UploadForm;
use backend\models\search\AnimalSearch;
use backend\models\search\AnimalSickSearch;
use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\AnimalHistory;
use backend\modules\scheme\models\Diagnosis;
use common\helpers\Excel\ExcelHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Yii;
use backend\modules\scheme\models\AppropriationScheme;
use backend\modules\scheme\models\Scheme;
use common\helpers\DataHelper;
use common\models\Animal;
use common\models\Cow;
use common\models\Color;
use common\models\search\CowSearch;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
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
            "searchModel" => $searchModel,
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
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
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
     *
     * @return string
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

        /** @var AppropriationScheme $animalOnScheme */
        $animalOnScheme = $model->onScheme();
        $closeScheme = false;
        $actionsToday = [];
        if ($animalOnScheme) {
            $actionsToday = $model->getActionsToday($animalOnScheme);

            $existNewActions = ActionHistory::find()
                ->where([
                    'appropriation_scheme_id' => $animalOnScheme->id,
                    'status' => ActionHistory::STATUS_NEW
                ])
                ->exists();

            if (!$existNewActions) {
                $closeScheme = true;
            }
        }


        $appropriationScheme = new AppropriationScheme([
            'animal_id' => $id,
            'status' => AppropriationScheme::STATUS_IN_PROGRESS,
        ]);

        /** @var AnimalHistory[] $history */
        $history = AnimalHistory::find()
            ->where(['animal_id' => $model->id])
            ->orderBy(['date' => SORT_DESC])
            ->all();

        return $this->render('new-detail',
            compact(
                'model',
                'schemeList',
                'appropriationScheme',
                'animalOnScheme',
                'actionsToday',
                'history',
                'closeScheme'
            )
        );
    }

    /**
     * Поставить животное на схему
     *
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionAppropriationScheme()
    {
        /** @var AppropriationScheme $model */
        $model = new AppropriationScheme();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $isLoading = $model->load(Yii::$app->request->post());
            if ($isLoading && $model->validate()) {
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
            "action" => $action,
            "url" => $url,
            "model" => $model,
            "groups" => $groups,
            "colors" => $colors,
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

    /**
     * @return \yii\web\Response
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
     * @return \yii\web\Response
     * @throws \Throwable
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
                            'health_status' => $model->health_status,
                            'date_health' => (new \DateTime($model->date_health))->format('Y-m-d H:i:s'),
                        ]);

                        $userId = Yii::$app->getUser()->getIdentity()->getId();

                        $health_status = ($model->health_status == Animal::HEALTH_STATUS_HEALTHY) ? "Здоровая" : "Больная";

                        /** @var AnimalHistory $newAnimalHistory */
                        $newAnimalHistory = new AnimalHistory([
                            'animal_id' => $animal->id,
                            'user_id' => $userId,
                            'date' => (new \DateTime('now',
                                new \DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
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
     * @return \yii\web\Response
     * @throws \Throwable
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
                        $animal->updateAttributes([
                            'health_status' => $model->health_status,
                            'date_health' => (new \DateTime($model->date_health))->format('Y-m-d H:i:s'),
                        ]);

                        AppropriationScheme::findOne($model->appropriation_scheme_id)
                            ->updateAttributes([
                                'status' => AppropriationScheme::STATUS_CLOSED,
                                'comment' => 'комментарий',
                            ]);

                        $userId = Yii::$app->getUser()->getIdentity()->getId();

                        $health_status = ($model->health_status == Animal::HEALTH_STATUS_HEALTHY) ? "Здоровая" : "Больная";

                        /** @var AnimalHistory $newAnimalHistory */
                        $newAnimalHistory = new AnimalHistory([
                            'animal_id' => $animal->id,
                            'user_id' => $userId,
                            'date' => (new \DateTime('now',
                                new \DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
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
     * @return \yii\web\Response
     * @throws \Throwable
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
                            'diagnosis' => $model->diagnosis,
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
                            'animal_id' => $animal->id,
                            'user_id' => $userId,
                            'date' => (new \DateTime('now',
                                new \DateTimeZone('Europe/Samara')))->format('Y-m-d H:i:s'),
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

    private function getPathTemplate()
    {
        return Yii::getAlias('@webroot') . '/templates/' . self::TEMPLATE_NAME;
    }

    private function getTimePrefix()
    {
        return (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y_m_d_H_i_s');
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

        $sheet->setCellValue("G1", (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('d.m.Y'));

        /** @var Animal[] $animals */
        $animals = Animal::find()
            ->alias('a')
            ->with([
                'diagnoses' => function (ActiveQuery $query) {
                    $query->alias('d');
                },
                'appropriationScheme' => function (ActiveQuery $query) {
                    $query->alias('as');
                    $query->joinWith([
                        'scheme' => function (ActiveQuery $query) {
                            $query->alias('s');
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
                (new \DateTime(ArrayHelper::getValue($animal,
                    "date_health")))->format('d.m.Y'));
            $sheet->setCellValue("E$offset", ArrayHelper::getValue($animal, "diagnoses.name"));
            $sheet->setCellValue("F$offset",
                (new \DateTime(ArrayHelper::getValue($animal,
                    "appropriationScheme.started_at")))->format('d.m.Y'));
            $sheet->setCellValue("G$offset", ArrayHelper::getValue($animal, "appropriationScheme.scheme.name"));
            $sheet->setCellValue("H$offset",
                count(ArrayHelper::getValue($animal, "appropriationScheme.scheme.schemeDays")));

            $offset++;
        }

        $end = $offset + 1;
        $spreadsheet->getActiveSheet()->getStyle("A4:H$end")->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle("A4:H$end")->getFont()->setBold(false)->setSize(10);

        $sheet->setTitle('Список больных животных');

        $writer = IOFactory::createWriter($spreadsheet, self::WRITER_TYPE);
        $prefix = $this->getTimePrefix();
        $newFileName = self::DIRECTORY_REPORTS . "/" . self::TEMPLATE_FILE_NAME . '_' . $prefix . '.xlsx';
        $writer->save($newFileName);

        return Yii::$app->response->sendFile($newFileName);
    }
}