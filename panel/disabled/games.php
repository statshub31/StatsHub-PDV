<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();
?>
<h1 class="h3 mb-0 text-gray-800">Jogos</h1>
<div>
    <a href="/admin/addgame">
        <button type="submit" class="btn btn-primary">Novo Jogo</button>
    </a>
</div>
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
            <th>#</th>
            <th>Título</th>
            <th>Criado por</th>
            <th>Criado em</th>
            <th>Status</th>
            <th>Meta</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Título</th>
            <th>Criado por</th>
            <th>Criado em</th>
            <th>Status</th>
            <th>Meta</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        $token = addToken('recoveryCode');
        $list = doGamesList();
        $count = 0;
        if ($list) {
            foreach ($list as $data) {
                $game_id = $data['id'];
                
                if (getGameTypeID($game_id) == 1) {
                    $game_count = doGamesScratchCardsCountRewardsAll($game_id);
                    $calc = calcularPercentual($game_count, doGamesScratchCardsCountOpenAll($game_id));
                }
                if (getGameTypeID($game_id) == 2) {
                    $game_count = doGamesTelesenaCountRewardsAll($game_id);
                    $calc = calcularPercentual($game_count, doGamesTelesenaCountOpenAll($game_id));
                }

                ++$count;
                ?>
                <tr>
                    <td>
                        <?php echo $count; ?>
                    </td>
                    <td>
                        <?php echo getGameTitle($game_id); ?>
                    </td>
                    <td>
                        <?php echo getUsersCName(getGameTeamID($game_id)); ?>
                    </td>
                    <td>
                        <?php echo date("d/m/Y", strtotime(getGameCreated($game_id))) ?>
                    </td>
                    <td>
                        <?php
                        echo getGameStatusTitle(getGameStatusID($game_id));

                        if (getGameStatusID($game_id) == 2) {
                            echo ' em ' . date("d/m/Y", strtotime(getGameCloseDate($game_id)));
                        }
                        ?>
                    </td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped" role="progressbar"
                                style="width: <?php echo $calc ?>%" aria-valuenow="<?php echo $calc ?>" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                        <?php echo round($calc,2) ?>% de
                        <?php echo $game_count; ?>
                    </td>
                    <td>

                        <div style="display: inline-block;">
                            <form action="/admin/viewgame" method="post">
                                <input name="game_id" type="text" value="<?php echo $game_id ?>" hidden />
                                <button style="
            text-align: none !important;
            -webkit-appearance: none !important;
            border: none !important;
            color: #858796 !important;
            background-color: transparent !important;" type="submit"><i class="fa fa-eye"
                                        aria-hidden="true"></i></button>
                            </form>
                        </div>
                        <?php
                        if (getGameStatusID($game_id) == 1) {
                            ?>

                            <div style="display: inline-block;">
                                <form action="/admin/deletegame" method="post" style="margin: 0;">
                                    <input type="hidden" name="game_id" value="<?php echo $game_id ?>" />
                                    <input type="hidden" name="token" value="<?php echo addToken('deletegame' . $game_id) ?>" />
                                    <button style="
            text-align: none !important;
            -webkit-appearance: none !important;
            border: none !important;
            color: #858796 !important;
            background-color: transparent !important;" type="submit"><i class="fa fa-trash"
                                            aria-hidden="true"></i></button>
                                </form>
                            </div>

                            <div style="display: inline-block;">
                                <form action="/admin/closegame" method="post" style="margin: 0;">
                                    <input type="hidden" name="game_id" value="<?php echo $game_id ?>" />
                                    <input type="hidden" name="token" value="<?php echo addToken('closeGame' . $game_id) ?>" />
                                    <button style="
            text-align: none !important;
            -webkit-appearance: none !important;
            border: none !important;
            color: #858796 !important;
            background-color: transparent !important;" type="submit"><i class="fa fa-close"
                                            aria-hidden="true"></i></button>
                                </form>
                            </div>
                            <?php
                        }
                        ?>
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


<?php
include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>