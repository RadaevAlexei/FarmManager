<?php

namespace common\models;

use Yii;

/**
 * Class Breed
 * @package common\models
 */
class Breed
{
    const TYPE_MILK = 'Специализированные молочные породы';

    const TYPE_MEAT = 'Специализированные мясные породы';
    const TYPE_MEAT_BRITISH = 'Британского происхождения';
    const TYPE_MEAT_FRENCH = 'Французского происхождения';
    const TYPE_MEAT_ITALIAN = 'Итальянского происхождения';
    const TYPE_MEAT_AZIA = 'Центрально-азиатского происхождения';
    const TYPE_MEAT_GIBRID = 'Породы гибридного происхождения';

    const TYPE_COMBO = 'Комбинированные породы скота';

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::TYPE_MILK => [
                0 => 'айрширская',
                1 => 'бурая швицкая',
                2 => 'гернзейская',
                3 => 'голштинская',
                4 => 'джерсейская',
                5 => 'красная датская',
                6 => 'красная прибалтийская',
                7 => 'красная степная',
                8 => 'тагильская',
                9 => 'холмогорская',
                10 => 'чёрно-пёстрая',
                11 => 'ярославская',
            ],
            self::TYPE_MEAT => [
                self::TYPE_MEAT_BRITISH => [
                    12 => 'абердин-ангусская',
                    13 => 'бифбилд',
                    14 => 'галловейская',
                    15 => 'герефордская',
                    16 => 'девонская',
                    17 => 'декстер',
                    18 => 'линкольнская',
                    19 => 'лонгхорнская',
                    20 => 'суссекская',
                    21 => 'хайленд',
                    22 => 'шортгорнская',
                ],
                self::TYPE_MEAT_FRENCH => [
                    23 => 'лимузинская',
                    24 => 'мен-анжу',
                    25 => 'салерская',
                    26 => 'светлая аквитанская',
                    27 => 'шароле',
                ],
                self::TYPE_MEAT_ITALIAN => [
                    28 => 'кианская',
                    29 => 'маркиджанская',
                    30 => 'пьемодская',
                    31 => 'романьольская',
                ],
                self::TYPE_MEAT_AZIA => [
                    32 => 'казахская белоголовая',
                    33 => 'казахская (киргизская)',
                    34 => 'калмыцкая',
                    35 => 'серая украинская',
                ],
                self::TYPE_MEAT_GIBRID => [
                    36 => 'африкандер',
                    37 => 'бифмастер',
                    38 => 'боран',
                    39 => 'барзона',
                    40 => 'босмара',
                    41 => 'браман',
                    42 => 'брангус',
                    43 => 'катало',
                    44 => 'санта-гертруда',
                    45 => 'шабрай (чабрай)',
                    46 => 'волынская мясная',
                ],
            ],
            self::TYPE_COMBO => [
                47 => 'алатауская',
                48 => 'бестужевская',
                49 => 'бурая карпатская',
                50 => 'кавказская бурая',
                51 => 'костромская',
                52 => 'красная горбатовская',
                53 => 'красная тамбовская',
                54 => 'лебединская',
                55 => 'монбельярдская',
                56 => 'симментальская',
                57 => 'синяя корова',
                58 => 'суксунская',
                59 => 'сычёвская',
                60 => 'бурая швицкая',
                61 => 'юринская',
                62 => 'якутская',
            ],
        ];
    }

    /**
     * @param $breed
     * @return mixed
     */
    public static function getName($breed)
    {
        $list = self::getList();

        if ($breed >=0 && $breed <= 11) {
            $name = $list[self::TYPE_MILK][$breed];
        } else if ($breed >=12 && $breed <= 22) {
            $name = $list[self::TYPE_MEAT][self::TYPE_MEAT_BRITISH][$breed];
        } else if ($breed >=23 && $breed <= 27) {
            $name = $list[self::TYPE_MEAT][self::TYPE_MEAT_FRENCH][$breed];
        } else if ($breed >=28 && $breed <= 31) {
            $name = $list[self::TYPE_MEAT][self::TYPE_MEAT_ITALIAN][$breed];
        } else if ($breed >=32 && $breed <= 35) {
            $name = $list[self::TYPE_MEAT][self::TYPE_MEAT_AZIA][$breed];
        } else if ($breed >=36 && $breed <= 46) {
            $name = $list[self::TYPE_MEAT][self::TYPE_MEAT_GIBRID][$breed];
        } else {
            $name = $list[self::TYPE_COMBO][$breed];
        }

        return $name;
    }
}