<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Painel de Controle</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="/panel/index">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Pedidos</span></a>

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#users" aria-expanded="true"
            aria-controls="collapseTwo">

            <i class="fa fa-users" aria-hidden="true"></i>
            <span>Clientes</span>
        </a>

        <div id="users" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Opções</h6>
                <a class="collapse-item" href="/panel/users">Clientes</a>
                <a class="collapse-item" href="/panel/useradd">Adicionar Cliente</a>
            </div>
        </div>

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#campaigns" aria-expanded="true"
            aria-controls="collapseTwo">

            <i class="fa-solid fa-barcode" aria-hidden="true"></i>
            <span>Cupons</span>
        </a>
        <div id="campaigns" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Opções</h6>
                <a class="collapse-item" href="/panel/tickets">Cupons</a>
                <a class="collapse-item" href="/panel/ticketadd">Criar cupom</a>
            </div>
        </div>

        <a class="nav-link" href="/panel/teams">
            <i class="fa fa-user-secret" aria-hidden="true"></i>
            <span>Equipe</span></a>

        <a class="nav-link" href="/panel/sendemail">
            <i class="fa-solid fa-envelope"></i>
            <span>Email</span></a>

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#products" aria-expanded="true"
            aria-controls="collapseTwo">

            <i class="fa fa-bullhorn" aria-hidden="true"></i>
            <span>Produtos</span>
        </a>
        <div id="products" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Opções</h6>
                <a class="collapse-item" href="/panel/products">Produtos</a>
                <a class="collapse-item" href="/panel/additional">Adicionais</a>
                <a class="collapse-item" href="/panel/complements">Complementos</a>
            </div>
        </div>
<!-- 
        <a class="nav-link" href="/panel/finances">
            <i class="fa-solid fa-dollar-sign"></i>
            <span>Financeiro</span></a> -->

        <a class="nav-link" href="/panel/reports">
            <i class="fa-solid fa-chart-line"></i>
            <span>Relatórios</span></a>

        <a class="nav-link" href="/panel/avaliables">
            <i class="fa-solid fa-star"></i>
            <span>Avaliações</span></a>
        <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#plans" aria-expanded="true"
                aria-controls="collapseTwo">

                <i class="fa fa-bullhorn" aria-hidden="true"></i>
                <span>Assinatura/Planos</span>
            </a>
            <div id="plans" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Opções</h6>
                    <a class="collapse-item" href="/panel/plans">Planos</a>
                    <a class="collapse-item" href="/panel/alterplans">Modificar Planos</a>
                </div>
            </div> -->
        <a class="nav-link" href="/panel/settings">
            <i class="fa fa-cog"></i>
            <span>Configurações</span></a>
        <a class="nav-link" href="/logout">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
            <span>Sair</span></a>
    </li>
</ul>
<!-- End of Sidebar -->