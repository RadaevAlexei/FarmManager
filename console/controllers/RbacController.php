<?php

namespace console\controllers;

use Yii;
use Exception;
use common\models\User;
use yii\console\Controller;
use yii\helpers\Console;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Role;

/**
 * Class RbacController
 * @package console\controllers
 */
class RbacController extends Controller
{
    /** @var ManagerInterface */
    public $_auth;

    /**
     * @param $permission
     * @param $description
     * @return null|Permission
     * @throws Exception
     */
    private function secureCreatePermission($permission, $description)
    {
        $curPermission = $this->_auth->getPermission($permission);
        if ($curPermission) {
            return $curPermission;
        }

        $newPermission = $this->_auth->createPermission($permission);
        $newPermission->description = $description;
        $this->_auth->add($newPermission);
        return $newPermission;
    }

    /**
     * @param $role
     * @return null|Role
     * @throws Exception
     */
    private function secureCreateRole($role)
    {
        $curRole = $this->_auth->getRole($role);
        if ($curRole) {
            return $curRole;
        }

        $newRole = $this->_auth->createRole($role);
        $this->_auth->add($newRole);
        return $newRole;
    }

    /**
     * @param $role
     * @param $child
     * @throws \yii\base\Exception
     */
    private function secureAssign($role, $child)
    {
        if ($this->_auth->hasChild($role, $child)) {
            return;
        }
        $this->_auth->addChild($role, $child);
    }

    /**
     * @throws Exception
     */
    public function actionInit()
    {
        $this->_auth = Yii::$app->authManager;

        // Создаем разрешения
//        $addUser = $this->secureCreatePermission('addUser',  'Возможность добавления пользователя');

        // Создаем роли
        $adminRole = $this->secureCreateRole('admin');

//        $this->secureAssign($curatorRole, $createAgent);
//        $this->secureAssign($adminRole, $createCurator);

        $this->stdout("RBAC has been initialized!\n", Console::FG_GREEN);
    }

    /**
     * Создание админа и назначение ему роли админа
     * @throws Exception
     */
    public function actionCreateAdmin()
    {
        $user = User::find()
            ->where(['username' => 'admin'])
            ->one();

        if ($user) {
            $this->stdout("Admin already exist!\n", Console::FG_RED);
            return;
        }

        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@admin.ru';
        $user->setPassword(123456);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        if ($user->save()) {
            // Навешиваем ему роль админа
            $auth = Yii::$app->authManager;
            $auth->assign($auth->getRole(User::ROLE_ADMIN), $user->id);
            $this->stdout("Admin was successfully created!\n", Console::FG_GREEN);
        } else {
            $this->stdout("Error in admin creation process!\n", Console::FG_RED);
        }
    }

    /**
     * Assign role
     * @param $username
     * @param $role
     * @throws Exception
     */
    public function actionAssignRole($username, $role)
    {
        $user = User::findOne(['username' => $username]);

        if ($user) {
            $auth = Yii::$app->authManager;
            $roleAssign = $auth->getRole($role);
            if (!$roleAssign) {
                $this->stdout("Role \"$role\" is not found\n", Console::FG_RED);
                return;
            }

            if ($auth->getAssignment($role, $user->id)) {
                $this->stdout("This role \"$role\" already assigned to user - \"$username\"!\n", Console::FG_RED);
                return;
            }

            $auth->assign($roleAssign, $user->id);
            $this->stdout("Successful assign role \"$role\" to user - \"$username\"!\n", Console::FG_GREEN);
        } else {
            $this->stdout("User - \"$username\" is not found!\n", Console::FG_YELLOW);
        }
    }

    /**
     * Revoke role
     * @param $username
     * @param $role
     */
    public function actionRevokeRole($username, $role)
    {
        $user = User::findOne(['username' => $username]);

        if ($user) {
            $auth = Yii::$app->authManager;
            $roleAssign = $auth->getRole($role);
            if (!$roleAssign) {
                $this->stdout("Role \"$role\" is not found\n", Console::FG_YELLOW);
                return;
            }

            if (!$auth->getAssignment($role, $user->id)) {
                $this->stdout("\"$username\" not have this role!\n", Console::FG_YELLOW);
                return;
            }

            $auth->revoke($roleAssign, $user->id);
            $this->stdout("Successful revoke role \"$role\" from user - \"$username\"!\n", Console::FG_GREEN);
        } else {
            $this->stdout("User - \"$username\" is not found!\n", Console::FG_YELLOW);
        }
    }
}
