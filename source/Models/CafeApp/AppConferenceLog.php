<?php

namespace Source\Models\CafeApp;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppCategory
 * @package Source\Models\CafeApp
 */
class AppConferenceLog extends Model
{
    /**
     * AppCategory constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "app_conference_log",
            ["id"],
            ['n_pedido'],
            [
                "app_conference_item" => "n_pedido"
            ],
            [
                "app_conference_item" => "n_pedido"
            ],
            "app_conference_item.id, app_conference_item.`status`, app_conference_item.remessa, app_conference_item.nome, app_conference_item.n_pedido,  app_conference_item.data_log, app_conference_log.data_log as data_coletado"
        );
    }

    public function log(?array $data)
    {
        return $this->create($data);
    }
}
