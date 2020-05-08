<?php

namespace backend\modules\scheme\controllers;

use Yii;
use backend\models\reports\ReportExcelActionDay;
use backend\modules\pharmacy\models\CashBook;
use backend\modules\pharmacy\models\Preparation;
use backend\modules\pharmacy\models\Storage;
use backend\modules\scheme\models\ActionListItem;
use backend\modules\scheme\models\AnimalHistory;
use backend\modules\scheme\models\Scheme;
use common\models\TypeField;
use DateTime;
use DateTimeZone;
use Exception;
use backend\modules\scheme\models\ActionHistory;
use backend\modules\scheme\models\search\ActionHistorySearch;
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
    /**
     * Страничка со списком схем, в которых нужно что-то сделать
     */
    public function actionIndex()
    {
        $filterDate = (new DateTime('now', new DateTimeZone('Europe/Samara')));

        if (Yii::$app->request->isPost) {
            $postDate = Yii::$app->request->post("filter_date");
            if (!empty($postDate)) {
                $filterDate = (new DateTime($postDate));
            }
        }

        $disableExecuteAction = false;
        if ($filterDate > new DateTime('now', new DateTimeZone('Europe/Samara'))) {
            $disableExecuteAction = true;
        }

        $filterDate = $filterDate->format('Y-m-d');

        /** @var ActionHistorySearch $searchModel */
        $searchModel = new ActionHistorySearch();

        /** @var ArrayDataProvider $dataProvider */
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, [
            'day'     => $filterDate,
            'overdue' => false
        ]));

        return $this->render('index',
            compact('searchModel', 'dataProvider', 'disableExecuteAction', 'filterDate')
        );
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionOverdue()
    {
        /** @var ActionHistorySearch $searchModel */
        $searchModel = new ActionHistorySearch();

        /** @var ArrayDataProvider $dataProvider */
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, [
            'day'     => (new DateTime('-1 days', new DateTimeZone('Europe/Samara')))
                ->setTime(23, 59, 59)
                ->format('Y-m-d H:i:s'),
            'overdue' => true
        ]));

        return $this->render('overdue-index',
            compact('searchModel', 'dataProvider')
        );
    }

    /**
     * Скачивание списка дел на сегодня
     *
     * @param null $filterDate
     * @return \yii\console\Response|\yii\web\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionDownloadActionList($filterDate = null)
    {
        $report = new ReportExcelActionDay($filterDate);
        $report->generateAndSave();
        return Yii::$app->response->sendFile($report->getNewFileName());
    }

    /**
     * @param $scheme_id
     * @param bool $disable
     * @return string
     * @throws Exception
     */
    public function actionDetails($scheme_id, $disable = false)
    {
        $history = ActionHistory::find()
            ->alias('ah')
            ->select(['ah.*', 'as.scheme_id', 'as.animal_id'])
            ->joinWith([
                'groupsAction',
                'action'              => function (ActiveQuery $query) {
                    $query->alias('ac');
                    $query->joinWith([
                        'actionList' => function (ActiveQuery $query) {
                            $query->alias('al');
                            $query->joinWith(['items']);
                        }
                    ]);
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
                            $query->where(['s.status' => Scheme::STATUS_ACTIVE]);
                            $query->andFilterWhere(['s.id' => $scheme_id]);
                        }
                    ]);
                },
            ])
            ->where([
                'ah.status'        => ActionHistory::STATUS_NEW,
                'ah.scheme_day_at' => (new DateTime('now', new DateTimeZone('Europe/Samara')))->format('Y-m-d')
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

        return $this->render('details', compact('details', 'disable'));
    }

    /**
     * @param $scheme_id
     * @return string
     * @throws Exception
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
                    $query->joinWith([
                        'actionList' => function (ActiveQuery $query) {
                            $query->alias('al');
                            $query->joinWith(['items']);
                        }
                    ]);
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
                            $query->where(['s.status' => Scheme::STATUS_ACTIVE]);
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
                    (new DateTime('-1 days', new DateTimeZone('Europe/Samara')))
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
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\Exception
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
                throw new Exception();
            }

            $post = Yii::$app->request->post("ExecuteForm");

            $value = ArrayHelper::getValue($post, "value");
            $preparationId = ArrayHelper::getValue($post, "preparation_id");
            $preparationVolume = ArrayHelper::getValue($post, "preparation_volume");
            $stockId = ArrayHelper::getValue($post, "stock_id");
            $executeAt = ArrayHelper::getValue($post, "execute_at");


            if (empty($value)) {
                throw new Exception('Заполните значение');
            }
            if (empty($executeAt)) {
                throw new Exception('Выберите дату!');
            } else {
                $executeAt = (new DateTime($executeAt))->format('Y-m-d H:i:s');
            }

            $type = ArrayHelper::getValue($post, "type");

            $actionHistory->setValueByType($type, $value, $executeAt);

            $user = Yii::$app->getUser()->getIdentity();
            $userId = ArrayHelper::getValue($user, "id");

            $animalName = ArrayHelper::getValue($actionHistory, "appropriationScheme.animal.nickname");
            $actionName = ArrayHelper::getValue($actionHistory, "action.name");

            if ($type == TypeField::TYPE_LIST) {
                $listItems = ActionListItem::find()->where(['in', 'id', $value])->all();
                if ($listItems) {
                    $mappedItems = ArrayHelper::map($listItems, "id", "name");
                    $values = array_values($mappedItems);
                    $value = json_encode($values, JSON_UNESCAPED_UNICODE);
                } else {
                    $value = json_encode($value);
                }
                $actionText = "Ввел \"$actionName\"=$value для \"$animalName\"";
            } else {
                if ($type == TypeField::TYPE_NUMBER && !empty($preparationId)) {

                    if (empty($stockId)) {
                        throw new Exception('Выберите склад!');
                    }

                    $preparation = Preparation::findOne($preparationId);
                    $preparationName = ArrayHelper::getValue($preparation, "name");
                    $actionText = "Потратил на животное \"$animalName\" - \"$value\"шт препарата - \"$preparationName\", объёмом \"$preparationVolume\"";

                    // Вычесть из склада
                    Storage::substractPreparation($preparationId, $stockId, $preparationVolume, $value);

                    // Добавить в расход
                    $cashBook = new CashBook();
                    $cashBook->user_id = Yii::$app->getUser()->id;
                    $cashBook->type = CashBook::TYPE_KREDIT;
                    $cashBook->date = $executeAt;
                    $cashBook->preparation_id = $preparationId;
                    $cashBook->stock_id = $stockId;
                    $cashBook->count = $value;
                    $cashBook->volume = $preparationVolume;
                    $cashBook->total_price_with_vat = $value * $preparation->price;
                    $cashBook->total_price_without_vat = $cashBook->total_price_with_vat;
                    $cashBook->vat_percent = 0;
                    $cashBook->save();
                } else {
                    $value = "\"$value\"";
                    $actionText = "Ввел \"$actionName\"=$value для \"$animalName\"";
                }
            }

            /** @var AnimalHistory $newAnimalHistory */
            $newAnimalHistory = new AnimalHistory([
                'animal_id'   => ArrayHelper::getValue($actionHistory, "appropriationScheme.animal.id"),
                'user_id'     => $userId,
                'date'        => $executeAt,
                'action_type' => AnimalHistory::ACTION_TYPE_EXECUTE_ACTION,
                'action_text' => $actionText
            ]);

            $newAnimalHistory->save();
            $transaction->commit();

            \Yii::$app->session->setFlash('success', 'Успешное выполнение действия');
            return $this->redirect([$returnAction, "scheme_id" => $scheme_id]);
        } catch (Exception $exception) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('error', $exception->getMessage());
            return $this->redirect([$returnAction, "scheme_id" => $scheme_id]);
        }
    }
}
