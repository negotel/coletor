<?php $v->layout("theme"); ?>

<div class="row">

    <div class="col-6 col-xl-3 cursor-pointer">
        <div class="card card-round shadow-material-1">
            <div class="card-body">
                <div class="text-center">
                    <span class="fa fa-cubes fs-30 fw-500"></span><br>
                    <span class="fs-10 text-muted">em <?=mes_extenso(date('m'))?></span>
                    <h5 class="fw-500">Encomendas</h5>
                    <span class="fs-30 fw-400"> <?=$dash['encomendas']?> </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 cursor-pointer">
        <div class="card card-round shadow-material-1">
            <div class="card-body">
                <div class="text-center">
                    <span class="fa fa-location-arrow fs-30 fw-500"></span><br>
                    <span class="fs-10 text-muted">em <?=mes_extenso(date('m'))?></span>
                    <h5 class="fw-500">Coletado</h5>
                    <span class="fs-30 fw-400"> <?=$dash['coletado']?> </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 cursor-pointer">
        <div class="card card-round shadow-material-1">
            <div class="card-body">
                <div class="text-center">
                    <span class="fa fa-check-square fs-30 fw-500"></span><br>
                    <span class="fs-10 text-muted">em <?=mes_extenso(date('m'))?></span>
                    <h5 class="fw-500">Postados</h5>
                    <span class="fs-30 fw-400"> 0 </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 cursor-pointer text-danger">
        <div class="card card-round shadow-material-1">
            <div class="card-body">
                <div class="text-center">
                    <span class="fa fa-history fs-30 fw-500 text-danger"></span><br>
                    <span class="fs-10 text-danger">em <?=mes_extenso(date('m'))?></span>
                    <h5 class="fw-500 text-danger">Pendentes</h5>
                    <span class="fs-30 fw-400 text-danger"> <?=$dash['pendente']?> </span>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card card-round shadow-material-1">
            <header class="card-header">
                <h3 class="card-title fw-500 text-uppercase">Lista de Remessas</h3>
                <div class="card-header-actions">
                    <a class="btn btn-dark" href="<?php echo url("/app/importacao"); ?>"> <i class="fa fa-upload"></i> </a>
                </div>
            </header>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Remessa</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>&nbsp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($remessas) : $i = 1; ?>
                            <?php foreach ($remessas as $remessa) : ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $remessa->remessa; ?></td>
                                    <td><?php echo $remessa->status; ?></td>
                                    <td><?php echo $remessa->data_log; ?></td>
                                    <td class="text-right table-actions">
                                        <a class="table-action hover-primary" data-url-print="<?= url('/app/remessa/print/' . $remessa->remessa) ?>" data-action="<?= url('/app/remessa/finalizar/' . $remessa->remessa) ?>" data-modal-confirm="true" href="#"><i class="ti-printer"></i></a>
                                        <div class="dropdown table-action">
                                            <span class="dropdown-toggle no-caret hover-primary" data-toggle="dropdown"><i class="ti-more-alt rotate-90"></i></span>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="<?php echo url("/app/remessa/{$remessa->remessa}"); ?>"><i class="ti-menu-alt"></i> Detalhes</a>
                                                <a class="dropdown-item" href="<?php echo url("/app/remessa/adicionar/{$remessa->remessa}"); ?>"><i class="ti-clip"></i> Adicionar Objeto</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td>Quando adicionar uma nova remessa aparecerar aqui!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- <?= $paginator; ?> -->
            </div>
        </div>
    </div>
</div>