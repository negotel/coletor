<?php $v->layout("theme"); ?>


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
                                    <a class="table-action hover-primary"data-url-print="<?= url('/app/remessa/print/' . $remessa->remessa) ?>" data-action="<?= url('/app/remessa/finalizar/' . $remessa->remessa) ?>" data-modal-confirm="true" href="#"><i class="ti-printer"></i></a>
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