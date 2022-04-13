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
        parent::__construct("app_conference_log", ["id"], ['n_pedido'],[], []);
    }

    public function log(?array $data)
    {
        return $this->create($data);
    }
}
