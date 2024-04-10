<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

?>
<h1 class="h3 mb-0 text-gray-800">Produtos</h1>
<a href="/panel/productadd">
    <button type="submit" class="btn btn-primary">Novo Produto</button>
</a>
<a href="/panel/complementadd">
    <button type="submit" class="btn btn-primary">Novo Complemento</button>
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
    <div class="modal-dialog" style="max-width: 800px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="user_panel">
                    <section class="product-photo-circle">
                        <img src="/layout/images/products/1.jpeg">
                    </section>
                    <section style="width: 40%">
                        <b><label>Cod:</label></b>
                        <span>X5A25</span><br>
                        <b><label>Medida:</label></b>
                        <span>KG</span><br>
                        <b><label>Categoria:</label></b>
                        <span>Comida</span><br>
                    </section>
                    <section style="width: 40%">
                        <b><label>Estoque Atual:</label></b>
                        <span>20</span><br>
                        <b><label>Estoque Minimo:</label></b>
                        <span>20</span><br>
                    </section>
                    <section style="width: 100%">
                        <b><label>Descrição:</label></b>
                        <span>20</span><br>
                    </section>

                    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#stockModal">Ajustar Estoque</button>
                </div>
                <br>

                <div>
                    <table border="1" width="100%">
                        <tr>
                            <th>#</th>
                            <th>Tamanho</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                        </tr>
                        <tr>
                            <td>#1</td>
                            <td>P</td>
                            <td>Serve 01 pessoa</td>
                            <td>R$ 20.00</td>
                        </tr>
                    </table>
                </div>
                <br>

                <div>
                    <table border="1" width="100%">
                        <tr>
                            <th>#</th>
                            <th>Adicional</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Desconto</th>
                            <th>Total</th>
                        </tr>
                        <tr>
                            <td>#1</td>
                            <td>Tomate</td>
                            <td>Tomate e Cebola</td>
                            <td>R$ 20.00</td>
                            <td>R$ 5.00</td>
                            <td>R$ 15.00</td>
                        </tr>
                    </table>
                </div>
                <br>

                <div>
                    <table border="1" width="100%">
                        <tr>
                            <th>#</th>
                            <th>Complemento</th>
                            <th>Descrição</th>
                        </tr>
                        <tr>
                            <td>#1</td>
                            <td>Tomate</td>
                            <td>Tomate e Cebola</td>
                        </tr>
                    </table>
                </div>
                <br>

                <div>
                    <table border="1" width="100%">
                        <tr>
                            <th>#</th>
                            <th>Pergunta</th>
                            <th>Multipla Resposta</th>
                            <th>Resposta Livre</th>
                            <th>Respostas</th>
                        </tr>
                        <tr>
                            <td>#1</td>
                            <td>O que é o que é, clara e salgada?</td>
                            <td>Sim</td>
                            <td>Não</td>
                            <td>Clara oxente, salgada oxente, clararasdad</td>
                        </tr>
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


<!-- Modal Stock -->
<div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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

                <div class="input-group">
                    <select class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                        <option selected>Escolha uma Ação...
                            <font color="red">*</font>
                        </option>
                        <option value="1">Entrada</option>
                        <option value="2">Saida</option>
                        <option value="3">Devolução</option>
                    </select>
                </div><br>

                <div class="input-group">
                    <span class="input-group-text">Quantidade
                        <font color="red">*</font>
                    </span>
                    <input type="text" class="form-control">
                </div><br>

                <div class="input-group">
                    <span class="input-group-text">Motivo:
                        <font color="red">*</font>
                    </span>
                    <input type="text" class="form-control">
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