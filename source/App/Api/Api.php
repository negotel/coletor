<?php

namespace Source\App\Api;

use Source\Core\Controller;
use Source\Models\AppConference;
use Source\Models\AppConferenceItem;
use Source\Models\AppConferenceLog;
use Source\Models\AppControlVersionAppMobile;
use Source\Models\AppPushNotificationRegistration;
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

        $remessas = (new AppConference())->join()->find('app_conference.status = :st', "st={$data['type']}", "app_conference.*, concat(users.first_name,' ', users.last_name) as cliente, count()")->order('app_conference.data_log DESC')->fetch(true);
        $results = ['result' => false, 'data' => null, 'total' => 0, 'total_finalizado' => 0];

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

            $results['total_finalizado'] = (new AppConference())->join()->find('app_conference.status = :st', 'st=finalizado')->count();
        }

        $this->back($results);
    }

    public function get_data_remessa_item(?array $data)
    {

        $remessas = (new AppConferenceItem())->find("remessa = :r", "r={$data['remessa']}", "id, status, remessa, nome, n_pedido")->order("status")->fetch(true);

        $results = [
            'result' => false,
            'data' => null,
            'total' => 0,
            'totalColetado' => 0,
            'nRemessa' => null,
            'message' => null
        ];

        if ($remessas) {
            foreach ($remessas as $remessa) {

                if ($remessa->status == 'aberto') {
                    $results['total'] += 1;

                    $results['data'][] = [
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
                }


                $results['result'] = true;
            }
            $results['nRemessa'] = $data['remessa'];
        }

        $this->back($results);
    }

    public function get_objetos_coletado(?array $data)
    {

        $data_coleta = (new AppConferenceLog())
            ->join()
            ->find(
                'app_conference_log.remessa = :np AND app_conference_item.`status`="coletado"',
                "np={$data['remessa']}",
                "app_conference_item.id, app_conference_item.`status`,
                app_conference_item.remessa,
                app_conference_item.nome,
                app_conference_item.n_pedido, 
                app_conference_item.data_log, 
                app_conference_log.data_log as data_coletado"
            )
            ->order('app_conference_log.data_log DESC')
            ->fetch(true);


        $results = [
            'result' => false,
            'data' => null,
            'message' => null
        ];


        if ($data_coleta) {
            foreach ($data_coleta as $remessa) {

                $results['data'][] = [
                    "id" => $remessa->id,
                    "status" => $remessa->status,
                    "remessa" =>  $remessa->remessa,
                    "nome" =>  $remessa->nome,
                    "n_pedido" =>  $remessa->n_pedido,
                    "data_coleta" => $remessa->data_coletado
                ];
            }
            $results['result'] = true;
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

        /*  $chave_push = [];

        $chaves = (new AppPushNotificationRegistration())->getData();
        foreach ($chaves as $chave) {
            array_push($chave_push,  $chave->token_push_notification);
        }

        (new PushNotification(
            'Nova remessa adicionada',
            "Teste"
        ))->run(); */
    }

    public function send_notification_update(){
        (new PushNotification(
            "Uma nova atualização",
            "Atualização disponivel, você pode instalar a nova versão clicando nessa notificação."
        ))->run();
    }

    public function notification_send(?array $data)
    {
        $start_exec = date('d.m.Y H:i:s');
        $conference = (new AppConference())->join()->find("notification = :notification", "notification={$data['type']}")->fetch(true);

        $result = [];

        $result['message'] = "Nenhuma notificações encontrada.";
        if ($conference) {
            foreach ($conference as $conferenc) {
                $conference_item = (new AppConferenceItem())->countItem($conferenc);

                $complement_msg = "{$conference_item} item";
                if ($conference_item > 1) {
                    $complement_msg = "{$conference_item} itens";
                }

                /* $result["data"][] = [
                    "cliente" => $conferenc->nomeClient,
                    "total_item_adicionado" => $conference_item,
                    "titulo_notificacao" => "{$conferenc->nomeClient} adicionou nova remessa",
                    "corpo_notificacao" => "Remessa {$conferenc->remessa} contem {$complement_msg}, por favor providencie a coleta."
                ]; */

                (new PushNotification(
                    "{$conferenc->nomeClient} adicionou nova remessa",
                    "Remessa {$conferenc->remessa} contem {$complement_msg}, por favor providencie a coleta."
                ))->run();

                $update_conference = (new AppConference())->findById($conferenc->id);
                $update_conference->notification = 'sim';
                $update_conference->save();
            }
            $result['message'] = "Notificações enviada com sucesso";
        }

        $fim_exc = date('d.m.Y H:i:s');

        file_put_contents('../logs.txt', "[--INICIO--] => {$start_exec} | [--FIM--] => {$fim_exc}  | {$result['message']}\n", FILE_APPEND);
        $this->back($result);
    }

    public function check_update_version(?array $data)
    {
        $new_version_app_mobile = (new AppControlVersionAppMobile())->getNewVersion();
        $this->back([
            "description" => $new_version_app_mobile->description,
            "version_number" => $new_version_app_mobile->version_number,
            "url_download" => $new_version_app_mobile->url_download,
            "status" => $new_version_app_mobile->status,
            "create_at" => $new_version_app_mobile->create_at,
        ]);
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
