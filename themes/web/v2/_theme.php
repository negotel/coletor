<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Entrar - ERCSistemas</title>

    <!-- Styles -->
    <link href="<?= theme("assets/css/core.min.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme("assets/css/app.min.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme("assets/css/style.css", CONF_VIEW_APP) ?>" rel="stylesheet">
    <link href="<?= theme("assets/css/jquery.toast.css", CONF_VIEW_APP) ?>" rel="stylesheet">


    <!-- Favicons -->
    <link rel="apple-touch-icon" href="../../assets/img/apple-touch-icon.png">
    <link rel="icon" href="../../assets/img/favicon.png">
    <style>

    </style>
</head>

<body class="min-h-fullscreen bg-img center-vh p-20" style="background-image: url(<?= theme("assets/images/bg3.jpg") ?>);" data-overlay="5">

    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>

    <?= $v->section("content"); ?>

    <!-- Scripts -->
    <script src="<?= theme("assets/js/core.min.js", CONF_VIEW_APP) ?>" data-provide="sweetalert chartjs"></script>
    <script src="<?= theme("assets/js/app.min.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/js/script.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/vendor/jqueryui/jquery-ui.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/js/jquery.form.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("assets/js/jquery.toast.js", CONF_VIEW_APP) ?>"></script>

    <script src="<?= theme("/assets/scripts.js"); ?>"></script>
    <?= $v->section("scripts"); ?>

</body>

</html>