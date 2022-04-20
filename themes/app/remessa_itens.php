<?php $v->layout("theme"); ?>


<div class="col-xl-12">
    <div class="card card-round shadow-material-1">
        <header class="card-header">
            <h3 class="card-title fw-500">Detalhes da Remessa [<?php echo $nremessa; ?>]</h3>
            <div class="card-header-actions">
                <a class="btn btn-dark" data-url-print="<?= url('/app/remessa/print/' . $nremessa) ?>" data-action="<?= url('/app/remessa/finalizar/' . $nremessa) ?>" data-modal-confirm="true" href="#"> <i class="ti-printer"></i> </a>
                <a class="btn btn-info" href="<?php echo url("/app/remessa/adicionar/{$nremessa}"); ?>"> <i class="ti-upload"></i> </a>
            </div>
        </header>
        <div class="card-body">
            <table class="table" data-provide="datatables">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Endereco</th>
                        <th>Numero</th>
                        <th>Status</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($itens) : ?>
                        <?php foreach ($itens as $remessa) : ?>
                            <tr <?= $remessa->status == 'coletado' ? 'class="bg-pale-success"' : '' ?>>
                                <td scope="row"><?php echo $remessa->id; ?></td>
                                <td><b><?php echo $remessa->nome; ?></b></td>
                                <td>
                                    <p class="lh-1">
                                        <?php echo str_limit_chars("{$remessa->rua}, {$remessa->numero} {$remessa->complemento}", 50, '...'); ?><br>
                                        <small><?php echo "{$remessa->bairro}, {$remessa->cidade}-{$remessa->uf}"; ?></small>
                                    </p>
                                </td>
                                <td><?php echo $remessa->n_pedido; ?></td>
                                <td><?php echo $remessa->status; ?></td>
                                <td class="text-right table-actions">
                                    <a class="table-action hover-danger" href="#"><i class="ti-trash"></i></a>
                                    <div class="dropdown table-action">
                                        <span class="dropdown-toggle no-caret hover-primary" data-toggle="dropdown"><i class="ti-more-alt rotate-90"></i></span>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="<?php echo url("/app/remessa/{$remessa->remessa}"); ?>"><i class="ti-menu-alt"></i> Detalhes</a>
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