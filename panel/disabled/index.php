<?php
include_once(realpath(__DIR__ . "/front/php/header.php"));

?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" disabled><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
</div>

<hr>

<br>
<div id="dashboard">
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
                                <?php echo getProductsPurchasePriceWeekly() ?>
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
                                <?php echo getProductsPurchasePriceTotal() ?>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Produtos Vendidos
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <?php echo getProductsPurchaseTotal() ?>
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
                                Brindes Entregue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo getRewardsCheckRowCountByStatusReceived(); ?>
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
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 - Apoiadores</h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse" id="topBuyers">
                    <div class="card-body">
                        <table class="table table-bordered" id="dataTableBuyers" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Produto(s)</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Produto(s)</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                $marketList = doProductsPurchaseListV2(5);

                                if ($marketList) {
                                    $mrank = 0;
                                    foreach ($marketList as $data) {
                                        $user_id = $data['user_id'];
                                        $total = $data['total'];
                                        ++$mrank;
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $mrank ?>
                                            </td>
                                            <td>
                                                <?php echo getUsersCName($user_id); ?>
                                            </td>
                                            <td>
                                                <?php echo $total; ?>
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
                    <h6 class="m-0 font-weight-bold text-primary">Produtos x Brindes</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Brindes
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Produtos
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
                    labels: ["Brindes (R$)", "Produtos (R$)"],
                    datasets: [{
                        data: [
                            <?php echo doRewardsCheckPriceTotal(); ?>, // Cancelado
                            <?php echo doProductsPurchaseTotal(); ?>, // Pago
                        ],
                        backgroundColor: ['#e75d4f', '#1cc88a'],
                        hoverBackgroundColor: ['#c82f20', '#13855c'],
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

</div>

<?php
include_once(realpath(__DIR__ . "/front/php/footer.php"));
?>