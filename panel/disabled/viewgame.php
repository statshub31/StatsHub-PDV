<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getAdminAccess();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_id = $_POST['game_id'];

    if (isset($_POST['reward_id']) && getToken('altergame' . $_POST['reward_id'])) {

        if (getGameStatusID($game_id) == 1) {
            if (getGameRewardBlockedStatus($_POST['reward_id'])) {
                $import = array(
                    'blocked' => 0,
                );
                $status = 'desbloqueado';
            } else {
                $import = array(
                    'blocked' => 1,
                );
                $status = 'bloqueado';
            }

            doUpdateGamesRewardsRow($_POST['reward_id'], $import);
            echo alertSuccess("O serviço foi " . $status . " com sucesso!!");
        }
    }
    ?>
    <div>
        <style>
            #raffle-img-container {
                width: 30px;
                margin-right: 10px;
            }

            #raffle-img-container img {
                width: 100%;
                height: 100%;
            }
        </style>

        <a href="/admin/games">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
        </a><br><br>
        <!-- <td scope="col">< ?php echo  ?> </td> -->

        <div id="div1" style="display:block;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Imagem</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Status</th>
                        <th scope="col">Encontrado(s)</th>
                        <th scope="col">Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $listRewards = doGamesRewardsList($game_id);
                    $count = 0;
                    if ($listRewards) {
                        foreach ($listRewards as $data) {
                            $reward_id = $data['id'];
                            $dir = '/front/images/games/';
                            $format = getPathImageFormat($dir, getGameRewardPhoto($reward_id));
                            ++$count;

                            if (getGameTypeID($game_id) == 1) {
                                $game_count = doGamesScratchCardsCountRewards($game_id, $reward_id);
                                $calc = calcularPercentual($game_count, doGamesScratchCardsCountOpen($game_id, $reward_id));
                            }
                            if (getGameTypeID($game_id) == 2) {
                                $game_count = doGamesTelesenaCountRewards($game_id, $reward_id);
                                $calc = calcularPercentual($game_count, doGamesTelesenaCountOpen($game_id, $reward_id));
                            }
                            ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $count ?>
                                </th>
                                <td scope="col">
                                    <div style="display: flex;align-content: center;align-items: center;">
                                        <section id="raffle-img-container">
                                            <img
                                                src="/../../../front/images/games/<?php echo getGameRewardPhoto($reward_id) ?>.<?php echo $format; ?>"></img>
                                        </section>
                                    </div>
                                    <label>
                                        <?php echo getGameRewardTitle($reward_id); ?>
                                    </label>
                                </td>
                                <td scope="col">
                                    <?php echo getGameRewardPrice($reward_id); ?>
                                </td>
                                <td scope="col">
                                    <?php echo (getGameRewardBlockedStatus($reward_id)) ? 'Bloqueado' : 'Desbloqueado'; ?>
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: <?php echo $calc ?>%" aria-valuenow="<?php echo $calc ?>"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <?php echo $calc ?>% de
                                    <?php echo $game_count; ?>
                                </td>
                                <td>
                                    <?php
                                    if (getGameStatusID($game_id) == 1) {
                                        ?>
                                        <div style="display: inline-block;">
                                            <form action="/admin/viewgame" method="post">
                                                <input name="game_id" type="text" value="<?php echo $game_id ?>" hidden />
                                                <input name="reward_id" type="text" value="<?php echo $reward_id ?>" hidden />
                                                <input type="hidden" name="token"
                                                    value="<?php echo addToken('altergame' . $reward_id) ?>" />
                                                <button style="
            text-align: none !important;
            -webkit-appearance: none !important;
            border: none !important;
            color: #858796 !important;
            background-color: transparent !important;" type="submit"><i class="fa-solid fa-rotate"
                                                        aria-hidden="true"></i></button>
                                            </form>
                                        </div>
                                    <?php }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <?php
} else {
    header('Location: /admin/games', true, 302);
    exit; // Certifique-se de encerrar a execução do script após o redirecionamento
}
include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>