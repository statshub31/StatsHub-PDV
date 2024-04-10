<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

?>
<h1 class="h3 mb-0 text-gray-800">Adicionais</h1>
<a href="/panel/complementadd">
    <button type="submit" class="btn btn-primary">Novo Adicional</button>
</a>
<hr>
<div class="input-group">
  <select class="custom-select" id="inputGroupSelect04">
    <option selected>-- Ação --</option>
    <option value="1">Remover</option>
    <option value="2">Promocionar</option>
    <option value="3">Montar Kit</option>
    <option value="3">Isentar de Taxa</option>
    <option value="3">Bloquear</option>
    <option value="3">Desbloquear</option>
  </select>
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button">Executar</button>
  </div>
</div>
<hr>
<link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Marcar</th>
            <th>Produto</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Marcar</th>
            <th>Produto</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Status</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td>
                <input type="checkbox">
            </td>
            <td>
                <section class="product_photo">
                    <img src="/../../../layout/images/products/1.jpeg"></img>
                </section>
                <label>Pizza 1</label>
            </td>
            <td>
                Sabor Calabresa
            </td>
            <td>R$ 20.00</td>
            <td>
                <div class="vc-toggle-container">
                    <label class="vc-switch">
                        <input type="checkbox" name="order-withdrawal" id="order-withdrawal" class="vc-switch-input">
                        <span data-on="Disp" data-off="Indis" class="vc-switch-label"></span>
                        <span class="vc-handle"></span>
                    </label>
                </div>
            </td>
            <td>
                <i class="fa fa-edit" aria-hidden="true"></i>
                <i class="fa fa-trash" aria-hidden="true" data-toggle="modal" data-target="#exampleModal"></i>
                <i class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#exampleModalLong"></i>
            </td>
        </tr>
    </tbody>
</table>

<!-- Modal View -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
    aria-hidden="true">
    <div class="modal-dialog" style="max-width: 600px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="user_panel">
                    <section id="user_photo">
                        <img src="/layout/images/users/1.jpg">
                    </section>
                    <section id="user_infos">
                        <b><label>Nome:</label></b>
                        <span>Thiago de Oliveira Lima</span><br>
                        <b><label>Email:</label></b>
                        <span>usuario@gmail.com</span><br>
                        <b><label>Celular:</label></b>
                        <span>00 00000 0000</span><br>
                    </section>
                </div>
                <br>

                <div id="user_address">
                    <table border="1" width="100%">
                        <tr>
                            <th>#</th>
                            <th>Endereço</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Av. Arcilio Federzoni, 399, Jardim Silvia, Francisco Morato-SP</td>
                        </tr>
                    </table>
                </div>
                <br>
                <div id="user_history">
                    <table class="table table-bordered" id="dataTableDeliverys" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Opções</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>#255</td>
                                <td>08/04/2024 10:30</td>
                                <td><i class="fa-solid fa-eye" data-toggle="modal" data-target="#exampleModal"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>

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