<?php


namespace common\helpers;

use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * Class AccessControlHelper
 * @package frontend\models\helpers
 */
class AccessControl
{
    /**
     * @param array $parents
     * @param array $roles
     * @return array
     */
    public static function mergeBehavior($parents = [], $roles = [])
    {
        ArrayHelper::setValue(
            $parents,
            "access.rules.access-control.matchCallback",
            function ($rule, $action) use ($roles) {
                if (empty($roles)) {
                    return true;
                }
                foreach ($roles as $role) {
                    if (User::getCurRoleCode() === $role) {
                        return true;
                    }
                }
                return false;
            }
        );

        return $parents;
    }
}

