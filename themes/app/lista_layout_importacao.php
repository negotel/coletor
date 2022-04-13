<?php if ($appImpTemp) : ?>
    <table class="table table-hover table-has-action">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Padrão</th>
                <th class="w-80px text-center">&nbsp;</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($appImpTemp as $r) : ?>
                <tr>
                    <td><?= $r->id ?></td>
                    <td><?= $r->nome_layout ?></td>
                    <td><?= $r->template_padrao ?></td>
                    <td class="text-right table-actions">
                        <a class="table-action hover-danger" data-action="<?= url('/app/importacao/excluir/layout') ?>" data-id="<?= $r->id ?>" id='excluir' href="#"><i class="ti-close"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
<?php else : ?>
    <tr>
        <td colspan="4" style="text-align: center">Quando você adicionar um novo layout de importação ele aparecerar aqui!</td>
    </tr>
<?php endif; ?>

<script>
    $(document).ready(function(e) {
        $('#excluir').click(function(e) {
            var clicked = $(this);
            var dataset = clicked.data();
            var load = $(".ajax_load");

            load.fadeIn(200).css("display", "flex");

            $.post(clicked.data("action"), dataset, function(response) {
                //reload by error
                if (response.reload) {
                    window.location.reload();
                }
            }, "json");
        })
    });
</script>