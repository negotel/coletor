<?php

namespace Source\Models\CafeApp;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppCategory
 * @package Source\Models\CafeApp
 */
class AppImportTemplate extends Model
{
    /**
     * AppCategory constructor.
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
