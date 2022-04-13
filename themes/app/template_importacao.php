<?php $v->layout("theme"); ?>

<div class="row">
    <div class="col-xl-12">
        <div class="callout callout-danger" role="alert">
            <h5>Atenção</h5>
            <p>
                Escolha os campos na ordem que estiver na sua planilha.<br>
                lembre-se que a planilha deverá SEMPRE seguir esta sequência,<br>
                sem falhas. campos em branco devem ser mantidos em seus lugares.
            </p>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="card row card-round shadow-material-1">
        <div class="card-body">
            <div class="col-xl-12">
                <select class="form-control" id='cmbCamposDisponiveis' multiple='multiple'>
                    <?php foreach ($temp_imports as $layou) : ?>
                        <option value='<?php echo $layou->label; ?>'><?php echo $layou->titulo; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-xl-12">
                <div class="form-group">
                    <label>Nome do modelo:</label>
                    <input type="text" name="txtNomeModelo" class="form-control form-control-lg" id="divNomeModelo" placeholder="dê um nome para seu novo modelo de layout.">
                </div>
            </div>
            <div class="col-xl-12">
                <div class="form-group" id="divLayoutPadrao">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-checkbox custom-control-info">
                            <input type="checkbox" name="layoutPadrao" class="custom-control-input" id="cc-1">
                            <label class="custom-control-label fs-16" for="cc-1">Modelo padrão?</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">

            </div>
            <input type="hidden" name="tipo_layout" id="tipo_layout" value="csv">
            <div class="row mt-3 text-right">
                <div class="col-xl-12">
                    <button class="mt-3 btn btn-w-sm btn-flat btn-secondary" data-dismiss="modal" data-perform="cancel">Cancel</button>
                    <button class='mt-3 btn btn-info' onclick="SalvarModeloDeImportacao()"> Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>