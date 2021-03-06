<?php

namespace Source\App;

use DateTime;
use Dompdf\Dompdf;
use Source\Core\Controller;
use Source\Core\Session;
use Source\Models\Auth;
use Source\Models\AppClient;
use Source\Models\AppConference;
use Source\Models\AppConferenceItem;
use Source\Models\AppImportTemplate;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\User;
use Source\Support\Thumb;
use Source\Support\Upload;
use Source\Models\AppTemplateFile;
use Source\Support\PushNotification;

/**
 * Class App
 * @package Source\App
 */
class App extends Controller
{
    /** @var User */
    private $user;

    /** @var AppClient */
    private $client;

    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");

        if (!($this->user = Auth::user())) {
            $this->message->warning("Efetue login para acessar o APP.")->flash();
            redirect("/entrar");
        }

        (new Access())->report();
        (new Online())->report();

        //UNCONFIRMED EMAIL
        if ($this->user->status != "confirmed") {
            $session = new Session();
            if (!$session->has("appconfirmed")) {
                $this->message->info("IMPORTANTE: Acesse seu e-mail para confirmar seu cadastro e ativar todos os recursos.")->flash();
                $session->set("appconfirmed", true);
                (new Auth())->register($this->user);
            }
        }

        $this->client = Auth::client();
    }

    /**
     * APP HOME
     */
    public function home(): void
    {
        $date = new DateTime("now");
        $date->modify("last day of this month");

        $first_month_day = $date->format("Y-m-01");
        $last_month_day = $date->format("Y-m-t");

        $remessas = (new AppConference())->remessa($this->user);
        $count_remessas = (new AppConferenceItem())->find("user_id = :uid AND data_log BETWEEN '{$first_month_day}' AND '{$last_month_day}'", "uid={$this->user->id}")->count();
        $count_item_coletados = (new AppConferenceItem())->find("user_id = :uid AND status = 'coletado' AND data_log BETWEEN '{$first_month_day}' AND '{$last_month_day}'", "uid={$this->user->id}")->count();
        $count_item_abertos = (new AppConferenceItem())->find("user_id = :uid AND status = 'aberto' AND data_log BETWEEN '{$first_month_day}' AND '{$last_month_day}'", "uid={$this->user->id}")->count();

        echo $this->view->render("home", [
            "remessas" => $remessas,
            'dash' => [
                'encomendas' => $count_remessas,
                'coletado' => $count_item_coletados,
                'pendente' => $count_item_abertos,
                'estatistica' => [
                    'coletado' => (new AppConferenceItem())->find(null, null, queryCountMonthData('id', 'app_conference_item', 'data_log', 'coletado', date('Y')))->fetch(),
                    'pendente' => (new AppConferenceItem())->find(null, null, queryCountMonthData('id', 'app_conference_item', 'data_log', 'aberto', date('Y')))->fetch()
                ]
            ]
        ]);
    }

    public function objects_pending(?array $data): void
    {

        $first = (!empty($data['first_day'])) ? $data['first_day'] : first_last_day_of_the_month()->first_day;
        $last = (!empty($data['last_day'])) ? $data['last_day'] : first_last_day_of_the_month()->last_day;
        $itens_pending = (new AppConferenceItem())->find("data_log BETWEEN '{$first}' AND '{$last}' AND status = :status", "status=aberto")->fetch(true);

        echo $this->view->render("pages/itens_pending", [
            'itens' => $itens_pending
        ]);
    }

    public function item_cancel(?array $data)
    {
        echo $this->view->render("pages/itens_pending_form", []);
    }

    public function item_cancel_action(?array $data)
    {
        $itemCancel = (new AppConferenceItem())->findById($data['id']);
        if (!$itemCancel) {
            echo json_encode(["reload" => true, 'type' => 'error', 'message' => 'Voc?? tentou gerenciar um item que n??o existe']);
            return;
        }

        if (empty($data['observacoes_cancelamento'])) {
            echo json_encode(["reload" => true, 'type' => 'error', 'message' => 'Informe o motivo do cancelamento do objeto']);
            return;
        }

        $itemCancel->status = 'cancelado';
        $itemCancel->observacoes_cancelamento = $data['observacoes_cancelamento'];

        if (!$itemCancel->save()) {
            $json["message"] = $itemCancel->message()->render();
            echo json_encode(["reload" => true, 'type' => 'error', 'message' => $itemCancel->message()->render()]);
            return;
        }

        echo json_encode(["reload" => true, 'type' => 'success', 'message' => 'Objeto cancelado com sucesso...']);
        return;
    }

    public function item_transfer_action(?array $data)
    {

        if (empty($data['value'])) {
            echo json_encode(["reload" => true, 'type' => 'error', 'message' => 'Informe o numero da remessa para concluir a transferencia!']);
            return;
        }


        $itemTransfer = (new AppConferenceItem())->findById($data['id']);
        if (!$itemTransfer) {
            echo json_encode(["reload" => true, 'type' => 'error', 'message' => 'Voc?? tentou gerenciar um item que n??o existe']);
            return;
        }

        $remessUpdate = (new AppConference())->find("client_id = :cid AND remessa = :rms", "cid={$this->user->client_id}&rms={$data['value']}")->fetch();
        if (!$remessUpdate) {
            echo json_encode(["reload" => true, 'type' => 'error', 'message' => 'A remessa informada n??o existe ou foi excluida']);
            return;
        }

        if ($itemTransfer->remessa == $remessUpdate->remessa) {
            echo json_encode(["reload" => true, 'type' => 'error', 'message' => 'Esse objeto ja pertence a mesma remessa que esta tentando trasferir.']);
            return;
        }

        $itemTransfer->remessa = $data['value'];

        if (!$itemTransfer->save()) {
            $json["message"] = $itemTransfer->message()->render();
            echo json_encode(["reload" => true, 'type' => 'error', 'message' => $itemTransfer->message()->render()]);
            return;
        }

        echo json_encode(["reload" => true, 'type' => 'success', 'message' => 'Objeto transferido com sucesso...']);
        return;
    }

    public function import(?array $data): void
    {
        $head = $this->seo->render(
            "Ol?? {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $_SESSION['remessa_timestamp'] =  (!empty($data['remessa'])) ? $data['remessa'] : strtotime(date('YmdHis'));

        echo $this->view->render("importacao", [
            "head" => $head
        ]);
    }

    public function remessa(?array $data)
    {

        $remessa = (new AppConference())->getRemessa($data['remessa'], $this->user, false);
        if (!$remessa) {
            $this->message->info("Voc?? tentou acessa uma remessa que n??o existe")->flash();
            redirect("/app");
            exit;
        }
        $itens = (new AppConferenceItem())->itens($remessa);

        echo $this->view->render("remessa_itens", [
            "remessa" => $remessa,
            'nremessa' => $data['remessa'],
            'itens' => $itens
        ]);
    }

    public function remessa_finalizar(?array $data)
    {
        $remessa = (new AppConference())->getRemessa($data['remessa'], $this->user, false);
        if (!$remessa) {
            $json['result'] = false;
            $json["message"] = "Ops, Voc?? tentou acessa uma remessa que n??o existe";
            echo json_encode($json);
            exit;
        }

        $uploadRemessa = (new AppConference())->findById($remessa->id);
        $uploadRemessa->status = 'finalizado';

        if (!$uploadRemessa->save()) {
            $json['result'] = false;
            $json["message"] = $uploadRemessa->message()->render();
            echo json_encode($json);
            return;
        }
        $json['result'] = true;
        $json["message"] = "Remessa finalizada com sucesso.";
        echo json_encode($json);
    }

    public function remessa_print(?array $data)
    {

        $remessa = (new AppConference())->getRemessa($data['remessa'], $this->user, false);
        $itens = (new AppConferenceItem())->itens($remessa, 'coletado');

        $html = $this->view->render("print_remessa", [
            "remessas" => $remessa,
            'nremessa' => $data['remessa'],
            'itens' => $itens
        ]);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', TRUE);

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);


        $dompdf->render();

        header('Content-Type: application/pdf');
        echo $dompdf->output();
    }

    public function create_template(?array $data)
    {
        $head = $this->seo->render(
            "Ol?? {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        //TEMPLATE IMPORTACAO
        $temp_imports = (new AppTemplateFile())->find()->fetch(true);

        echo $this->view->render("template_importacao", [
            "head" => $head,
            'temp_imports' => $temp_imports
        ]);
    }

    public function upload(?array $data)
    {
        //upload photo
        if (!empty($_FILES["file"])) {

            $files = $_FILES["file"];
            usleep(1000000 / 5);

            $nomeArquivoOriginal = str_slug(mb_strtolower(pathinfo($files['name'])['filename']));
            $ext = mb_strtolower(pathinfo($files['name'])['extension']);
            $arq = $nomeArquivoOriginal . '.' . $ext;

            //criar um numero apra remessa
            $nremessa = $_SESSION['remessa_timestamp']; //strtotime(date('YmdHis'));

            $upload = new Upload();
            $file = $upload->file($files, $nomeArquivoOriginal);
            $fileName = 'storage/' . $file;

            //verifica se o arquivo ja foi processando.
            $confID = (new AppConference())->find('filename = :fl', "fl={$arq}")->fetch();
            if ($confID) {
                echo json_encode(['message' => 'O Arquivo carregado, j?? foi importadado anteriomente!']);
                return false;
            }

            //verifica se a remssa ja existe
            $confRemessaID = (new AppConference())->find('remessa = :rm', "rm={$nremessa}")->fetch();
            if (!$confRemessaID) {
                //adicionar uma nova remessa
                $conf = (new AppConference());
                $conf->status = 'aberto';
                $conf->filename = $arq;
                $conf->user_id = $this->user->id;
                $conf->client_id = $this->client->id;
                $conf->remessa = $nremessa;

                if (!$conf->save()) {
                    echo json_encode(['message' => 'Ops, Erro ao processar este arquivo, por favor tente novamente!']);
                    return false;
                }
            }

            //template de importa????os
            $tampleteImport = (new AppImportTemplate())->template($this->user);

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);
            $xls_data = $spreadsheet->getActiveSheet()->toArray();

            $nr = count($xls_data); //number of rows

            $error = 0;


            for ($i = 1; $i < $nr; $i++) {
                $npedido = $xls_data[$i][$tampleteImport->n_pedido];

                $conferenceID = (new AppConferenceItem())->find("n_pedido = :np", "np={$npedido}")->fetch();

                if (!$conferenceID) {
                    $conference = (new AppConferenceItem());

                    if (!empty($xls_data[$i][$tampleteImport->n_pedido])) {
                        $conference->user_id = $this->user->id;
                        $conference->remessa =  $nremessa;
                        $conference->nome = strtoupper($xls_data[$i][$tampleteImport->nome]);

                        $conference->documento = limpa_caracteres($xls_data[$i][$tampleteImport->documento]);

                        //$conference->email = $xls_data[$i][$tampleteImport->email];
                        //$conference->telefone = format_value($xls_data[$i][$tampleteImport->telefone]);
                        $conference->cep = format_value($xls_data[$i][$tampleteImport->cep]);
                        $conference->rua = strtoupper($xls_data[$i][$tampleteImport->rua]);
                        $conference->numero = $xls_data[$i][$tampleteImport->numero];
                        $conference->complemento = strtoupper($xls_data[$i][$tampleteImport->complemento]);
                        $conference->bairro = strtoupper($xls_data[$i][$tampleteImport->bairro]);
                        $conference->cidade = strtoupper($xls_data[$i][$tampleteImport->cidade]);
                        $conference->uf = strtoupper($xls_data[$i][$tampleteImport->uf]);
                        $conference->n_pedido = $xls_data[$i][$tampleteImport->n_pedido];

                        if (!$conference->save()) {
                            $error += 1;
                            continue;
                        }
                    } else {
                        $error += 1;
                        continue;
                    }
                } else {
                    $error += 1;
                    continue;
                }
            }

            $json["message"] = "Registros importado com sucesso";
            if ($error > 0) {
                $json["redirect"] = url("app/remessa/{$nremessa}");
                $json["message"] = "Alguns registro nao foi importado, {$error} n??o importado";
            }
            if ($error == 0) {
                $json["redirect"] = url("app/remessa/{$nremessa}");
            }

            /* (new PushNotification(
                'Nova remessa adicionada',
                "{$this->client->nome} adicionou uma nova remessa, por favor providencie a coleta."
            ))->run(); */

            echo json_encode($json);
        }
    }

    public function save_layout(?array $data)
    {
        $stdc = new AppImportTemplate();
        $stdc->nome_layout = $data['nome_layout'];
        $stdc->template_padrao = $data['layoutPadrao'];
        $stdc->tipo_layout = $data['tipo_layout'];
        $stdc->ignorar_primeira_linha = $data['ignorarPrimeiraLinha'];
        $stdc->user_id = $this->user->id;
        $stdc->client_id = $this->user->id;

        for ($i = 0; $i < count($data['camposDisponiveis']); $i++) {
            $stdc->{$data['camposDisponiveis'][$i]} = $i;
        }

        if (!$stdc->save()) {
            $json["message"] = $stdc->message()->render();
            echo json_encode($json);
            return;
        }

        $json["reload"] = true;
        $json["message"] = $this->message->success("Layout criado com sucesso!")->render();
        echo json_encode($json);
    }

    public function lista_layout()
    {
        $client_id = $this->client->id;
        $appImpTemp = (new AppImportTemplate())->find('client_id = :cid', "cid={$client_id}")->fetch(true);

        $html =  $this->view->render("lista_layout_importacao", [
            'appImpTemp' => $appImpTemp
        ]);

        echo $html;
    }

    public function delete_layout(?array $data)
    {
        $data = filter_var_array($data,  FILTER_SANITIZE_STRIPPED);
        $lt = (new AppImportTemplate())->findById($data["id"]);

        if (!$lt) {
            $this->message->error("Voc?? tentnou deletar um registro que n??o existe")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        $lt->destroy();

        $this->message->success("O registro foi exclu??do com sucesso...")->flash();
        echo json_encode(["reload" => true]);
    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function profile(?array $data): void
    {
        if (!empty($data["update"])) {
            list($d, $m, $y) = explode("/", $data["datebirth"]);
            $user = (new User())->findById($this->user->id);
            $user->first_name = $data["first_name"];
            $user->last_name = $data["last_name"];
            $user->genre = $data["genre"];
            $user->datebirth = "{$y}-{$m}-{$d}";
            $user->document = preg_replace("/[^0-9]/", "", $data["document"]);

            if (!empty($_FILES["photo"])) {
                $file = $_FILES["photo"];
                $upload = new Upload();

                if ($this->user->photo()) {
                    (new Thumb())->flush("storage/{$this->user->photo}");
                    $upload->remove("storage/{$this->user->photo}");
                }

                if (!$user->photo = $upload->image($file, "{$user->first_name} {$user->last_name} " . time(), 360)) {
                    $json["message"] = $upload->message()->before("Ooops {$this->user->first_name}! ")->after(".")->render();
                    echo json_encode($json);
                    return;
                }
            }

            if (!empty($data["password"])) {
                if (empty($data["password_re"]) || $data["password"] != $data["password_re"]) {
                    $json["message"] = $this->message->warning("Para alterar sua senha, informa e repita a nova senha!")->render();
                    echo json_encode($json);
                    return;
                }

                $user->password = $data["password"];
            }

            if (!$user->save()) {
                $json["message"] = $user->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Pronto {$this->user->first_name}. Seus dados foram atualizados com sucesso!")->render();
            echo json_encode($json);
            return;
        }

        echo $this->view->render("profile", [
            "user" => $this->user,
            "photo" => ($this->user->photo() ? image($this->user->photo, 360, 360) :
                theme("/assets/images/avatar.jpg", CONF_VIEW_APP))
        ]);
    }


    public function importa_via_api(?array $data)
    {
        $data = [];

        $data["PerfilVipp"]["Usuario"] = 'wscanaldsak';
        $data["PerfilVipp"]["Token"] = '';
        $data["PerfilVipp"]["IdPerfil"] = '951951';

        $data["ContratoEct"]["NrContrato"] = '';
        $data["ContratoEct"]["CodigoAdministrativo"] = '';
        $data["ContratoEct"]["NrCartao"] = '';

        $data["Remetente"]["CnpjCpf"] = '';
        $data["Remetente"]["IeRg"] = '';
        $data["Remetente"]["Nome"] = '';
        $data["Remetente"]["SegundaLinhaNome"] = '';
        $data["Remetente"]["Endereco"] = '';
        $data["Remetente"]["Numero"] = '';
        $data["Remetente"]["Complemento"] = '';
        $data["Remetente"]["Bairro"] = '';
        $data["Remetente"]["Cidade"] = '';
        $data["Remetente"]["UF"] = '';
        $data["Remetente"]["Cep"] = '';
        $data["Remetente"]["Telefone"] = '';
        $data["Remetente"]["Celular"] = '';
        $data["Remetente"]["Email"] = '';

        $data["Destinatario"]["CnpjCpf"] = '';
        $data["Destinatario"]["IeRg"] = '';
        $data["Destinatario"]["Nome"] = '';
        $data["Destinatario"]["SegundaLinhaDestinatario"] = '';
        $data["Destinatario"]["Numero"] = '';
        $data["Destinatario"]["Complemento"] = '';
        $data["Destinatario"]["Bairro"] = '';
        $data["Destinatario"]["Cidade"] = '';
        $data["Destinatario"]["UF"] = '';
        $data["Destinatario"]["Cep"] = '';
        $data["Destinatario"]["Telefone"] = '';
        $data["Destinatario"]["Celular"] = '';
        $data["Destinatario"]["Email"] = '';

        $data["Servico"]["ServicoECT"] = '';

        $data["NotasFiscais"]["DtNotaFiscal"] = '';
        $data["NotasFiscais"]["SerieNotaFiscal"] = '';
        $data["NotasFiscais"]["NrNotaFiscal"] = '';
        $data["NotasFiscais"]["VlrTotalNota"] = '';

        $data["Volumes"][] = [
            "Peso" => 0,
            "Largura" => 0,
            "Comprimento" => 0,
            "ContaLote" => 0,
            "ChaveRoteamento" => 0,
            "CodigoBarraVolume" => 0,
            "CodigoBarraCliente" => 0,
            "ObservacaoVisual" => 0,
            "ObservacaoQuatro" => 0,
            "ObservacaoCinco" => 0,
            "PosicaoVolume" => 0,
            "Conteudo" => 0,
            "DeclaracaoConteudo" => [
                "ItemConteudo" => [
                    [
                        "DescricaoConteudo" => "",
                        "Quantidade" => "",
                        "Valor" => ""
                    ]
                ],
                "DocumentoRemetente" => "",
                "DocumentoDestinatario" => "",
            ],
            "ValorDeclarado" => "",
            "AdicionaisVolume" => "",
            "VlrACobrar" => "",
            "Etiqueta" => ""
        ];

        echo json_encode($data);
    }

    /**
     * APP LOGOUT
     */
    public function logout(): void
    {
        $this->message->info("Voc?? saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/entrar");
    }

    public function statistic(?array $data)
    {

        $coletados = (new AppConferenceItem())->analysisDashMonth(1, 12, 'coletado');
        $pendentes = (new AppConferenceItem())->analysisDashMonth(1, 12, 'aberto');

        $date = new DateTime("now");
        $first_month_day = $date->format("Y-m-01");
        $last_month_day = $date->format("Y-m-t");

        if ($data) {
            $coletados = (new AppConferenceItem())->analysisDashMonth($data['vmonth'], $data['vmonth'], 'coletado');
            $pendentes = (new AppConferenceItem())->analysisDashMonth($data['vmonth'], $data['vmonth'], 'aberto');

            $first_month_day = $date->format("Y-{$data['vmonth']}-01");
            $last_month_day = $date->format("Y-{$data['vmonth']}-t");
        }

        $count_remessas = (new AppConferenceItem())->find("user_id = :uid AND data_log BETWEEN '{$first_month_day}' AND '{$last_month_day}'", "uid={$this->user->id}")->count();
        $count_item_coletados = (new AppConferenceItem())->find("user_id = :uid AND status = 'coletado' AND data_log BETWEEN '{$first_month_day}' AND '{$last_month_day}'", "uid={$this->user->id}")->count();
        $count_item_abertos = (new AppConferenceItem())->find("user_id = :uid AND status = 'aberto' AND data_log BETWEEN '{$first_month_day}' AND '{$last_month_day}'", "uid={$this->user->id}")->count();

        $dataset['coletados'] = $coletados;
        $dataset['pendentes'] = $pendentes;
        $dataset['count_remessas'] = $count_remessas;
        $dataset['count_item_abertos'] = $count_item_abertos;
        $dataset['count_item_coletados'] = $count_item_coletados;

        echo json_encode($dataset);
        return;
    }

    public function teste()
    {
        $month = "05";
        for ($i = 1; $i <= 31; $i++) {
            echo "(SELECT IFNULL(count(id), 0) as '" . str_pad($i, 2, '0', STR_PAD_LEFT) . "' FROM app_conference_item WHERE EXTRACT(day FROM data_log) = '" . str_pad($i, 2, '0', STR_PAD_LEFT) . "' AND EXTRACT(month FROM data_log) = {$month}) '" . str_pad($i, 2, '0', STR_PAD_LEFT) . "',<br>";
        }
    }
}
