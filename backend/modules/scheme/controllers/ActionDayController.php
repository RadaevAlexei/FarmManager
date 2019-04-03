<?php

namespace backend\modules\scheme\controllers;

use backend\modules\scheme\models\AnimalHistory;
use Yii;
use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\search\ActionHistorySearch;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use backend\controllers\BackendController;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;


/**
 * Class ActionDayController
 * @package backend\modules\scheme\controllers
 */
class ActionDayController extends BackendController
{
    const TEMPLATE_NAME = "template_works_today.xlsx";
    const TEMPLATE_FILE_NAME = "works_today";
    const DIRECTORY_REPORTS = "actions_today";

    const READER_TYPE = "Xlsx";
    const WRITER_TYPE = "Xlsx";

    /**
     * Страничка со списком схем, в которых нужно что-то сделать
     */
    public function actionIndex()
    {
        /** @var ActionHistorySearch $searchModel */
        $searchModel = new ActionHistorySearch();

        /** @var ArrayDataProvider $dataProvider */
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, [
            'day'     => (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d'),
            'overdue' => false
        ]));

        return $this->render('index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * @return string
     */
    public function actionOverdue()
    {
        /** @var ActionHistorySearch $searchModel */
        $searchModel = new ActionHistorySearch();

        /** @var ArrayDataProvider $dataProvider */
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, [
            'day'     => (new \DateTime('-1 days', new \DateTimeZone('Europe/Samara')))
                ->setTime(23, 59, 59)
                ->format('Y-m-d H:i:s'),
            'overdue' => true
        ]));

