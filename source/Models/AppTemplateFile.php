<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppTemplateFile
 * @package Source\Models
 */
class AppTemplateFile extends Model
{
    /**
     * AppTemplateFile constructor.
     */
    public function __construct()
    {
        parent::__construct("app_template_file", ["id"], [],[], []);
    }
}
