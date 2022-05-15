<?php $v->layout("_theme"); ?>

<div class="card card-round card-shadowed px-50 py-30 w-450px mb-0" style="max-width: 100%">
    <h5 class="text-uppercase fw-500">Bem-vindo! 👋 Simon</h5>
    <p><small>Faça login na sua conta e comece a aventurar</small></p>
    <div class="ajax_response"><?= flash(); ?></div>
    <form action="<?= url("/entrar"); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="username">E-mail</label>
            <input type="email" class="form-control" name="email" value="<?= ($cookie ?? null); ?>" placeholder="Informe seu e-mail:" required />
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" class="form-control" name="password" placeholder="Informe sua senha:" required />
        </div>

        <div class="form-group flexbox">
            <div class="custom-control custom-checkbox"></div>

            <a class="text-muted hover-info fs-13" href="<?= url("/recuperar"); ?>">Esqueceu a senha?</a>
        </div>

        <div class="form-group">
            <button class="btn btn-bold btn-block btn-info">Entrar</button>
        </div>
        <?= csrf_input(); ?>
    </form>
    <p class="text-center text-muted fs-13 mt-20">Não tem uma conta? <a class="text-info fw-500" href="#">Cadastre-se</a></p>
</div>