<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 17.11.2016
 * Time: 21:32
 */

namespace common\helpers;


use alexgx\phpexcel\PhpExcel;
use yii\helpers\ArrayHelper;

class TransfersAct
{
    public static function create($transfers = [])
    {
        $cl = new PhpExcel();
        $xls = $cl->load("C:\\OpenServer\\domains\\FarmManager\\fermer\\backend\\runtime\\uploads\\template.xls");
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();


        if (empty($transfers)) {
            return;
        }

        $sheet->setCellValue('H15', ArrayHelper::getValue($transfers[0], "groupFrom.name"));
        $sheet->setCellValue('N15', ArrayHelper::getValue($transfers[0], "groupTo.name"));

        $startIndex = 19;
        foreach($transfers as $transfer) {
            $sheet->setCellValue('A' . $startIndex, ArrayHelper::getValue($transfer, "calfInfo.number"));
            $sheet->setCellValue('E' . $startIndex, ArrayHelper::getValue($transfer, "calfInfo.color"));
            $sheet->setCellValue('I' . $startIndex, DataHelper::getDate(ArrayHelper::getValue($transfer, "calfInfo.birthday")));
            $sheet->setCellValue('J' . $startIndex, 1);
            $sheet->setCellValue('L' . $startIndex, ArrayHelper::getValue($transfer, "calfInfo.currentWeighing"));
            $sheet->setCellValue('P' . $startIndex, ArrayHelper::getValue($transfer, "groupTo.name"));
            $startIndex++;
        }

        $sheet->setCellValue('N15', ArrayHelper::getValue($transfers[0], "groupTo.name"));

        $cl->responseFile($xls, '222.xlsx', 'Excel2007');

    }
}