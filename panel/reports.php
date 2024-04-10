<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

?>
<h1 class="h3 mb-0 text-gray-800">Relatórios</h1>
<div class="container-search">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="inputGroupSelect01">Relatório</label>
        </div>
        <select class="custom-select" id="inputGroupSelect01">
            <option selected>Escolha...</option>
            <option value="ReportFinances">Financeiro</option>
            <option value="ReportProducts">Produtos</option>
            <option value="ReportSales">Vendas</option>
        </select>
    </div>
</div>
<hr>

<div id="ReportFinances" class="hidden">
    <div class="container-search">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">De</span>
            </div>
            <input type="date" class="form-control">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Até</span>
            </div>
            <input type="date" class="form-control">
        </div>
    </div>
    <hr>
    <div>
        <div class="row">

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Bruto</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">5
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Saida</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"> 10 Minutos</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Liquido</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">5
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Devolvido
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">5
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <table class="table table-bordered" id="dataTableFinances" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Total Liquido</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Total Liquido</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        #1
                    </td>
                    <td>Venda
                    </td>
                    <td>R$ 30.00</td>
                    <td>
                        09/04/2024 às 10:00
                    </td>
                    <td>
                        Devolvido
                    </td>
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#exampleModalLong"></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        #1
                    </td>
                    <td>Venda
                    </td>
                    <td>R$ 30.00</td>
                    <td>
                        09/04/2024 às 10:00
                    </td>
                    <td>
                        Concluída
                    </td>
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#exampleModalLong"></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        #1
                    </td>
                    <td>Retirada
                    </td>
                    <td>R$ 30.00</td>
                    <td>
                        09/04/2024 às 10:00
                    </td>
                    <td>
                        Concluída
                    </td>
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#exampleModalLong"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

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
                    <div class="sale" hidden>
                        <div id="user_panel">
                            <section class="user-photo-circle">
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
                        <hr>
                        <div>
                            <fieldset id="historic">
                                <legend>Histórico</legend>
                                <p>O pedido foi solicitado em 00/00/0000 às 20:00</p>
                                <p>O pedido começou a ser preparado em 00/00/0000 às 20:00</p>
                                <p>O pedido foi finalizado em 00/00/0000 às 20:00</p>
                                <p>O pedido foi entregue em 00/00/0000 às 20:00</p>
                                <label class="deliveryman">Entregado por [Alexandre de Calvante]</label>
                                <label class="canceledHistory">Cancelado por Demora na entrega</label>
                            </fieldset>
                        </div>
                        <br>

                        <div id="user_address">
                            <table border="1" width="100%">
                                <tr>
                                    <th>#</th>
                                    <th>Endereço de Entrega</th>
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
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor</th>
                                        <th>Observações</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Desconto: -20.00</th>
                                        <th>Cupom: XLAS</th>
                                        <th>Total: 20.00</th>
                                        <th>Código da Venda: 9A56F897B5</th>
                                        <th>--</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>#255</td>
                                        <td>Pizza</td>
                                        <td>1x</td>
                                        <td>R$ 20,00</td>
                                        <td>Sem Mussarela</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="cashier_withdrawal">
                        <div id="user_panel">
                            <section class="user-photo-circle">
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
                        <hr>
                        <div>
                            <fieldset id="historic">
                                <legend>Histórico</legend>
                                <p>Foi retirado do caixa o valor de [R$ 20,00], pelo motivo de [Necessário pagar o
                                    motoboy].</p>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>





<div id="ReportProducts" class="hidden">
    <div>

        <div class="row">

            <div class="col-lg-6">

                <!-- Default Card Example -->
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#topBuyers" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="topBuyers">
                        <h6 class="m-0 font-weight-bold text-primary">Top 10 - Produtos</h6>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse" id="topBuyers">
                        <div class="card-body">
                            <table class="table table-bordered" id="dataTableBuyers" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Cotas</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Cotas</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">

                <!-- Default Card Example -->
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#topWinners" class="d-block card-header py-3 collapsed" data-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="topWinners">
                        <h6 class="m-0 font-weight-bold text-primary">Top 10 - </h6>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse" id="topWinners">
                        <div class="card-body">
                            <table class="table table-bordered" id="dataTableBuyers" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Cotas</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Cota</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Page level plugins -->
        <script src="front/vendor/chart.js/Chart.min.js"></script>


        <link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <table class="table table-bordered" id="dataTableFinances" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Data</th>
                    <th>Categoria</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Data</th>
                    <th>Categoria</th>
                    <th>Opções</th>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        #1
                    </td>
                    <td>Entrada</td>
                    <td>Pizza</td>
                    <td>20x</td>
                    <td>09/04/2024</td>
                    <td>Comida</td>
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#ModalProduct"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal View -->
    <div class="modal fade" id="ModalProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
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
                    <div>
                        <div>
                            <fieldset id="historic">
                                <legend>Histórico</legend>
                                <p>O usuário [Thiago de Oliveira Lima] retirou do estoque [20x]</p>
                                <p>O usuário [Thiago de Oliveira Lima] adicionou do estoque [20x]</p>
                                <p>Foi dado baixa, devido a compra do [Thiago de Oliveira Lima], pedido [2585]</p>
                                <p class="canceledHistory">O Estoque para esse produto, está desativado.</p>
                            </fieldset>
                        </div>
                        <br>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>


