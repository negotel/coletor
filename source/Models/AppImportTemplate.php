<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppImportTemplate
 * @package Source\Models
 */
class AppImportTemplate extends Model
{
    /**
     * AppImportTemplate constructor.
     */
    public function __construct()
    {
        parent::__construct("app_template_import", ["id"], ["nome_layout", "template_padrao", ], [], []);
    }

    public function template(User $user)
    {
        return $this->find("user_id = :uid AND template_padrao = 'sim'", "uid={$user->id}")->fetch();
    }
}
