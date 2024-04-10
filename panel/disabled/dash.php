<?php

require_once(realpath(__DIR__ . "/../engine/init.php"));

if (isset($_POST['raffle_id']) && isRafflesExist($_POST['raffle_id'])) {
    $raffle_id = $_POST['raffle_id'];
    $calc = calcularPercentual(getRaffleMaxNumbers(getRafflesMaxId($raffle_id)), getRafflesNumbersSoldRowCountApproved($raffle_id));

    ?>

    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Farutamento Semanal:</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$
                                <?php echo getMarketPayPriceValueWeekly($raffle_id) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                Faturamento Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$
                                <?php echo getMarketPayPriceValueTotal($raffle_id) ?>
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Números Vendidos
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <?php echo $calc ?>%
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: <?php echo $calc ?>%" aria-valuenow="<?php echo $calc ?>"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pagamentos Pendentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo doMarketPayListByPayPending($raffle_id); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-6">

            <!-- Default Card Example -->
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#topBuyers" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="topBuyers">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 - Compradores</h6>
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
                                <?php
                                $marketList = doMarketPayList($raffle_id, 10);

                                if ($marketList) {
                                    $mrank = 0;
                                    foreach ($marketList as $data) {
                                        $account_id = $data['account_id'];
                                        $total_quotas = $data['total_quotas'];
                                        ++$mrank;
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $mrank ?>
                                            </td>
                                            <td>
                                                <?php echo getUsersCName(getUsersIDByAccountID($raffle_id)); ?>
                                            </td>
                                            <td>
                                                <?php echo $total_quotas; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
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
                <a href="#topWinners" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="topWinners">
                    <h6 class="m-0 font-weight-bold text-primary">Vencedores</h6>
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
                                <?php
                                $winningList = doRafflesWinnerInfoList($raffle_id);

                                if ($winningList) {
                                    $wrank = 0;
                                    foreach ($winningList as $data) {
                                        $winner_id = $data['id'];
                                        ++$wrank;
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $wrank ?>
                                            </td>
                                            <td>
                                                <?php echo getRafflesWinnerInfoName($winner_id); ?>
                                            </td>
                                            <td>
                                                <?php echo getRafflesWinnerInfoNumber($winner_id); ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page level plugins -->
    <script src="front/vendor/chart.js/Chart.min.js"></script>

    <div class="row">

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Números</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Pendente
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Cancelado
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Pago
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Doado
                        </span><br>
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Compartilhamento
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-secondary"></i> Livre
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';

            // Pie Chart Example
            var ctx = document.getElementById("myPieChart");
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Pendente", "Cancelado", "Pago", "Doado", "Compartilhamento", "Livre"],
                    datasets: [{
                        data: [
                            <?php echo getRafflesNumbersSoldRowCountStatusPending($raffle_id) ?>, // Pedente
                            <?php echo getRafflesNumbersSoldRowCountStatusCanceled($raffle_id) ?>, // Cancelado
                            <?php echo getRafflesNumbersSoldRowCountApproved($raffle_id) ?>, // Pago
                            <?php echo getRafflesNumbersSoldRowCountStatusDonated($raffle_id) ?>, // Doado
                            <?php echo getRafflesNumbersSoldRowCountStatusShared($raffle_id) ?>, // Compartilhamento
                            <?php echo getRafflesNumbersSoldRowCountStatusFree($raffle_id) ?>, // Livre
                        ],
                        backgroundColor: ['#f6c23e', '#e75d4f', '#1cc88a', '#36b9cc', '#4e73df', '#858796'],
                        hoverBackgroundColor: ['#dda20a', '#c82f20', '#13855c', '#258391', '#224abe', '#60616f'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });


        </script>
    </div>

    <?php
} else {
    header('Location: /admin/index', true, 302);
    exit; // Certifique-se de encerrar a execução do script após o redirecionamento
}
?>