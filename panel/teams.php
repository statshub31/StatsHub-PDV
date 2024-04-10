<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));

?>

<div style="white-space: nowrap;">
    <h1 class="h3 mb-4 text-gray-800" style="display: inline-block;">Equipe</h1>
    <a href="/panel/users"><button type="submit" class="btn btn-primary">Promover Usuário</button></a>
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
<link href="/layout/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Opções</th>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td>
                <section id="raffle-img-container">
                    <img src="/../../../layout/images/users/1.jpg"></img>
                </section>
            </td>
            <td>Thiago de Oliveira Lima</td>
            <td>Gerente</td>
            <td><i class="fa fa-edit" aria-hidden="true"></i>
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
                        <b><label>Cargo:</label></b>
                        <span>00 00000 0000</span><br>
                    </section>
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

<!-- Modal Depromotion -->
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
                    Confirmando está ação, o usuário voltarar a ter uma permissão de cliente.
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
    });

</script>
</script>


<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>