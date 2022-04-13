<?php

namespace Source\Models\CafeApp;

use Source\Core\Model;
use Source\Models\User;

/**
 * Class AppCategory
 * @package Source\Models\CafeApp
 */
class AppConferenceItem extends Model
{
    /**
     * AppCategory constructor.
     */
    public function __construct()
    {
        parent::__construct("app_conference_item", ["id"], ['remessa'], [], []);
    }

    public function itens(AppConference $remessa, $status = null)
    {
        $terms = '';
        if ($status != null) {
            $terms = "AND status = '{$status}'";
        }

        return $this->find("remessa = :remessa {$terms}", "remessa={$remessa->remessa}")->order('n_pedido ASC')->fetch(true);
    }

    public function countItem(AppConference $remessa): int
    {
        return $this->find("remessa = :remessa", "remessa={$remessa->remessa}")->count();;
    }
	
	 public function countItemRead(AppConference $remessa): int
    {
        return $this->find("remessa = :remessa AND status = 'coletado'", "remessa={$remessa->remessa}")->count();;
    }
}
