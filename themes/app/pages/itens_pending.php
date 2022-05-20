<?php $v->layout("theme"); ?>


<div class="col-xl-12">
    <div class="card card-round shadow-material-1">
        <header class="card-header">
            <h3 class="card-title fw-500">Itens pendentes</h3>
        </header>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Nº Remessa</th>
                        <th>Nº Pedido</th>
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
                                <td><?php echo $remessa->remessa; ?></td>
                                <td><?php echo $remessa->n_pedido; ?></td>
                                <td><?php echo $remessa->status; ?></td>
                                <td class="text-right table-actions">
                                    <div class="dropdown table-action">
                                        <span class="dropdown-toggle no-caret hover-primary" data-toggle="dropdown"><i class="ti-more-alt rotate-90"></i></span>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" 
                                            href="#" 
                                            data-confirm-transference="true"
                                            data-action="<?= url('/app/transferir/item/' . $remessa->id) ?>" 
                                            data-id="<?php echo $remessa->id ?>"><i 
                                            class="ti-direction-alt"></i> Transferir</a>
                                            <a class="dropdown-item" 
                                            href="#" 
                                            data-type="cancel"
                                            data-title="Cancelar Item"
                                            data-action="<?= url('/app/cancelar/item/' . $remessa->id) ?>" 
                                            data-confirm-cancel="true"><i class="ti-close"></i> Cancelar</a>
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