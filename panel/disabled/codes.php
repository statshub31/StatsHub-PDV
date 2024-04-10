<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));

?>
<h1 class="h3 mb-0 text-gray-800">Códigos</h1>
<a href="/admin/codegenerate">
    <button type="submit" class="btn btn-primary">Novos Codigo</button>
</a>
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
            <th>Criado em</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Criado em</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        $token = addToken('recoveryCode');
        $list = doAccountsCBCodeList();
        $count = 0;
        if ($list) {
            foreach ($list as $data) {
                ++$count;
                ?>
                <tr>
                    <td>
                        <?php echo $count; ?>
                    </td>
                    <td>
                        <?php echo date("d/m/Y H:i:s", strtotime($data['date_created'])) ?>
                    </td>
                    <td>
                        <form action="/admin/recoverycodes" method="post" target="_blank">

                            <input name="date" type="text" value="<?php echo $data['date_created'] ?>" hidden />
                            <input name="token" type="text" value="<?php echo $token ?>" hidden />
                            <button style="
            text-align: none !important;
            -webkit-appearance: none !important;
            border: none !important;
            color: #858796 !important;
            background-color: transparent !important;" type="submit"><i class="fa fa-eye"
                                    aria-hidden="true"></i></button>
                        </form>
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