<div id="ReportSales" class="hidden">
    <div>
        <link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <table class="table table-bordered" id="dataTableFinances" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Total Liquido</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Total Liquido</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        #1
                    </td>
                    <td>Venda
                    </td>
                    <td>R$ 30.00</td>
                    <td>
                        09/04/2024 às 10:00
                    </td>
                    <td>
                        Devolvido
                    </td>
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#ModalProduct"></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        #1
                    </td>
                    <td>Venda
                    </td>
                    <td>R$ 30.00</td>
                    <td>
                        09/04/2024 às 10:00
                    </td>
                    <td>
                        Concluída
                    </td>
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#ModalProduct"></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        #1
                    </td>
                    <td>Retirada
                    </td>
                    <td>R$ 30.00</td>
                    <td>
                        09/04/2024 às 10:00
                    </td>
                    <td>
                        Concluída
                    </td>
                    <td>
                        <i class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#ModalProduct"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal View -->
    <div class="modal fade" id="ModalProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
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
                    <div class="sale">
                        <div id="user_panel">
                            <section class="user-photo-circle">
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
                        <hr>
                        <div>
                            <fieldset id="historic">
                                <legend>Histórico</legend>
                                <p>O pedido foi solicitado em 00/00/0000 às 20:00</p>
                                <p>O pedido começou a ser preparado em 00/00/0000 às 20:00</p>
                                <p>O pedido foi finalizado em 00/00/0000 às 20:00</p>
                                <p>O pedido foi entregue em 00/00/0000 às 20:00</p>
                                <label class="deliveryman">Entregado por [Alexandre de Calvante]</label>
                                <label class="canceledHistory">Cancelado por Demora na entrega</label>
                            </fieldset>
                        </div>
                        <br>

                        <div id="user_address">
                            <table border="1" width="100%">
                                <tr>
                                    <th>#</th>
                                    <th>Endereço de Entrega</th>
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
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor</th>
                                        <th>Observações</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Desconto: -20.00</th>
                                        <th>Cupom: XLAS</th>
                                        <th>Total: 20.00</th>
                                        <th>Código da Venda: 9A56F897B5</th>
                                        <th>--</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>#255</td>
                                        <td>Pizza</td>
                                        <td>1x</td>
                                        <td>R$ 20,00</td>
                                        <td>Sem Mussarela</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>

<script>

    $(document).ready(function () {
        $('#dataTableFinances').DataTable({
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



    document.addEventListener("DOMContentLoaded", function () {
        const selectElement = document.getElementById('inputGroupSelect01');
        const financeiroDiv = document.getElementById('ReportFinances');
        const ReportProductsDiv = document.getElementById('ReportProducts'); // Corrigido aqui
        const caixaDiv = document.getElementById('ReportSales');

        selectElement.addEventListener('change', function () {
            // Oculta todas as divs
            financeiroDiv.classList.add('hidden');
            ReportProductsDiv.classList.add('hidden');
            caixaDiv.classList.add('hidden');

            // Verifica qual opção está selecionada e exibe a div correspondente
            const selectedOption = selectElement.value;
            if (selectedOption === 'ReportFinances') {
                financeiroDiv.classList.remove('hidden');
            } else if (selectedOption === 'ReportProducts') {
                ReportProductsDiv.classList.remove('hidden');
            } else if (selectedOption === 'ReportSales') {
                caixaDiv.classList.remove('hidden');
            } else {
                // Se a opção selecionada não corresponde a nenhuma das opções disponíveis, oculta todas as divs
                financeiroDiv.classList.add('hidden');
                ReportProductsDiv.classList.add('hidden');
                caixaDiv.classList.add('hidden');
            }
        });
    });

</script>

<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>