        return $this->render('overdue-index',
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


    public function actionDownloadActionList()
    {
        /** @var BaseReader $reader */
        $templatePath = $this->getPathTemplate();

        $reader = new Xlsx();

        /** @var Spreadsheet $spreadsheet */
        $spreadsheet = $reader->load($templatePath);

        /** @var Worksheet $sheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue("H1", (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('d-m-Y'));

        /** @var ActionHistory[] $history */
        $history = ActionHistory::find()
            ->alias('ah')
            ->select(['ah.*', 'as.scheme_id', 'as.animal_id'])
            ->joinWith([
                'groupsAction',
                'action',
                'appropriationScheme' => function (ActiveQuery $query) {
                    $query->alias('as');
                    $query->joinWith([
                        'animal' => function (ActiveQuery $query) {
                            $query->alias('a');
                            $query->joinWith(['animalGroup']);
                        },
                        'scheme' => function (ActiveQuery $query) {
                            $query->alias('s');
                            $query->joinWith(['diagnosis']);
                        }
                    ]);
                },
            ])
            ->where([
                'ah.scheme_day_at' => (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d'),
                'ah.status'        => ActionHistory::STATUS_NEW
            ])
            ->all();

        $count = count($history);
        if ($count > 1) {
            $sheet->insertNewRowBefore(3, $count - 1);
        }

        $offset = 3;
        foreach ($history as $action) {
            $sheet->setCellValue("A$offset", ArrayHelper::getValue($action, "appropriationScheme.scheme.name"));
            $sheet->setCellValue("B$offset", ArrayHelper::getValue($action, "scheme_day"));
            $sheet->setCellValue("C$offset",
                ArrayHelper::getValue($action, "appropriationScheme.animal.animalGroup.name"));
            $sheet->setCellValue("D$offset", ArrayHelper::getValue($action, "appropriationScheme.animal.label"));
            $sheet->setCellValue("E$offset", ArrayHelper::getValue($action, "appropriationScheme.animal.label"));
            $sheet->setCellValue("F$offset",
                ArrayHelper::getValue($action, "appropriationScheme.scheme.diagnosis.name"));
            $sheet->setCellValue("G$offset", ArrayHelper::getValue($action, "groupsAction.name"));
            $sheet->setCellValue("H$offset", ArrayHelper::getValue($action, "action.name"));
            $offset++;
        }

        $end = $offset + 1;
        $spreadsheet->getActiveSheet()->getStyle("A3:J$end")->getFont()->setBold(false);

        $sheet->setTitle('Список дел на сегодня');

        $writer = IOFactory::createWriter($spreadsheet, self::WRITER_TYPE);
        $prefix = $this->getTimePrefix();
        $newFileName = self::DIRECTORY_REPORTS . "/" . self::TEMPLATE_FILE_NAME . '_' . $prefix . '.xlsx';
        $writer->save($newFileName);

        return Yii::$app->response->sendFile($newFileName);
    }

    /**
     * @param $scheme_id
     *
     * @return string
     */
    public function actionDetails($scheme_id)
    {
        $history = ActionHistory::find()
            ->alias('ah')
            ->select(['ah.*', 'as.scheme_id', 'as.animal_id'])
            ->joinWith([
                'groupsAction',
                'action'              => function (ActiveQuery $query) {
                    $query->alias('ac');
                    $query->joinWith(['actionList' => function (ActiveQuery $query) {
                        $query->alias('al');
                        $query->joinWith(['items']);
                    }]);
                },
                'appropriationScheme' => function (ActiveQuery $query) use ($scheme_id) {
                    $query->alias('as');
                    $query->joinWith([
                        'animal' => function (ActiveQuery $query) {
                            $query->alias('a');
                            $query->joinWith(['animalGroup']);
                        },
                        'scheme' => function (ActiveQuery $query) use ($scheme_id) {
                            $query->alias('s');
                            $query->andFilterWhere(['s.id' => $scheme_id]);
                        }
                    ]);
                },
            ])
            ->where([
                'ah.status'        => ActionHistory::STATUS_NEW,
                'ah.scheme_day_at' => (new \DateTime('now', new \DateTimeZone('Europe/Samara')))->format('Y-m-d')
            ])
            ->orderBy(['animal_id' => SORT_ASC])
            ->all();

        $details = [];
        foreach ($history as $action) {
            $animalId = ArrayHelper::getValue($action, "appropriationScheme.animal.id");
            $animalLabel = ArrayHelper::getValue($action, "appropriationScheme.animal.label");
            $animalNickname = ArrayHelper::getValue($action, "appropriationScheme.animal.nickname");
            $groupActionId = ArrayHelper::getValue($action, "groupsAction.id");
            $groupActionName = ArrayHelper::getValue($action, "groupsAction.name");

            $details[$animalId]["animal_nickname"] = $animalNickname;
            $details[$animalId]["animal_label"] = $animalLabel;
            $details[$animalId]["data"][$groupActionId]["group_action_name"] = $groupActionName;
            $details[$animalId]["data"][$groupActionId]["actions"][] = $action;
        }

        return $this->render('details', compact('details'));
    }

    /**
     * @param $scheme_id
     *
     * @return string
     */
    public function actionOverdueDetails($scheme_id)
    {
        $history = ActionHistory::find()
            ->alias('ah')
            ->select(['ah.*', 'as.scheme_id', 'as.animal_id'])
            ->joinWith([
                'groupsAction',
                'action'              => function (ActiveQuery $query) {
                    $query->alias('ac');
                    $query->joinWith(['actionList' => function (ActiveQuery $query) {
                        $query->alias('al');
                        $query->joinWith(['items']);
                    }]);
                },
                'appropriationScheme' => function (ActiveQuery $query) use ($scheme_id) {
                    $query->alias('as');
                    $query->joinWith([
                        'animal' => function (ActiveQuery $query) {
                            $query->alias('a');
                            $query->joinWith(['animalGroup']);
                        },
                        'scheme' => function (ActiveQuery $query) use ($scheme_id) {
                            $query->alias('s');
                            $query->andFilterWhere(['s.id' => $scheme_id]);
                        }
                    ]);
                },
            ])
            ->where([
                'and',
                ['=', 'ah.status', ActionHistory::STATUS_NEW],
                ['is', 'ah.execute_at', null],
                [
                    '<=',
                    'ah.scheme_day_at',
                    (new \DateTime('-1 days', new \DateTimeZone('Europe/Samara')))
                        ->setTime(23, 59, 59)
                        ->format('Y-m-d H:i:s')
                ]
            ])
            ->orderBy(['animal_id' => SORT_ASC])
            ->all();

        $details = [];
        foreach ($history as $action) {
            $animalId = ArrayHelper::getValue($action, "appropriationScheme.animal.id");
            $animalLabel = ArrayHelper::getValue($action, "appropriationScheme.animal.label");
            $animalNickname = ArrayHelper::getValue($action, "appropriationScheme.animal.nickname");
            $groupActionId = ArrayHelper::getValue($action, "groupsAction.id");
            $groupActionName = ArrayHelper::getValue($action, "groupsAction.name");

            $details[$animalId]["animal_nickname"] = $animalNickname;
            $details[$animalId]["animal_label"] = $animalLabel;
            $details[$animalId]["data"][$groupActionId]["group_action_name"] = $groupActionName;
            $details[$animalId]["data"][$groupActionId]["actions"][] = $action;
        }

        $overdue = true;

        return $this->render('details', compact('details', 'overdue'));
    }

    /**
     * @param $id
     * @param bool $overdue
     *
     * @return \yii\web\Response
     */
    public function actionExecute($id, $overdue = false)
    {
        /** @var ActionHistory $actionHistory */
        $actionHistory = ActionHistory::find()
            ->alias('ah')
            ->joinWith([
                'action'              => function (ActiveQuery $query) {
                    $query->alias('ac');
                },
                'appropriationScheme' => function (ActiveQuery $query) {
                    $query->alias('as');
                    $query->joinWith([
                        'scheme',
                        'animal' => function (ActiveQuery $query) {
                            $query->alias('a');
                        },
                    ]);
                },
            ])
            ->where([
                'ah.id' => $id
            ])->one();

        $transaction = Yii::$app->db->beginTransaction();

        $scheme_id = ArrayHelper::getValue($actionHistory, "appropriationScheme.scheme.id");

        $returnAction = $overdue ? "overdue-details" : "details";

        try {
            if (!$actionHistory) {
                throw new \Exception();
            }


            $post = Yii::$app->request->post("ExecuteForm");
            $value = ArrayHelper::getValue($post, "value");

            if (empty($value)) {
                throw new \Exception('Заполните значение');
            }

            $type = ArrayHelper::getValue($post, "type");
            $executeAt = ArrayHelper::getValue($post, "execute_at");

            $actionHistory->setValueByType($type, $value, $executeAt);

            $user = Yii::$app->getUser()->getIdentity();
            $userId = ArrayHelper::getValue($user, "id");

            $animalName = ArrayHelper::getValue($actionHistory, "appropriationScheme.animal.nickname");
            $actionName = ArrayHelper::getValue($actionHistory, "action.name");

            /** @var AnimalHistory $newAnimalHistory */
            $newAnimalHistory = new AnimalHistory([
                'animal_id'   => ArrayHelper::getValue($actionHistory, "appropriationScheme.animal.id"),
                'user_id'     => $userId,
                'date'        => $executeAt,
                'action_type' => AnimalHistory::ACTION_TYPE_EXECUTE_ACTION,
                'action_text' => "Ввел \"$actionName\"=\"$value\" для \"$animalName\""
            ]);

            $newAnimalHistory->save();

            $transaction->commit();

            \Yii::$app->session->setFlash('success', 'Успешное выполнение действия');
            return $this->redirect([$returnAction, "scheme_id" => $scheme_id]);
        } catch (\Exception $exception) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('error', $exception->getMessage());
            return $this->redirect([$returnAction, "scheme_id" => $scheme_id]);
        }
    }
}