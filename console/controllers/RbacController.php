<?php

namespace console\controllers;

use Yii;
use common\models\UserRole;
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
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        $this->_auth = Yii::$app->authManager;

        // Создаем разрешения
        // Пользователи
        $userView = $this->secureCreatePermission('userView',  'Просмотр сотрудников');
        $userEdit = $this->secureCreatePermission('userEdit',  'Редактирование сотрудников');
        $userPositionView = $this->secureCreatePermission('userPositionView',  'Просмотр должностей');
        $userPositionEdit = $this->secureCreatePermission('userPositionEdit',  'Редактирование должностей');

        // Стадо
        $animalView = $this->secureCreatePermission('animalView',  'Просмотр стада');
        $animalEdit = $this->secureCreatePermission('animalEdit',  'Редактирование стада');
        $animalColorView = $this->secureCreatePermission('animalColorView',  'Просмотр мастей');
        $animalColorEdit = $this->secureCreatePermission('animalColorEdit',  'Редактирование мастей');
        $cowshedView = $this->secureCreatePermission('cowshedView',  'Просмотр коровников');
        $cowshedEdit = $this->secureCreatePermission('cowshedEdit',  'Редактирование коровников');
        $farmView = $this->secureCreatePermission('farmView',  'Просмотр ферм');
        $farmEdit = $this->secureCreatePermission('farmEdit',  'Редактирование ферм');
        $animalGroupView = $this->secureCreatePermission('animalGroupView',  'Просмотр списка групп животных');
        $animalGroupEdit = $this->secureCreatePermission('animalGroupEdit',  'Редактирование списка групп животных');

        // Амбулаторный журнал
        $schemeManageView = $this->secureCreatePermission('schemeManageView',  'Просмотр управление схемами');
        $schemeManageEdit = $this->secureCreatePermission('schemeManageEdit',  'Редактирование управление схемами');
        $diagnosisView = $this->secureCreatePermission('diagnosisView',  'Просмотр диагнозов');
        $diagnosisEdit = $this->secureCreatePermission('diagnosisEdit',  'Редактирование диагнозов');
        $managePharmacyView = $this->secureCreatePermission('managePharmacyView',  'Просмотр управление аптекой');
        $managePharmacyEdit = $this->secureCreatePermission('managePharmacyEdit',  'Редактирование управление аптекой');
        $schemeActionDayView = $this->secureCreatePermission('schemeActionDayView',  'Просмотр списка дел');
        $schemeActionDayEdit = $this->secureCreatePermission('schemeActionDayEdit',  'Редактирование списка дел');
        $animalSickView = $this->secureCreatePermission('animalSickView',  'Просмотр списка больных животных');
        $animalSickEdit = $this->secureCreatePermission('animalSickEdit',  'Редактирование списка больных животных');
        $animalAwaitingView = $this->secureCreatePermission('animalAwaitingView',  'Просмотр списка животных в ожидании');
        $animalAwaitingEdit = $this->secureCreatePermission('animalAwaitingEdit',  'Редактирование списка животных в ожидании');

        // Воспроизводство
        $seedSupplierView = $this->secureCreatePermission('seedSupplierView',  'Просмотр поставщиков семени');
        $seedSupplierEdit = $this->secureCreatePermission('seedSupplierEdit',  'Редактирование поставщиков семени');
        $seedBullView = $this->secureCreatePermission('seedBullView',  'Просмотр списка быков');
        $seedBullEdit = $this->secureCreatePermission('seedBullEdit',  'Редактирование списка быков');
        $containerDuaraView = $this->secureCreatePermission('containerDuaraView',  'Просмотр сосудов дьюара');
        $containerDuaraEdit = $this->secureCreatePermission('containerDuaraEdit',  'Редактирование сосудов дьюара');
        $seedCashBookView = $this->secureCreatePermission('seedCashBookView',  'Просмотр расхода/прихода');
        $seedCashBookEdit = $this->secureCreatePermission('seedCashBookEdit',  'Редактирование расхода/прихода');

        // Ректальное исследование
        $rectalListView = $this->secureCreatePermission('rectalListView',  'Просмотр животных под РИ');
        $rectalListEdit = $this->secureCreatePermission('rectalListEdit',  'Редактирование животных под РИ');
        $rectalSettingsView = $this->secureCreatePermission('rectalSettingsView',  'Просмотр настроек РИ');
        $rectalSettingsEdit = $this->secureCreatePermission('rectalSettingsEdit',  'Редактирование настроек РИ');

        // Зоотехническая служба
        $liveStockReportView = $this->secureCreatePermission('rectalSettingsView',  'Формирование отчетов');

        // Создаем роли
        $adminRole = $this->secureCreateRole(UserRole::ROLE_ADMIN);
        $veterinaryRole = $this->secureCreateRole(UserRole::ROLE_VETERINARY_SERVICE);
        $zootechnicalRole = $this->secureCreateRole(UserRole::ROLE_ZOOTECHNICAL_SERVICE);
        $viewerRole = $this->secureCreateRole(UserRole::ROLE_VIEWER);

        // Назначаем пермишены на роли
        $this->secureAssign($viewerRole, $userView);
        $this->secureAssign($viewerRole, $userPositionView);
        $this->secureAssign($viewerRole, $animalView);
        $this->secureAssign($viewerRole, $animalColorView);
        $this->secureAssign($viewerRole, $cowshedView);
        $this->secureAssign($viewerRole, $farmView);
        $this->secureAssign($viewerRole, $animalGroupView);
        $this->secureAssign($viewerRole, $schemeManageView);
        $this->secureAssign($viewerRole, $diagnosisView);
        $this->secureAssign($viewerRole, $managePharmacyView);
        $this->secureAssign($viewerRole, $schemeActionDayView);
        $this->secureAssign($viewerRole, $animalSickView);
        $this->secureAssign($viewerRole, $animalAwaitingView);
        $this->secureAssign($viewerRole, $rectalListView);
        $this->secureAssign($viewerRole, $rectalSettingsView);
        $this->secureAssign($viewerRole, $seedSupplierView);
        $this->secureAssign($viewerRole, $seedBullView);
        $this->secureAssign($viewerRole, $containerDuaraView);
        $this->secureAssign($viewerRole, $seedCashBookView);
        $this->secureAssign($viewerRole, $liveStockReportView);

        $this->secureAssign($veterinaryRole, $animalEdit);
        $this->secureAssign($veterinaryRole, $schemeManageEdit);
        $this->secureAssign($veterinaryRole, $diagnosisEdit);
        $this->secureAssign($veterinaryRole, $managePharmacyEdit);
        $this->secureAssign($veterinaryRole, $schemeActionDayEdit);
        $this->secureAssign($veterinaryRole, $animalSickEdit);
        $this->secureAssign($veterinaryRole, $animalAwaitingEdit);
        $this->secureAssign($veterinaryRole, $rectalListEdit);
        $this->secureAssign($veterinaryRole, $rectalSettingsEdit);
        $this->secureAssign($veterinaryRole, $viewerRole);

        $this->secureAssign($zootechnicalRole, $seedSupplierEdit);
        $this->secureAssign($zootechnicalRole, $seedBullEdit);
        $this->secureAssign($zootechnicalRole, $containerDuaraEdit);
        $this->secureAssign($zootechnicalRole, $seedCashBookEdit);
        $this->secureAssign($zootechnicalRole, $viewerRole);

        $this->secureAssign($adminRole, $userEdit);
        $this->secureAssign($adminRole, $userPositionEdit);
        $this->secureAssign($adminRole, $animalColorEdit);
        $this->secureAssign($adminRole, $cowshedEdit);
        $this->secureAssign($adminRole, $farmEdit);
        $this->secureAssign($adminRole, $animalGroupEdit);
        $this->secureAssign($adminRole, $viewerRole);

        $this->stdout("RBAC has been initialized!\n", Console::FG_GREEN);
    }

    /**
     * Создание админа и назначение ему роли админа
     * TODO:: Поправить создание админа
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
