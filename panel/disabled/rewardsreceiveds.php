<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
?>


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
<div id="table">
    <link href="/front/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Premio</th>
                <th>Cliente</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Premio</th>
                <th>Cliente</th>
                <th>Opções</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $listReward = doRewardsCheckList();
            $token = addToken('confirmReceived');
            if ($listReward) {
                foreach ($listReward as $data) {
                    $reward_id = getRewardCheckRewardID($data['id']);
                    $user_id = getRewardCheckUserID($data['id']);
                    $team_id = getRewardCheckTeamID($data['id']);
                    $dir = '/front/images/rewards/';
                    $format = getPathImageFormat($dir, getRewardPhoto($reward_id));
                    ?>
                    <tr>
                        <td>
                            <section id="raffle-img-container">
                                <img
                                    src="/../../../front/images/rewards/<?php echo getRewardPhoto($reward_id) ?>.<?php echo $format; ?>"></img>
                            </section>
                        </td>
                        <td>
                            <?php echo getRewardName($reward_id); ?>
                        </td>
                        <td>
                            <?php echo getUsersCName($user_id) ?>
                        </td>
                        <td>
                            <?php
                            if (getRewardCheckStatus($data['id']) === 'received') {
                                ?>
                                Produto entregue pelo membro [<?php echo getUsersCName($team_id) ?>] no dia [<?php echo date("d/m/Y", strtotime(getRewardCheckDateReceived($data['id'])))?>].
                            <?php
                            } else {
                                ?>

                                <button class="btn-success" type="button" data-toggle="modal"
                                    data-target="#rewardsReceivedModal<?php echo $data['id']; ?>">Entregar</button>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="rewardsReceivedModal<?php echo $data['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="rewardsReceivedModal<?php echo $data['id']; ?>Label"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rewardsReceivedModal<?php echo $data['id']; ?>Label">Confirmar a entrega</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/admin/registerpurchase" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="inputlname">Informe o codigo de premiação:</label>
                                            <input type="text" name="code" class="form-control" id="inputlname">
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <input type="" name="check_id" value="<?php echo $data['id'] ?>" />
                                        <input type="hidden" name="token" value="<?php echo $token ?>" />
                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                </form>
                            </div>
                        </div>
                    </div>
        </div>
        <?php
                }
            }
            ?>
</tbody>
</table>
</div>

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

    function toggleTable() {
        var tableDiv = document.getElementById('table');
        tableDiv.style.display = (tableDiv.style.display === 'block') ? 'none' : 'block';
    }
</script>
<?php
include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>