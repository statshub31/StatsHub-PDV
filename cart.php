<?php
include_once __DIR__ . '/layout/php/header.php';
?>

<link href="/front/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td>
                <div class="cart-product">
                    <section class="product-photo">
                        <img src="/layout/images/products/1.jpeg">
                    </section>
                    <section class="product-name">
                        <label>Produto 1</label>
                    </section>
                </div>
            </td>
            <td>
                Descrição do Produto:<br>
                <small>Observações</small><br>
                <div class="list-quantity">
                    <button class="btn btn-sm btn-secondary decrease">-</button>
                    <input type="number" class="form-control quantity" value="1">
                    <button class="btn btn-sm btn-secondary increase">+</button>
                </div>
                <b>
                    <label class="v">R$ 10.00</label>
                </b>
            </td>
        </tr>
    </tbody>
</table>
<hr>
<div>
    <section id="address">
        <div>Av. Arcilio Federzoni, 399, Jardim Silvia, Francisco Morato-SP</div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addressModal">Alterar</button>
    </section>
    <hr>
    <section id="ticket">
        <div>XTY210
            <small>R$ -25,00</small>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ticketModal">Alterar</button>
    </section>
    <hr>
    <section id="pay">
        <div>Pix
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paysModal">Alterar</button>
    </section>
    <hr>
    <section id="ticket">
        <div>
            <p class="t">Taxa de entrega
                <label class="v">R$ 10.00</label>
            </p>
            <p class="t">Total de desconto
                <label class="v">R$ 10.00</label>
            </p>
            <b>
                <p class="t">Total do Pedido
                    <label class="v">R$ 10.00</label>
                </p>
            </b>
        </div>
        <button type="button" class="btn btn-primary">Confirmar Compra</button>
    </section>
</div>


<!-- Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Endereços</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Ticket -->
<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Endereços</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pays -->
<div class="modal fade" id="paysModal" tabindex="-1" role="dialog" aria-labelledby="paysModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Endereços</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
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
    });



    // Selecionar todos os botões de diminuir e adicionar um evento de clique
    document.querySelectorAll('.decrease').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Selecionar o input associado a este botão
            var input = this.nextElementSibling;
            // Obter o valor atual do input
            var value = parseInt(input.value);
            // Decrementar o valor, garantindo que não seja menor que 1
            if (value > 1) {
                input.value = value - 1;
            }
        });
    });

    // Selecionar todos os botões de aumentar e adicionar um evento de clique
    document.querySelectorAll('.increase').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Selecionar o input associado a este botão
            var input = this.previousElementSibling;
            // Obter o valor atual do input
            var value = parseInt(input.value);
            // Incrementar o valor
            input.value = value + 1;
        });
    });
</script>

<?php
include_once __DIR__ . '/layout/php/footer.php';
?>