<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppConference
 * @package Source\Models
 */
class AppConference extends Model
{
    /**
     * AppConference constructor.
     */
    public function __construct()
    {
        /* parent::__construct("app_conference", ["id"], ['remessa'], [], []); */
        parent::__construct(
            "app_conference",
            ["id"],
            ["remessa"],
            [
                "users" => "user_id",
                "app_clients" => "client_id"
            ],
            [
                "users" => "id",
                "app_clients" => "id"
            ],
            "app_conference.*, concat(users.first_name,' ', users.last_name) as nomeUsuario, app_clients.id as cid, app_clients.nome as nomeClient"
        );
    }

    public function remessa(User $user)
    {
        return $this->join('LEFT JOIN')->find("app_conference.client_id = :cid", "cid={$user->client_id}")->order('data_log DESC, status')->fetch(true);
    }

    public function getRemessa($remessa, User $user, $all = true)
    {
        return $this->find("remessa = :r AND client_id = :cid", "r={$remessa}&cid={$user->client_id}")->fetch($all);
    }
}
