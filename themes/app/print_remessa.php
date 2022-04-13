
<!DOCTYPE html>
<html lang="pt-Br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REMESSA - <?= $nremessa; ?></title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        .tcontent th {
            background: rgb(66, 230, 149);
            background: linear-gradient(90deg, rgba(66, 230, 149, 1) 0%, rgba(59, 178, 184, 1) 50%, rgba(66, 230, 149, 1) 100%);
            color: white;
        }

        th,
        td {
            padding: 2px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #e8e8e8;
        }

        tr:hover:nth-child(1n + 2) {
            background-color: #085F63;
            color: #fff;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <th>
                REMESSA<br>
                <small>ConfControl</small>
            </th>
            <th style="text-align: right; font-size: 18px;">
                <?= $nremessa; ?><br>
                <small style="font-size: 12px; text-weight: normal"><?= date_fmt(date('Y-m-d H:i'), 'd.m.Y H:i') ?></small>
            </th>
        </tr>
    </table>
    <br><br><br>
    <table>
        <tr class="tcontent">
            <th class="count">&nbsp;</th>
            <th style="text-align: left; font-size: 10px" class="nome">NOME</th>
            <th style="text-align: left; font-size: 10px" class="numero">N PEDIDO</th>
            <th style="text-align: left; font-size: 10px" class="status">STATUS</th>
        </tr>
        <?php if ($itens) : ?>
            <?php 
                $total = 0;
                foreach ($itens as $remessa) : 
            ?>
                <tr style="border: 1px solid #000">
                    <td style="text-align: left; font-size: 10px" class="count"><?php echo $remessa->id; ?></td>
                    <td style="text-align: left; font-size: 10px" class="nome"><b><?php echo $remessa->nome; ?></b></td>
                    <td style="text-align: left; font-size: 10px" class="numero"><?php echo $remessa->n_pedido; ?></td>
                    <td style="text-align: left; font-size: 10px" class="status"><?php echo $remessa->status; ?></td>
                </tr>
            <?php 
                $total += 1; 
                endforeach; 
            ?>
            <tr>
                <td colspan="3"></td>
                <td style="text-align: right;"><b>Total Coletado: </b> <b><?=$total?></b></td>
            </tr>
        <?php else : ?>
            <tr>
                <td>Quando adicionar uma nova remessa aparecerar aqui!</td>
            </tr>
        <?php endif; ?>
    </table>
    <div class="row" style="text-align: center; font-size: 10px; margin-top: 25px">
        <h3>
            Assinatura do Conferente:<br><br>
            <small style="font-size: 14px; font-weight: 100; margin-top: 15px;">___________________________________________________________</small>
        </h3>
    </div>
</body>

</html>