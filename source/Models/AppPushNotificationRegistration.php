<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppPushNotificationRegistration
 * @package Source\Models
 */
class AppPushNotificationRegistration extends Model
{
    /**
     * AppPushNotificationRegistration constructor.
     */
    public function __construct()
    {
        parent::__construct("app_push_notification_registration", ["id"], ['token_push_notification'], [], []);
    }

    public function getData()
    {
        return $this->find("status = :st", "st=ativo")->fetch(true);
    }

    public function findTokenPushNotification(string $token){
        return $this->find("token_push_notification = :token_push_notification", "token_push_notification={$token}")->fetch();
    }
}
