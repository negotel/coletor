<?php $v->layout("_theme"); ?>

<div class="card card-round card-shadowed px-50 py-30 w-450px mb-0" style="max-width: 100%">
    <h5 class="text-uppercase">Esqueceu sua senha? 🔒</h5>
    <p><small>Digite seu e-mail e enviaremos instruções para redefinir sua senha</small></p>
    <form>
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="text" class="form-control" id="email">
        </div>

        <br>
        <button class="btn btn-bold btn-block btn-info" type="submit">Enviar</button>
    </form>
    <p class="text-center text-muted fs-13 mt-20">Lembrei minha senha! <a class="text-info fw-500" href="<?= url("/"); ?>">Logar-se</a></p>
</div>