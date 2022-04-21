<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class AppClient
 * @package Source\Models
 */
class AppClient extends Model
{
    /**
     * AppClient constructor.
     */
    public function __construct()
    {
        
        parent::__construct("app_clients",["id"], ["nome"],[],[], "*");
    }
}
