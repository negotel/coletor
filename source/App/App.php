<?php

namespace Source\App;

use Dompdf\Dompdf;
use Source\Core\Controller;
use Source\Core\Session;
use Source\Models\Auth;
use Source\Models\CafeApp\AppClient;
use Source\Models\CafeApp\AppConference;
use Source\Models\CafeApp\AppConferenceItem;
use Source\Models\CafeApp\AppImportTemplate;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\User;
use Source\Support\Thumb;
use Source\Support\Upload;
use Source\Models\CafeApp\AppTemplateFile;
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
        $head = $this->seo->render(
            "Olá {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        //REMESSA
        $remessas = (new AppConference())->remessa($this->user);

        echo $this->view->render("home", [
            "head" => $head,
            "remessas" => $remessas
        ]);
    }

    public function import(?array $data): void
    {
        $head = $this->seo->render(
            "Olá {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
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
        $head = $this->seo->render(
            "Olá {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $remessa = (new AppConference())->getRemessa($data['remessa'], $this->user, false);
        if (!$remessa) {
            $this->message->info("Você tentou acessa uma remessa que não existe")->flash();
            redirect("/app");
            exit;
        }
        $itens = (new AppConferenceItem())->itens($remessa);

        echo $this->view->render("remessa_itens", [
            "head" => $head,
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
            $json["message"] = "Ops, Você tentou acessa uma remessa que não existe";
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
            "Olá {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
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
                echo json_encode(['message' => 'O Arquivo carregado, já foi importadado anteriomente!']);
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

            //template de importaçãos
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
                $json["message"] = "Alguns registro nao foi importado, {$error} não importado";
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
            $this->message->error("Você tentnou deletar um registro que não existe")->flash();
            echo json_encode(["reload" => true]);
            return;
        }

        $lt->destroy();

        $this->message->success("O registro foi excluído com sucesso...")->flash();
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

        $head = $this->seo->render(
            "Meu perfil - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("profile", [
            "head" => $head,
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
        $this->message->info("Você saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/entrar");
    }
}
