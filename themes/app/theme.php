<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>ConfControl - ERCSistemas</title>

    <!-- Styles -->
    <link href="<?= theme("assets/css/core.min.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme("assets/css/app.min.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme("assets/css/style.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme("assets/css/jquery.toast.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme("assets/vendor/jqueryui/jquery-ui.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme("assets/vendor/dropzone/min/dropzone.min.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme('assets/css/multi-select.css', CONF_VIEW_APP) ?>" rel="stylesheet">


    <!-- Favicons -->
    <link rel="apple-touch-icon" href="../../assets/img/apple-touch-icon.png">
    <link rel="icon" href="../../assets/img/favicon.png">
    <style>

    </style>
</head>

<body class="topbar-unfix">

    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>

    <!-- Topbar -->
    <header class="topbar topbar-expand-lg topbar-secondary topbar-inverse">
        <div class="container">
            <div class="topbar-left">
                <span class="topbar-btn topbar-menu-toggler"><i>&#9776;</i></span>

                <div class="topbar-brand">
                    <h2 class="text-white fw-500">ConfControl</h2>
                </div>

                <div class="topbar-divider d-none d-md-block"></div>

                <nav class="topbar-navigation">
                    <ul class="menu">


                        <li class="menu-item <?= (isset($menu) && $menu == 'home') ? 'active' : '' ?>">
                            <a class="menu-link" href="<?= url('app') ?>">
                                <span class="icon ti-home"></span>
                                <span class="title">Dashboard</span>
                            </a>
                        </li>
                        <!-- 

                        <li class="menu-item <?= (isset($menu) && $menu == 'cadastros') ? 'active' : '' ?>">
                            <a class="menu-link" href="#">
                                <span class="icon ti-layout"></span>
                                <span class="title">Cadastros</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="menu-submenu">
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= url("/app/vendedor") ?>">
                                        <span class="title">Vendedor</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= url("/app/cliente") ?>">
                                        <span class="title">Clientes</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= url("/app/milhar") ?>">
                                        <span class="title">Milhar</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= url("/app/premio") ?>">
                                        <span class="title">Premio</span>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                    </ul>
                </nav>
            </div>
            <div class="topbar-right">

                <ul class="topbar-btns">
                    <li class="dropdown">
                        <span class="topbar-btn" data-toggle="dropdown"><img class="avatar" src="<?= theme("assets/img/avatar/1.jpg", CONF_VIEW_APP) ?>" alt="..."></span>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i class="ti-user"></i> Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= url("/app/sair"); ?>"><i class="ti-power-off"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- END Topbar -->

    <!-- Main container -->
    <main class="main-container">
        <div class="main-content">
            <div class="container">

                <div class="col-xl-12">
                    <?= flash(); ?>
                </div>
                <?= $v->section("content"); ?>
            </div>
        </div>
        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-center text-sm-left"><strong>ErcSistema - </strong> Todos os direitos reservados</p>
                    </div>

                    <div class="col-md-6">
                        <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Ajuda</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Sobre Nos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </main>
    <!-- Scripts -->
    <script src="<?= theme("assets/js/core.min.js", CONF_VIEW_APP) ?>" data-provide="sweetalert chartjs"></script>
    <script src="<?= theme("assets/js/app.min.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/js/script.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/vendor/jqueryui/jquery-ui.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/js/jquery.form.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/js/jquery.toast.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/vendor/dropzone/min/dropzone.min.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/vendor/bootstrap3-editable/js/bootstrap-editable.min.js", CONF_VIEW_APP) ?>"></script>

    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        app.ready(function() {

            ready_statistic();

            var myDropzone = new Dropzone(".dropzone", {
                url: "<?= url("app/processar/arquivo") ?>",
                paramName: "file",
                acceptedFiles: ".xlsx,.xls,.csv,.txt",
                addRemoveLinks: true,
                init: function() {
                    var load = $(".ajax_load");
                    var _this = this;

                    var flashClass = "ajax_response";
                    var flash = $("." + flashClass);

                    // Set up any event handlers
                    this.on("sending", function(file, xhr, formData) {
                        $(".response_message").html("");
                        load.fadeIn(200).css("display", "flex");
                        formData.append("remessa", '<?= $_SESSION['remessa_timestamp'] ?>');
                        //location.reload();
                    });

                    this.on("success", function(file, xhr) {
                        let response = JSON.parse(xhr)
                        this.removeFile(this.files[0]);

                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }

                        var message = "<div class='message alert alert-success icon-warning'>" + response.message + "</div>";
                        $(".response_message").html(message).fadeIn(100);
                        load.fadeOut(200);
                    });

                    this.on("error", function(file, xhr) {
                        var message = "<div class='message alert alert-danger icon-warning'>Erro ao fazer upload desse arquivo...</div>";
                        $(".response_message").html(message).fadeIn(100);
                        this.removeFile(this.files[0]);
                        load.fadeOut(200);
                    });
                }
            });
        });
    </script>

    <script src="<?= theme("assets/js/jquery.multi-select.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/js/jquery.quicksearch.js", CONF_VIEW_APP) ?>"></script>
    <script type="application/javascript">
        jQuery(document).ready(function() {

            jQuery('#cmbCamposDisponiveis').multiSelect({
                keepOrder: true,
                showFinishButtonAlways: true,
                selectableHeader: "<input type='text' class='form-control mb-3' autocomplete='off' placeholder='Procurar'>",
                selectionHeader: "<input type='text' class='form-control mb-3' autocomplete='off' placeholder='Procurar'>",
                selectableFooter: "<button class=' mt-3 btn btn-pure btn-primary btn-flat' id='select-all'> Selecionar Todos</button>",
                selectionFooter: "<button class='mt-3 btn btn-pure btn-primary btn-flat' id='deselect-all'> Desmarcar Todos</button>",
                cssClass: "col-xl-6 mt-1",
                afterInit: function(ms) {
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                        .on('keydown', function(e) {
                            if (e.which === 40) {
                                that.$selectableUl.focus();
                                return false;
                            }
                        });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                        .on('keydown', function(e) {
                            if (e.which == 40) {
                                that.$selectionUl.focus();
                                return false;
                            }
                        });
                },
                afterSelect: function(e) {
                    this.qs1.cache();
                    this.qs2.cache();
                    TmpCamposOcultos.push(e[0]);
                },
                afterDeselect: function(e) {
                    this.qs1.cache();
                    this.qs2.cache();
                    var tmparr = [];
                    for (var i = 0; i < TmpCamposOcultos.length; i++) {
                        if (TmpCamposOcultos[i] !== e[0]) {
                            tmparr.push(TmpCamposOcultos[i]);
                        }
                    }
                    TmpCamposOcultos = tmparr;
                }
            });

            InicializarCamposForm();

            jQuery('#select-all').click(function() {
                $('#cmbCamposDisponiveis').multiSelect('deselect_all');
                $('#cmbCamposDisponiveis').multiSelect('select_all');
                TmpCamposOcultos = ["StsSel", "IdxUni", "StsReg"];
                jQuery('#cmbCamposDisponiveis option:selected').each(function() {
                    TmpCamposOcultos.push($(this).val());
                });
                return false;
            });

            //se clicar em limpar ----------------------------------------------------------------------------------------------
            jQuery('#deselect-all').click(function() {
                $('#cmbCamposDisponiveis').multiSelect('deselect_all');
                TmpCamposOcultos = [];
                return false;
            });

            function InicializarCamposForm() {
                TmpCamposOcultos = [];
                //jQuery('#ModalNovoModelo').modal('hide');
            }

        });

        function SalvarModeloDeImportacao() {

            var load = $(".ajax_load");
            var nom = jQuery.trim(jQuery('#divNomeModelo').val());
            var tipoLayout = jQuery.trim(jQuery('#tipo_layout').val());

            //pelo menos 6 campos
            if (TmpCamposOcultos.length < 8) {
                toast('error', 'você deve selecionar no minimo 8 campos');
                return;
            }

            jQuery('#divCamposDisponiveis').removeClass('alert-info alert-danger alert-success').addClass('alert-success');

            //pelo menos 6 caracteres no nome
            if (nom.length < 6) {
                toast('error', 'o nome do modelo de layout deve ter no minimo 6 caracteres');
                jQuery('#divNomeModeloForm').removeClass('b-1').addClass('has-error has-danger');
                jQuery('#divNomeModelo').removeClass('b-1').addClass('is-invalid');
                return;
            }

            jQuery('#divNomeModeloForm').removeClass('has-error has-danger');
            jQuery('#divNomeModelo').removeClass('is-invalid').addClass('is-valid');

            //salvar o modelo finalmente
            $.ajax({
                type: "POST",
                url: '<?php echo url('/app/importacao/salva-layout') ?>',
                data: {
                    cmbModeloImportacao: '0',
                    nome_layout: nom,
                    camposDisponiveis: TmpCamposOcultos,
                    tipo_layout: tipoLayout,
                    layoutPadrao: (jQuery('input[name=layoutPadrao]').is(':checked')) ? 'sim' : 'nao',
                    ignorarPrimeiraLinha: (jQuery('input[name=chkIgnorarPrimeiraLinha]').is(':checked')) ? 'sim' : 'nao'
                },
                dataType: 'JSON',
                beforeSend: function() {
                    load.fadeIn(200).css("display", "flex");
                },
                success: function(data) {

                    toast('success', data.message);
                    //reload
                    if (data.reload) {
                        window.location.reload();
                    } else {
                        load.fadeOut(200);
                    }

                }
            });
        }

        //abrir um modal padrao do navegado
        function abrirModal(URL, w = 600, h = 800) {
            window.open(URL, '_black', 'height=' + h + ', width=' + w + ', left=' + (window.innerWidth - w) / 2 + ', top=' + (window.innerHeight - h) / 2);
        }

        //menssagens toast
        function toast(type, message) {
            $.toast({
                text: message, // Text that is to be shown in the toast
                icon: type, // Type of toast icon
                showHideTransition: 'slide', // fade, slide or plain
                allowToastClose: true, // Boolean value true or false
                hideAfter: 3000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
                stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
                position: 'top-center', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
                textAlign: 'left', // Text alignment i.e. left, right or center
                loader: true, // Whether to show loader or not. True by default
                loaderBg: '#926dde', // Background color of the toast loader
            });
        }

        $('#listLayoutImportacao').on('click', function() {
            app.modaler({
                url: '<?= url("/app/lista/layout") ?>',
                type: 'top',
                size: 'sm',
                title: 'Layout de importação',
                cancelVisible: false,
                confirmVisible: false,
                footerVisible: false
            });
        });

        function ready_statistic() {

            let labels = [];
            let datasets = [];
            let dataset = [];

            $.ajax({
                url: "<?= url("/app/dashboard") ?>",
                type: "POST",
                dataType: "json",
                beforeSend: function() {
                    $(".ajax_load")
                        .fadeIn(200)
                        .css("display", "flex")
                        .find(".ajax_load_box_title")
                        .text("Aguarde, carregando dashboard...");
                },
                success: function(response) {
                    let valores_coletados = [];
                    let valores_pendentes = [];
                    let dataset = [];

                    for (let i in response['coletados'][<?= date('m') ?>][0]) {
                        valores_coletados.push(response['coletados'][<?= date('m') ?>][0][i]);
                    }

                    for (let i in response['pendentes'][<?= date('m') ?>][0]) {
                        valores_pendentes.push(response['pendentes'][<?= date('m') ?>][0][i]);
                    }

    
                    new Chart($("#chart-line-5"), {
                        type: 'bar',
                        data: {
                            labels: [01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
                            datasets: [{
                                label: "Coletados",
                                fill: false,
                                borderWidth: 3,
                                pointRadius: 4,
                                borderColor: "#36a2eb",
                                backgroundColor: "#36a2eb",
                                pointBackgroundColor: "#36a2eb",
                                pointBorderColor: "#36a2eb",
                                pointHoverBackgroundColor: "#fff",
                                pointHoverBorderColor: "#36a2eb",
                                data: valores_coletados
                            }, {
                                label: "Pedentes",
                                fill: false,
                                borderWidth: 3,
                                pointRadius: 4,
                                borderColor: "#ff6384",
                                backgroundColor: "#ff6384",
                                pointBackgroundColor: "#ff6384",
                                pointBorderColor: "#ff6384",
                                pointHoverBackgroundColor: "#fff",
                                pointHoverBorderColor: "#ff6384",
                                data: valores_pendentes
                            }]
                        },
                        options: {}
                    });

                    $(".ajax_load").fadeOut(200);

                },
                error: function() {
                    $(".ajax_load").fadeOut();
                }
            });
        }
    </script>

</body>

</html>