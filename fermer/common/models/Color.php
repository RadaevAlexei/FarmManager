<?php

namespace common\models;


use yii\db\ActiveRecord;

class Color extends ActiveRecord
{
    // Масть - Красно-пестрая
    const COLOR_CALF_RED_MOTLEY = 1;

    // Масть - Черно-пестрая
    const COLOR_CALF_BLACK_MOTLEY = 2;

    // Масть - Бурая
    const COLOR_CALF_BROWN = 3;
}