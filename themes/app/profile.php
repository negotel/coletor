<?php $v->layout("theme"); ?>

<div class="col-lg-12">
    <form class="card card-round shadow-material-1" action="<?= url("/app/profile"); ?>" method="post">
        <div class="card-body">
            <h6 class="text-uppercase fw-500">Meus Dados</h6>
            <hr class="hr-sm mb-2">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Nome</label>
                        <input class="form-control" type="text" name="first_name" required value="<?= $user->first_name; ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Sobrenome</label>
                        <input class="form-control" type="text" name="last_name" required value="<?= $user->last_name; ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>CPF</label>
                        <input class="form-control" type="text" name="document" placeholder="Apenas números" required value="<?= $user->document; ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Nascimento</label>
                        <input class="form-control" type="date" name="datebirth" placeholder="dd/mm/yyyy" required value="<?= ($user->datebirth ? date_fmt($user->datebirth, "d/m/Y") : null); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Genero:</label>

                        <select name="genre" required class="form-control" data-provide="selectpicker">
                            <option value="">Selecione</option>
                            <option <?= ($user->genre == "male" ? "selected" : ""); ?> value="male">&ofcir; Masculino</option>
                            <option <?= ($user->genre == "female" ? "selected" : ""); ?> value="female">&ofcir; Feminino</option>
                            <option <?= ($user->genre == "other" ? "selected" : ""); ?> value="other">&ofcir; Outro</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Image perfil:</label>
                        <div class="rounded j_profile_image thumb" style="background-image: url('<?= $photo; ?>')"></div>
                        <div class="form-group file-group">
                            <input type="text" class="form-control file-value file-browser" placeholder="Escolha sua image..." readonly="">
                            <input data-image=".j_profile_image" type="file" name="photo">
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="text-uppercase mt-3 fw-500">INFORMAÇÃO DA CONTA</h6>
            <hr class="hr-sm mb-2">

            <div class="form-group">
                <label>E-mail</label>
                <input class="form-control" type="email" name="email" placeholder="Seu e-mail de acesso" readonly value="<?= $user->email; ?>" />
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Senha</label>
                        <input class="form-control" type="password" name="password" placeholder="Sua senha de acesso">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Repetir Senha</label>
                        <input class="form-control" type="password" name="password_re" placeholder="Sua senha de acesso">
                    </div>
                </div>
            </div>
        </div>

        <footer class="card-footer text-right">
            <button class="btn btn-primary" type="submit">Atualizar</button>
        </footer>
    </form>
</div>