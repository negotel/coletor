<?php $v->layout("theme"); ?>


<div class="col-xl-12">
    <div class="card card-round shadow-material-1">
        <header class="card-header">
            <h3 class="card-title fw-500">Importação de Arquivos</h3>
            <div class="card-header-actions">
                <a class="btn btn-dark" id="listLayoutImportacao" href="#"> <i class="fa fa-bars"></i> </a>
                <a class="btn btn-dark" id="novoLayoutImportacao" href="<?php echo url("/app/criar/template/importacao"); ?>"> <i class="fa fa-archive"></i> </a>
            </div>
        </header>
        <div class="card-body">
            <div class="col-xl-12  card card-round" id="dropzoneHTML">
                <div class="response_message"></div>
                <div class="dropzone svelte-12uhhij dz-clickable">
                    <div class="dz-message svelte-12uhhij">
                        <h1 class="svelte-12uhhij">Carrega Arquivo!</h1>
                        <p>Arraste e solte arquivos aqui</p>
                        <p class="comment svelte-12uhhij">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>