<?php

namespace Source\App\Api;

use Source\Core\Controller;
use Source\Models\CafeApp\AppConference;
use Source\Models\CafeApp\AppConferenceItem;
use Source\Models\CafeApp\AppConferenceLog;
use Source\Models\CafeApp\AppPushNotificationRegistration;
use Source\Support\PushNotification;

/**
 * Class Api
 * @package Source\App\Api
 */
class Api extends Controller
{
    /** @var \Source\Models\User|null */
    protected $user;

    /** @var array|false */
    protected $headers;

    /** @var array|null */
    protected $response;

    /**
     * CafeApi constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct("/");
        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = getallheaders();
    }

    /** API APP */
    public function validar_remessa(?array $data)
    {

        $remessa = (new AppConference())->find("remessa = :r", "r={$data['remessa']}")->fetch(false);

        $results = ["results" => false, 'message' => 'Remessa não encontrada, por favor verifique se a remessa digitada esta correta.'];;
        if ($remessa) {
            $results = ["results" => true, 'message' => 'Remessa encontrada, agora você ja pode começar a conferencia da remessa solicitada.'];
        }

        $this->back($results);
    }

    public function get_data_remessa(?array $data)
    {

        $remessas = (new AppConference())->join()->find('app_conference.status = :st', 'st=aberto', "app_conference.*, concat(users.first_name,' ', users.last_name) as cliente, count()")->order('app_conference.data_log DESC')->fetch(true);
        $results = ['result' => false, 'data' => null, 'total' => 0];

        if ($remessas) {
            foreach ($remessas as $remessa) {

                $results['data'][] = array_merge(
                    (array)$remessa->data(),
                    [
                        'total_item' => (new AppConferenceItem())->countItem($remessa),
                        'total_item_coletado' => (new AppConferenceItem())->countItemRead($remessa)
                    ]
                );

                if ($remessa->status == 'aberto') {
                    $results['total'] += 1;
                }
                $results['result'] = true;
            }
        }

        $this->back($results);
    }

    public function get_data_remessa_item(?array $data)
    {
        $remessas = (new AppConferenceItem())->find("remessa = :r", "r={$data['remessa']}", "id, status, remessa, nome, n_pedido")->order("status")->fetch(true);

        $results = [
            'result' => false,
            'data' => [
                'abertos' => null,
                'coletados' => null
            ],
            'total' => 0,
            'totalColetado' => 0,
            'nRemessa' => null,
            'message' => null
        ];

        if ($remessas) {
            foreach ($remessas as $remessa) {
                //$results['data'][] = $remessa->data();

                if ($remessa->status == 'aberto') {
                    $results['total'] += 1;
                    $results['data']['abertos'][] = [
                        "id" => $remessa->id,
                        "status" => $remessa->status,
                        "remessa" =>  $remessa->remessa,
                        "nome" =>  $remessa->nome,
                        "n_pedido" =>  $remessa->n_pedido,
                        "data_coleta" => ''
                    ];
                }

                if ($remessa->status == 'coletado') {
                    $results['totalColetado'] += 1;

                    $data_coleta = (new AppConferenceLog())->find('n_pedido = :np', "np={$remessa->n_pedido}")->fetch();

                    $results['data']['coletados'][] = [
                        "id" => $remessa->id,
                        "status" => $remessa->status,
                        "remessa" =>  $remessa->remessa,
                        "nome" =>  $remessa->nome,
                        "n_pedido" =>  $remessa->n_pedido,
                        "data_coleta" => $data_coleta->data_log
                    ];
                }


                $results['result'] = true;
            }
            $results['nRemessa'] = $data['remessa'];
        }

        $this->back($results);
    }

    public function coleta_remessa(?array $data)
    {
        $remessas = (new AppConferenceItem())->find("n_pedido = :r", "r={$data['n_pedido']}")->fetch();

        if ($remessas) {
            if ($remessas->status == 'aberto') {

                //muda para aberto
                $coletor = (new AppConferenceItem())->findById($remessas->id);
                $coletor->status = 'coletado';
                $coletor->save();

                $rem = (new AppConferenceItem())->find("remessa = :r", "r={$remessas->remessa}", "id, status, remessa, nome, n_pedido")->order("status")->fetch(true);

                $results = [
                    'result' => false,
                    'data' => [
                        'abertos' => null,
                        'coletados' => null
                    ],
                    'total' => 0,
                    'totalColetado' => 0,
                    'nRemessa' => null,
                    'message' => null
                ];

                if ($rem) {

                    foreach ($rem as $remessa) {
                        //$results['data'][] = $remessa->data();

                        if ($remessa->status == 'aberto') {
                            $results['total'] += 1;
                            $results['data']['abertos'][] = $remessa->data();
                        }

                        if ($remessa->status == 'coletado') {
                            $results['totalColetado'] += 1;
                            $results['data']['coletados'][] = $remessa->data();
                        }

                        $results['result'] = true;
                    }
                    $results['nRemessa'] = $remessas->remessa;
                }

                $results['message'] = 'Objeto coletado com sucesso';

                $this->back($results);
            }

            if ($remessas->status == 'coletado') {
                //muda para coletado
                $this->back([
                    'result' => false,
                    'message' => 'este objeto ja foi coletado!'
                ]);
            }
            $total_coletado = (new AppConferenceLog())->find('remessa')->count('remessa');
            
            if ($total_coletado == 0) {
                (new PushNotification(
                    'Coleta Iniciada',
                    "O operador iniciou a coleta no cliente."
                ))->run();
            }else{
                (new PushNotification(
                    'Coleta Iniciada',
                    "O operador iniciou a coleta no cliente."
                ))->run();
            }

            (new AppConferenceLog())->log(['user_id' => 1, 'n_pedido' => $data['n_pedido'], 'remessa' => $remessas->remessa]);
        } else {
            $this->back([
                'result' => false,
                'message' => 'Objeto não encontrado!'
            ]);
        }
    }

    public function save_device(?array $data)
    {
        $pushGetToken = (new AppPushNotificationRegistration())->findTokenPushNotification($data['pushNotificationID']);
        if (!$pushGetToken) {
            $push = new AppPushNotificationRegistration();
            $push->token_push_notification = $data['pushNotificationID'];
            $push->status = 'ativo';

            $push->save();
            $results['message'] = 'ok';

            $this->back($results);
            return true;
        }
        return false;
    }

    public function push()
    {

        $chave_push = [];

        $chaves = (new AppPushNotificationRegistration())->getData();
        foreach ($chaves as $chave) {
            array_push($chave_push,  $chave->token_push_notification);
        }

        (new PushNotification(
            'Nova remessa adicionada',
            "Teste"
        ))->run();
    }

    /**
     * @param int $code
     * @param string|null $type
     * @param string|null $message
     * @param string $rule
     * @return Api
     */
    protected function call(int $code, string $type = null, string $message = null, string $rule = "errors"): Api
    {
        http_response_code($code);

        if (!empty($type)) {
            $this->response = [
                $rule => [
                    "type" => $type,
                    "message" => (!empty($message) ? $message : null)
                ]
            ];
        }
        return $this;
    }

    /**
     * @param array|null $response
     * @return Api
     */
    protected function back(array $response = null): Api
    {
        if (!empty($response)) {
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return $this;
    }
}
