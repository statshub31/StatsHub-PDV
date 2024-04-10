<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

?>
<h1 class="h3 mb-0 text-gray-800">Premios</h1>
<form action="/admin/addreward" method="post">
<input type="hidden" name="token" value="<?php echo addToken('newReward') ?>" />
<button type="submit" class="btn btn-primary">Novo Premio</button>
</form>
<hr>
<style>
    #raffle-img-container {
        width: 30px;
        margin-right: 10px;
    }

    #raffle-img-container img {
        width: 100%;
        height: 100%;
    }

    .tier {
        padding: 0px 7px;
        border: 1px solid #c9dfc9;
        border-radius: 5px;
        background-color: #119500;
        font-weight: bold;
        color: white;
    }
</style>
<link href="/front/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Level</th>
            <th>Preço</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Foto</th>
            <th>Nome Completo</th>
            <th>Telefone</th>
            <th>Preço</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        $list = doRewardsList();

        if ($list) {
            foreach ($list as $data) {
                $reward_id = $data['id'];
                $dir = '/front/images/rewards/';
                $format = getPathImageFormat($dir, getRewardPhoto($reward_id));
                ?>
                <tr>
                    <td>
                        <section id="raffle-img-container">
                            <img src="/../../../front/images/rewards/<?php echo getRewardPhoto($reward_id) ?>.<?php echo $format; ?>"></img>
                        </section>
                    </td>
                    <td>
                        <?php echo getRewardName($reward_id); ?>
                    </td>
                    <td>
                        <?php echo getRewardLevel($reward_id); ?>
                    </td>
                    <td>
                        R$ <?php echo getRewardPrice($reward_id); ?>
                    </td>
                    <td>
                        <div style="display: inline-block;">
                            <form action="/admin/updatereward" method="post" style="margin: 0;">
                                <input type="hidden" name="reward_id" value="<?php echo $reward_id ?>" />
                                <input type="hidden" name="token" value="<?php echo addToken('updateReward' . $reward_id) ?>" />
                                <button style="
            text-align: none !important;
            -webkit-appearance: none !important;
            border: none !important;
            color: #858796 !important;
            background-color: transparent !important;" type="submit"><i class="fa fa-edit"
                                        aria-hidden="true"></i></button>
                            </form>
                        </div>

                        <div style="display: inline-block;">
                            <form action="/admin/deletereward" method="post" style="margin: 0;">
                                <input type="hidden" name="reward_id" value="<?php echo $reward_id ?>" />
                                <input type="hidden" name="token" value="<?php echo addToken('deleteReward' . $reward_id) ?>" />
                                <button style="
            text-align: none !important;
            -webkit-appearance: none !important;
            border: none !important;
            color: #858796 !important;
            background-color: transparent !important;" type="submit"><i class="fa fa-trash"
                                        aria-hidden="true"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            "language": {
                "search": "Pesquisar:",
                "info": "Mostrando _START_ até _END_ de _TOTAL_ entradas",
                "lengthMenu": "Mostrar _MENU_",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Próximo"
                }
                // Outras opções de linguagem...
            }
        });
    });

</script>
</script>


<?php
include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>