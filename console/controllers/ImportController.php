<?php

namespace console\controllers;

use common\helpers\Excel\ExcelHelper;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 08.08.2018
 * Time: 19:11
 */
class ImportController extends Controller
{
    /**
     * @var
     */
    public $filename;

    /**
     * @param string $actionID
     * @return array
     */
    public function options($actionID)
    {
        return ['filename'];
    }

    /**
     * @return array
     */
    public function optionAliases()
    {
        return ['f' => 'filename'];
    }

    /**
     * Импорт
     */
    public function actionIndex()
    {
        if (empty($this->filename)) {
            $this->stdout('Введите название файла');
            return;
        }

        $result_import = ExcelHelper::import($this->filename);

        if ($result_import) {
            $this->stdout('Импорт успешно выполнен');
        } else {
            $this->stdout('При выполнении испорта произошла ошибка');
        }
    }
}