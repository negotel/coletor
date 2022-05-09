<?php $v->layout("theme"); ?>

<div class="row">

    <div class="col-6 col-xl-3 cursor-pointer">
        <div class="card card-round shadow-material-1">
            <div class="card-body">
                <div class="text-center">
                    <span class="fa fa-cubes fs-30 fw-500"></span><br>
                    <span class="fs-10 text-muted">em <?= mes_extenso(date('m')) ?></span>
                    <h5 class="fw-500">Encomendas</h5>
                    <span class="fs-30 fw-400"> <?= $dash['encomendas'] ?> </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 cursor-pointer text-info">
        <div class="card card-round shadow-material-1">
            <div class="card-body">
                <div class="text-center">
                    <span class="fa fa-location-arrow fs-30 fw-500"></span><br>
                    <span class="fs-10 text-info">em <?= mes_extenso(date('m')) ?></span>
                    <h5 class="fw-500 text-info">Coletado</h5>
                    <span class="fs-30 fw-400 text-info"> <?= $dash['coletado'] ?> </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 cursor-pointer text-success">
        <div class="card card-round shadow-material-1">
            <div class="card-body">
                <div class="text-center">
                    <span class="fa fa-check-square fs-30 fw-500"></span><br>
                    <span class="fs-10  text-success">em <?= mes_extenso(date('m')) ?></span>
                    <h5 class="fw-500 text-success">Postados</h5>
                    <span class="fs-30 fw-400 text-success"> 0 </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3 cursor-pointer text-danger">
        <div class="card card-round shadow-material-1">
            <div class="card-body">
                <div class="text-center">
                    <span class="fa fa-history fs-30 fw-500 text-danger"></span><br>
                    <span class="fs-10 text-danger">em <?= mes_extenso(date('m')) ?></span>
                    <h5 class="fw-500 text-danger">Pendentes</h5>
                    <span class="fs-30 fw-400 text-danger"> <?= $dash['pendente'] ?> </span>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card card-round shadow-material-1">
            <header class="card-header">
                <h4 class="card-title fw-500">Estatística</h4>
                <nav>
                    <ul class="pagination pagination-info no-gutters">
                        <li class="page-item active"><a class="page-link" href="#"><?=date('Y')?></a></li>
                        <?php for ($range = 1; $range <= 3; $range++):
                            $dateRange = date("Y", strtotime(date("Y-m-01") . "-{$range}Year"));
                        ?>
                        <li class="page-item"><a class="page-link" href="#"><?=$dateRange?></a></li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </header>


            <div class="card-body">
                <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                    <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                        <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                    </div>
                    <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                        <div style="position:absolute;width:100%;height:100%;left:0; top:0"></div>
                    </div>
                </div>
                <canvas id="chart-line-4" width="577" height="180" class="chartjs-render-monitor" style="display: block; width: 577px; height: 360px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="card card-round shadow-material-1">
            <header class="card-header">
                <h3 class="card-title fw-500">Lista de Remessas</h3>
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

            </div>
        </div>
    </div>
</div>