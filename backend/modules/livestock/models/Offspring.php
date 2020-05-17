<?php

namespace backend\modules\livestock\models;

use common\models\Animal;
use common\models\AnimalGroup;
use common\models\CalvingLink;
use common\models\Color;
use Yii;
use common\models\Calving;
use yii\db\ActiveQuery;

/**
 * Class Offspring
 * @package backend\modules\livestock\models
 */
class Offspring
{
    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getCalvingList($dateFrom = null, $dateTo = null)
    {
        $query = CalvingLink::find()
            ->alias('link')
            ->select([
                'c.id as calving_id',
                'DATE_FORMAT(c.date, "%d.%m.%Y") as calving_date',
                'mother.id as mother_id',
                'mother.label as mother_label',
                'child.id as child_id',
                'child.sex',
                'child.label as child_label',
                'child.birth_weight',
                'child.birthday as child_birthday',
                'child.health_status as child_health_status',
                'child_group.name as child_group_name',
                'child_color.short_name as child_color_shortname',
            ])
            ->innerJoin(['c' => Calving::tableName()], 'link.calving_id = c.id')
            ->leftJoin(['mother' => Animal::tableName()], 'c.animal_id = mother.id')
            ->leftJoin(['child' => Animal::tableName()], 'link.child_animal_id = child.id')
            ->leftJoin(['child_group' => AnimalGroup::tableName()], 'child.animal_group_id = child_group.id')
            ->leftJoin(['child_color' => Color::tableName()], 'child.color_id = child_color.id');

        if ($dateFrom && $dateTo) {
            $query->andWhere([
                'and',
                ['>=', 'c.date', $dateFrom],
                ['<=', 'c.date', $dateTo]
            ]);
        } else if ($dateFrom) {
            $query->andWhere(['>=', 'c.date', $dateFrom]);
        } else if ($dateTo) {
            $query->andWhere(['<=', 'c.date', $dateTo]);
        }

        return $query->asArray()->all();
    }
}