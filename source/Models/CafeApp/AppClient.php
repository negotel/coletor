<?php

namespace Source\Models\CafeApp;

use Source\Core\Model;

/**
 * Class AppCategory
 * @package Source\Models\CafeApp
 */
class AppClient extends Model
{
    /**
     * AppCategory constructor.
     */
    public function __construct()
    {
        
        parent::__construct("app_clients",["id"], ["nome"],[],[], "*");
    }
}
