<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="mit" content="2022-01-05T00:25:19-03:00+187065">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Entrar - ERCSistemas</title>
    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png"); ?>" />
    <link rel="stylesheet" href="<?= theme("/assets/style.css"); ?>" />
</head>

<body>

    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>

    <!--HEADER-->
    <header class="main_header gradient gradient-green">
        <div class="container">
            <div class="main_header_logo">
                <h1><a class="icon-coffee transition" title="Home" href="<?= url(); ?>">Conf<b>Control</b></a></h1>
            </div>
        </div>
    </header>

    <!--CONTENT-->
    <main class="main_content">
        <?= $v->section("content"); ?>
    </main>

    <?php if ($v->section("optout")) : ?>
        <?= $v->section("optout"); ?>
    <?php else : ?>
        <article class="footer_optout">
            <div class="footer_optout_content content">
                <span class="icon icon-coffee icon-notext"></span>
                <h2>Comece a controlar suas encomenda</h2>
                <p>É rápido, simples e gratuito!</p>
                <a href="<?= url("/cadastrar"); ?>" class="footer_optout_btn gradient gradient-green gradient-hover radius icon-check-square-o">Quero
                    controlar</a>
            </div>
        </article>
    <?php endif; ?>
    <script src="<?= theme("/assets/scripts.js"); ?>"></script>
    <?= $v->section("scripts"); ?>

</body>

</html>