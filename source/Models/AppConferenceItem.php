<?php

namespace Source\Models;

use DateTime;
use Source\Core\Model;

/**
 * Class AppConferenceItem
 * @package Source\Models
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
        return $this->find("remessa = :remessa", "remessa={$remessa->remessa}")->count();
    }

    public function countItemRead(AppConference $remessa): int
    {
        return $this->find("remessa = :remessa AND status = 'coletado'", "remessa={$remessa->remessa}")->count();;
    }

    public function analysisDashMonth($month_init = 1, $month_end = 12, $status = null): array
    {
        $query_string = '';
        $dataset = [];

        for ($month = $month_init; $month <= $month_end; $month++) {
            $date = new DateTime(date("Y-{$month}-01"));

            for ($day = 1; $day <= $date->format("t"); $day++) {
                $query_string .= "(SELECT IFNULL(count(id), 0) as '" . $day . "' FROM app_conference_item WHERE EXTRACT(day FROM data_log) = '" . $day . "' AND EXTRACT(month FROM data_log) = " . str_pad($month, 2, '0', STR_PAD_LEFT) . " AND `status` = '{$status}') '" . $day . "'";
                if ($day < $date->format("t")) {
                    $query_string .= ',';
                }
            }

            $result = $this->find(null, null, $query_string)->fetch();
            $result = (array)$result->data();

            $dataset[$month][] = $result;
            $query_string = '';
        }

        return $dataset;
    }
}
