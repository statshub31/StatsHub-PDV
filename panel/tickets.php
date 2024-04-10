<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

?>
<h1 class="h3 mb-0 text-gray-800">Cupons</h1>
<a href="/panel/ticketadd">
    <button type="submit" class="btn btn-primary">Novo Cupom</button>
</a>
<hr>
<link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Código</th>
            <th>Quantidade</th>
            <th>Desconto</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Código</th>
            <th>Quantidade</th>
            <th>Desconto</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td>CUPOM25
            </td>
            <td>200</td>
            <td>R$ 20.00</td>
            <td>
                <label>10</label> de 200
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 5%;" aria-valuenow="5"
                        aria-valuemin="0" aria-valuemax="100">5%</div>
                </div>
            </td>
            <td>
                <i class="fa fa-trash" aria-hidden="true" data-toggle="modal" data-target="#exampleModal"></i>
            </td>
        </tr>
    </tbody>
</table>

<!-- Modal Remove -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Você está prestes a desativar o usuário <b>[Thiago de Oliveira Lima]</b>, você tem certeza disso?

                <div class="alert alert-danger" role="alert">
                    Confirmando está ação, o usuário não poderá fazer login ou executar qualquer tarefa.
                </div>


                Você está prestes a ativar o usuário <b>[Thiago de Oliveira Lima]</b>, você tem certeza disso?

                <div class="alert alert-warning" role="alert">
                    Confirmando está ação, o usuário voltará a fazer login e executar tarefas de sua função.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success">Confirmar</button>
            </div>
        </div>
    </div>
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

        $('#dataTableDeliverys').DataTable({
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
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>