<?php
include_once("conexion.php");
include_once("Consultas.php");
require('./models/venta.php');
$rol = intval($_SESSION['rol']);
$idUsuario = intval($_SESSION['id_usuario']);
$venta = new Venta();
$transacciones = $venta->transacciones($idUsuario);



?>
<!DOCTYPE html>
<html lang="es">
<!-- librerías -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="./img/logo.png">
    <link rel="icon" type="image/png" href="./img/logo.png">
    <title>
        Tienda del soldado GSECO
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="./assets/css/argon-dashboard.css?v=2.0.2" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="g-sidenav-show" style="background-color: #009ad5;">
    <div class="h-100 bg-primary position-absolute w-100" style="background-image: url('./img/gseco.jpg') !important;
  background-size: cover !important;
  background-position: center !important;
  background-repeat: no-repeat !important;">></div>
    <!-- sidebar -->
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-black opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0">
                <img src="./img/logo.png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">GSECO</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">inventario</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="stats.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Estadísticas</span>
                    </a>
                </li>
                <?php if ($rol == 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="inventory.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">gestionar inventario</span>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Gestión de ventas</h6>
                </li>
                <?php if ($rol === 2) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="sales.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-cart text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Ventas</span>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link active" href="bills.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Factura de venta</span>
                    </a>
                </li>
                <?php if ($rol === 1) { ?>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Gestión de usuarios</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-user text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Usuarios</span>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">gestión de compras</h6>
                </li>
                <?php if ($rol === 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="purchases.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 font-weight-bolder">COMPRAS</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="purchases-bills.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 font-weight-bolder">FACTURA DE COMPRA</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($rol == 2) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="inventory-expenses.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 font-weight-bolder text-uppercase">Registro de gastos</span>
                        </a>
                    </li>
                <?php } ?>
                <!-- <?php
                if ($rol == 1) {
                    ?>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administración</h6>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="user-administration.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-single-02 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Usuarios</span>
                        </a>
                    </li>
                <?php
                }
                ?> -->
                <?php if ($rol == 1) { ?>
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Reportes</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gastos_operacionales.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Gastos operacionales</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="balance.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 font-weight-bolder text-uppercase">Balance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gastos_no_operacionales.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Gastos no
                                operacionales</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </aside>
    <main class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a class="nav-link text-white font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none text-capitalize">
                                    <?php echo $nombreUsuario; ?>
                                </span>
                                <div class="wrap">
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0">
                                <i class="fa fa-sign-out fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-4">
            <!-- main content -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h4 class="mb-0 text-uppercase font-weight-bolder">Transacciones
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3 pb-0">
                            <?php  // Obtener las facturas
                            $facturas = $venta->facturas($idUsuario);

                            // Ordenar las facturas por fecha de venta de forma descendente
                            usort($facturas, function ($a, $b) {
                                return strtotime($b->fecha_venta) - strtotime($a->fecha_venta);
                            });

                            // Iterar sobre las facturas ordenadas
                            foreach ($facturas as $f) { ?>
                                <ul class="list-group">
                                    <li
                                        class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <a onclick="viewPDF(<?php echo $f->id_venta ?>)" target="_blank"
                                                class="btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-3 btn-md d-flex align-items-center justify-content-center"><i
                                                    class="fas fa-file-pdf"></i></a>
                                            <div class="d-flex flex-column">
                                                <h6
                                                    class="mb-1 text-dark font-weight-bold text-md text-uppercase font-weight-bolder">
                                                    <?php echo $f->nombres_productos; ?>
                                                </h6>
                                                <span class="text-sm text-uppercase font-weight-bolder">
                                                    <?php echo $f->fecha_venta; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div
                                                class="d-flex justify-content-end align-items-center text-md text-uppercase font-weight-bolder">
                                                <?php echo $f->metodos_pagos; ?>
                                            </div>
                                            <div
                                                class="d-flex justify-content-end text-success align-items-center text-gradient text-md text-uppercase font-weight-bolder">
                                                + $
                                                <?php echo number_format($f->total, 0, ',', '.'); ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- footer -->

        </div>
    </main>
    <!-- config interface -->
    <div class="fixed-plugin">
        <div class="card shadow-lg">
            <div class="card-header pb-0 pt-3 ">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">Configuración</h5>
                </div>
                <div class="float-end mt-4">
                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <div class="card-body pt-sm-3 pt-0 overflow-auto">
                <!-- Sidenav Type -->
                <a class="btn btn-danger w-100" onclick="logout()">Cerrar sesión</a>
            </div>
        </div>
    </div>
    <!--   Core JS Files and scripts  -->
    <script src="js/login.js"></script>
    <script src="./js/factura.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="assets/js/plugins/chartjs.min.js"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");
        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#5e72e4",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="./assets/js/argon-dashboard.js"></script>
</body>

</html>
<!-- style -->
<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans);

    .search {
        width: 100%;
        position: relative;
        display: flex;
    }

    .searchTerm {
        width: 100%;
        border: 3px solid #596CFF;
        border-right: none;
        padding: 5px;
        height: 36px;
        border-radius: 5px 0 0 5px;
        outline: none;
        color: black;
    }

    .searchTerm:focus {
        color: #596CFF;
    }

    .searchButton {
        width: 40px;
        height: 36px;
        border: 1px solid #596CFF;
        background: #596CFF;
        text-align: center;
        color: #fff;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        font-size: 20px;
    }

    /*Resize the wrap to see the search bar change!*/
    .wrap {
        width: 30%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .card {
        box-shadow: 4px 8px 8px #303030;
        /* Personaliza los valores según tus preferencias */
    }

    .main-content {
        position: relative;
        border-radius: 10px;
        overflow-y: auto;
        /* Agrega un desplazamiento vertical cuando sea necesario */
        max-height: 100vh;
        /* Establece una altura máxima para evitar que el contenido se desborde */
    }
</style>