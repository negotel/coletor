<?php

namespace Source\Models\CafeApp;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppCategory
 * @package Source\Models\CafeApp
 */
class AppTemplateFile extends Model
{
    /**
     * AppCategory constructor.
     */
    public function __construct()
    {
        parent::__construct("app_template_file", ["id"], [],[], []);
    }
}
