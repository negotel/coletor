<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class AppControlVersionAppMobile
 * @package Source\Models
 */
class AppControlVersionAppMobile extends Model
{
    /**
     * AppControlVersionAppMobile constructor.
     */
    public function __construct()
    {
        parent::__construct("app_control_version_app_mobile", ["id"], [], [], [], "*");
    }

    public function getNewVersion(){
        return $this->find("status = :sts", "sts=new")->fetch();
    }
}
