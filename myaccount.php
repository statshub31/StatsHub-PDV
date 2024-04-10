<?php
include_once __DIR__ . '/layout/php/header.php';
?>

<div id="user_panel">
    <section id="user_photo">
        <img src="/layout/images/users/1.jpg">
    </section>
    <section id="user_infos">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="username">Usuário</span>
            </div>
            <input type="text" class="form-control" placeholder="Usuário" aria-label="Usuário"
                aria-describedby="username">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="password">Senha</span>
            </div>
            <input type="password" class="form-control" placeholder="Senha" aria-label="Senha"
                aria-describedby="password">
        </div>
        <hr>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="cName">Nome</span>
            </div>
            <input type="text" class="form-control" placeholder="Isaque da Silva" aria-label="nome"
                aria-describedby="cName">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="email">E-mail</span>
            </div>
            <input type="text" class="form-control" placeholder="isaque.silva@gmail.com" aria-label="email"
                aria-describedby="email">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="phone">Celular</span>
            </div>
            <input type="text" class="form-control" placeholder="(11) 0 0000-0000" aria-label="phone"
                aria-describedby="phone">
        </div>
    </section>
</div>
<br>

<div id="user_address">
    <table border="1" width="100%">
        <tr>
            <th>#</th>
            <th>Endereço</th>
            <th>Opções</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Av. Arcilio Federzoni, 399, Jardim Silvia, Francisco Morato-SP</td>
            <td>
                <i class="fa-solid fa-pen-to-square"></i>
                <i class="fa-solid fa-trash"></i>
            </td>
        </tr>
    </table>
</div>
<br>
<div id="user_history">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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



<!-- Modal -->
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
                                [2x]
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
                    </section>
                    <hr>
                    <section id="ticket">
                        <div>XTY210
                            <small>R$ -25,00</small>
                        </div>
                    </section>
                    <hr>
                    <section id="pay">
                        <div>Pix
                        </div>
                    </section>
                    <hr>
                    <section id="pay">
                        <div>08/04/2024 10:30
                        </div>
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
                    </section>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<?php
include_once __DIR__ . '/layout/php/footer.php';
?>