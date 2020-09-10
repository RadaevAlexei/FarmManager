<?php


namespace common\models;

/**
 * Class UserRole
 * @package common\models
 */
class UserRole
{
    /**
     * Админ
     */
    const ROLE_ADMIN = 'admin';

    /**
     * Ветеринарная служба
     */
    const ROLE_VETERINARY_SERVICE = 'veterinary_service';

    /**
     * Зоотехническая служба
     */
    const ROLE_ZOOTECHNICAL_SERVICE = 'zootechnical_service';

    /**
     * Вьюер. Тот, кто может только просматривать, печатать отчеты
     */
    const ROLE_VIEWER = 'viewer';
}