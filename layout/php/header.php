<?php
include_once (__DIR__ . "/../../engine/init.php");
?>

<!DOCTYPE html>
<html lang="pt-br">


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="StatsHub" />
    <title>Título Plataforma</title>

    <!-- Favicon-->
    <link rel="icon" href="/../../../layout/images/config/favicon.png" type="image/png">

    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />

    <link href="/layout/css/styles.css" rel="stylesheet" />

    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- JS Boostrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</head>
    <div id="loadingDiv"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>
<?php 
echo showLoading();
?>
<body>
    <header>
        <div id="header">
            <section id="logo-header">
                <a href="/#">
                    <img src="/layout/images/config/logo.png">
                </a>
            </section>
            <section id="menu-header">
                <a href="/index">
                    <i class="fa-solid fa-house"></i>
                </a>
                <a href="/cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <a href="/favorites">
                    <i class="fa-solid fa-star"></i>
                </a>
                <a href="/menu">
                    <i class="fa-solid fa-bars"></i>
                </a>
                <a href="/myaccount">
                    <i class="fa-solid fa-user"></i>
                </a>
                <?php
                if (getGeneralSecurityLoggedIn() === true) {
                    ?>
                    <a href="/logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                    <?php
                }
                ?>
            </section>
            <section id="menu-exit">
                <?php
                if (getGeneralSecurityLoggedIn() === true) {
                    ?>
                    <a href="/logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                    <?php
                }
                ?>
            </section>
        </div>
        <div id="menu-footer">
            <a href="/cart">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
            <a href="/favorites">
                <i class="fa-solid fa-star"></i>
            </a>
            <a href="/index">
                <i class="fa-solid fa-house menu-option-center"></i>
            </a>
            <a href="/menu">
                <i class="fa-solid fa-bars"></i>
            </a>
            <a href="/myaccount">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    <main>