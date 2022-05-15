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

    <aside class="sidebar sidebar-color-info sidebar-icons-right sidebar-icons-boxed sidebar-expand-lg">

        <header class="sidebar-header bg-info">
            <span class="logo">
                Erc - Sistema
            </span>
        </header>

        <nav class="sidebar-navigation ps-container ps-theme-default" data-ps-id="1a7e4651-4c13-64d6-0ed8-a601513f55f9">

            <ul class="menu menu-lg menu-bordery">
                <li class="menu-category">Preview</li>
                <li class="menu-item active">
                    <a class="menu-link" href="<?= url('/app') ?>">
                        <span class="icon ti-home"></span>
                        <span class="title">
                            <span>Dashboard</span>
                        </span>
                    </a>
                </li>
            </ul>
            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
            </div>
            <div class="ps-scrollbar-y-rail" style="top: 0px; right: 2px;">
                <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
            </div>
        </nav>

    </aside>
    <!-- Topbar -->

    <header class="topbar">
        <div class="topbar-left">
            <span class="topbar-btn sidebar-toggler"><i>&#9776;</i></span>

            <div class="topbar-divider d-none d-md-block"></div>

            <div class="lookup d-none d-md-block topbar-search">
                <input class="form-control w-300px" type="text">
                <div class="lookup-placeholder">
                    <i class="ti-search"></i>
                    <span><strong>Pesquise</strong> Número do pedido, Nome destinatário.</span>
                </div>
            </div>
        </div>

        <div class="topbar-right">

            <ul class="topbar-btns">
                <li class="dropdown">
                    <span class="topbar-btn" data-toggle="dropdown">
                        <?php if (user()->photo()) : ?>
                            <img class="avatar" src="<?= image(user()->photo, 260, 260); ?>" />
                        <?php else : ?>
                            <img class="avatar" src="<?= theme("assets/img/avatar/1.jpg", CONF_VIEW_APP) ?>" alt="...">
                        <?php endif; ?>
                    </span>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?= url('/app/perfil') ?>"><i class="ti-user"></i> Profile</a>
                        <a class="dropdown-item" href="#">
                            <div class="flexbox">
                                <i class="ti-email"></i>
                                <span class="flex-grow">Inbox</span>
                                <span class="badge badge-pill badge-info">5</span>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= url('/app/sair') ?>"><i class="ti-power-off"></i> Logout</a>
                    </div>
                </li>

                <!-- Notifications -->
                <li class="dropdown d-none d-md-block">
                    <span class="topbar-btn has-new" data-toggle="dropdown"><i class="ti-bell"></i></span>
                </li>
                <!-- END Notifications -->
            </ul>
        </div>
    </header>
    <!-- END Topbar -->

    <!-- Main container -->
    <main class="main-container">
        <div class="main-content">
            <div class="container">
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
                                <a class="nav-link" href="#">Contato</a>
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
    </script>

</body>

</html>