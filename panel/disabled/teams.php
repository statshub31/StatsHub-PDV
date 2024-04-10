<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));
getMasterAdminAccess();

?>

<div style="white-space: nowrap;">
    <h1 class="h3 mb-4 text-gray-800" style="display: inline-block;">Equipe</h1>
    <a href="/admin/users"><button type="submit" class="btn btn-primary">Promover Usuário</button></a>
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
            <th>Foto</th>
            <th>Nome Completo</th>
            <th>Cargo</th>
            <th>Criado</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Foto</th>
            <th>Nome Completo</th>
            <th>Cargo</th>
            <th>Criado</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        $list = doAccountsTeamList();

        if ($list) {
            foreach ($list as $data) {
                $account_id = $data["id"];
                $user_id = getUsersIDByAccountID($data["id"]);
                $dir = '/front/images/users/';
                $format = getPathImageFormat($dir, $user_id);

                ?>
                <tr>
                    <td>
                        <section id="raffle-img-container">
                            <img src="/../../../front/images/users/<?php echo $user_id ?>.<?php echo $format; ?>"></img>
                        </section>
                    </td>
                    <td>
                        <?php echo getUsersCName($user_id); ?>
                    </td>
                    <td>
                        <?php echo getGroupsTitle(getAccountsGroupID($account_id)); ?>
                    </td>
                    <td>
                        <?php echo date("d/m/Y", strtotime(getAccountsCreated($account_id))); ?>
                    </td>
                    <td>
                        <div style="display: inline-block;">
                            <form action="/admin/teamsrequests" method="post" style="margin: 0;">
                                <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
                                <input type="hidden" name="token" value="<?php echo addToken('teamEditRequest' . $user_id) ?>" />
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
                            <form action="/admin/teamsrequests" method="post" style="margin: 0;">
                                <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
                                <input type="hidden" name="token" value="<?php echo addToken('teamDeleteRequest' . $user_id) ?>" />
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