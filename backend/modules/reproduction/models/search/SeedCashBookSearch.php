<?php

namespace backend\modules\reproduction\models\search;

use backend\modules\reproduction\models\SeedCashBook;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class SeedCashBookSearch
 * @package backend\modules\reproduction\models\search
 */
class SeedCashBookSearch extends SeedCashBook
{
    /**
     * @param $params
     *
     * @return ArrayDataProvider
     */
    public function search($params)
    {
        $queryDebit = SeedCashBook::find()
            ->select([
                'sb.id as seed_bull_id',
                'sb.nickname as seed_bull_name',
                'sum(count) as count',
                'sum(total_price_without_vat) as price'
            ])
            ->leftJoin('seed_bull as sb', 'seed_bull_id = sb.id')
            ->where(['=', 'type', SeedCashBook::TYPE_DEBIT])
            ->groupBy(['seed_bull_id'])
            ->asArray()
            ->all();
        
        $result = [];
        foreach ($queryDebit as $item_debit) {
            $result[$item_debit["seed_bull_id"]]['seed_bull_id'] = $item_debit['seed_bull_id'];
            $result[$item_debit["seed_bull_id"]]['seed_bull_name'] = $item_debit['seed_bull_name'];
            $result[$item_debit["seed_bull_id"]]['debit'] = [
                'count' => $item_debit['count'],
                'price' => $item_debit['price']
            ];
        }

        $kreditQuery = SeedCashBook::find()
            ->select([
                'sb.id as seed_bull_id',
                'sb.nickname as seed_bull_name',
                'sum(count) as count',
                'sum(total_price_without_vat) as price'
            ])
            ->leftJoin('seed_bull as sb', 'seed_bull_id = sb.id')
            ->where(['=', 'type', SeedCashBook::TYPE_KREDIT])
            ->groupBy(['seed_bull_id'])
            ->asArray()
            ->all();

        foreach ($kreditQuery as $item_kredit) {
            $result[$item_kredit["seed_bull_id"]]['seed_bull_id'] = $item_kredit['seed_bull_id'];
            $result[$item_kredit["seed_bull_id"]]['seed_bull_name'] = $item_kredit['seed_bull_name'];
            $result[$item_kredit["seed_bull_id"]]['kredit'] = [
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
