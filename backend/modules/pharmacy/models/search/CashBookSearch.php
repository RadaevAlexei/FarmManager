<?php

namespace backend\modules\pharmacy\models\search;

use backend\modules\pharmacy\models\CashBook;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class CashBookSearch
 * @package backend\modules\pharmacy\models\search
 */
class CashBookSearch extends CashBook
{
    /**
     * @param $params
     *
     * @return ArrayDataProvider
     */
    public function search($params)
    {
        $queryDebit = CashBook::find()
            ->select([
                'p.id as preparation_id',
                'p.name as preparation_name',
                'sum(count) as count',
                'sum(total_price_without_vat) as price'
            ])
            ->leftJoin('preparation as p', 'preparation_id = p.id')
            ->where(['=', 'type', CashBook::TYPE_DEBIT])
            ->groupBy(['preparation_id'])
            ->asArray()
            ->all();
        
        $result = [];
        foreach ($queryDebit as $item_debit) {
            $result[$item_debit["preparation_id"]]['preparation_id'] = $item_debit['preparation_id'];
            $result[$item_debit["preparation_id"]]['preparation_name'] = $item_debit['preparation_name'];
            $result[$item_debit["preparation_id"]]['debit'] = [
                'count' => $item_debit['count'],
                'price' => $item_debit['price']
            ];
        }

        $kreditQuery = CashBook::find()
            ->select([
                'p.id as preparation_id',
                'p.name as preparation_name',
                'sum(count) as count',
                'sum(total_price_without_vat) as price'
            ])
            ->leftJoin('preparation as p', 'preparation_id = p.id')
            ->where(['=', 'type', CashBook::TYPE_KREDIT])
            ->groupBy(['preparation_id'])
            ->asArray()
            ->all();

        foreach ($kreditQuery as $item_kredit) {
            $result[$item_kredit["preparation_id"]]['preparation_id'] = $item_kredit['preparation_id'];
            $result[$item_kredit["preparation_id"]]['preparation_name'] = $item_kredit['preparation_name'];
            $result[$item_kredit["preparation_id"]]['kredit'] = [
                'count' => $item_kredit['count'],
                'price' => $item_kredit['price']
            ];
        }
        
        foreach ($result as &$item_result) {
            if (!array_key_exists('debit', $item_result)) {
                $item_result['debit'] = [
                    'count'            => 0,
                    'price'            => 0,
                ];
            }
            if (!array_key_exists('kredit', $item_result)) {
                $item_result['kredit'] = [
                    'count'            => 0,
                    'price'            => 0,
                ];
            }
            $item_result['remainder'] = [
                'count' => ArrayHelper::getValue($item_result, 'debit.count') - ArrayHelper::getValue($item_result,
                        'kredit.count'),
                'price' => ArrayHelper::getValue($item_result, 'debit.price') - ArrayHelper::getValue($item_result,
                        'kredit.price')
            ];
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels'  => $result,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
