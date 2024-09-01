<?php
include_once("conexion.php");
include_once("Consultas.php");
require('./models/categoria.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}


$rol = intval($_SESSION['rol']);

$Categoria = new Categoria()


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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="./assets/css/argon-dashboard.css?v=2.0.2" rel="stylesheet" />
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
                    <a class="nav-link" href="bills.php">
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
                        <a class="nav-link active" href="purchases.php">
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
               
                    <li class="nav-item">
                        <a class="nav-link" href="inventory-expenses.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 font-weight-bolder text-uppercase">Registro de gastos</span>
                        </a>
                    </li>
               
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
                            <a class="nav-link text-white p-0">
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
                <div class="col-xl-12 col-sm-6 mb-xl-0">
                    <?php
                    // Obtén las categorías y ordénalas alfabéticamente
                    $categorias = $Categoria->index();
                    usort($categorias, function ($a, $b) {
                        return strcasecmp($a->nombre, $b->nombre);
                    });

                    ?>

                    <div class="row">
                        <button class="btn dropdown-toggle text-uppercase font-weight-bolder"
                            style="background: #c3c3c3; color:black; border: 0px solid black !important; height: 50px !important; box-shadow: 4px 4px 8px #303030;"
                            type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="true">
                            Selecciona una categoría
                        </button>
                        <ul class="dropdown-menu" style="border: 1px solid black !important"
                            aria-labelledby="dropdownMenuButton">
                            <?php
                            foreach ($categorias as $c) {
                                ?>
                                <li
                                    onclick="ProductsByCategory('<?php echo $c->id_categoria ?>', '<?php echo $c->nombre ?>')">
                                    <a class="dropdown-item text-uppercase font-weight-bolder">
                                        <?php echo $c->nombre ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="row mt-4">
                <div class="col-xl-6 mb-4">
                    <div class="card p-4">
                        <div class="col-xl-12 ">
                            <h4 class="text-uppercase font-weight-bolder">Selecciona los productos</h4>
                            <p class="text-uppercase font-weight-bolder"><em>Categoría seleccionada:</em> <strong
                                    id="selected_category"></strong></p>
                        </div>
                        <div class="row" id="products_category">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card p-4">
                        <form>
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="text-uppercase font-weight-bolder">Productos seleccionados</h4>
                                </div>
                                <div>
                                    <div class="table-responsive" style="min-height: 400px;">
                                        <table id="data_table_compras" class="table align-items-center">
                                            <thead>
                                                <tr>
                                                    <th align="center"
                                                        class="text-center text-uppercase text-black text-xs font-weight-bolder">
                                                        Producto</th>
                                                    <th align="center"
                                                        class="text-center text-uppercase text-black text-xs font-weight-bolder">
                                                        Cantidad</th>
                                                    <th align="center"
                                                        class="text-center text-uppercase text-black text-xs font-weight-bolder">
                                                        Precio unitario</th>
                                                    <th align="center"
                                                        class="text-center text-uppercase text-black text-xs font-weight-bolder">
                                                        Total </th>
                                                    <th align="center"
                                                        class="text-center text-uppercase text-black text-xs font-weight-bolder">
                                                    </th>
                                                    <th align="center"
                                                        class="text-center text-uppercase text-black text-xs font-weight-bolder">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div>
                                </div>
                                <div class="col-xl-12 mt-4">
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <p class="text-md mb-0 text-uppercase font-weight-bold">Total de la compra
                                            </p>
                                            <h5 id="total" class="font-weight-bolder">
                                                $0
                                            </h5>
                                        </div>
                                        <div class="col-xl-4 text-end">
                                            <button type="button"
                                                class="btn btn-success mb-0 text-uppercase font-weight-bolder w-100 h-100"
                                                style="background: #252850; box-shadow: 4px 8px 8px #303030;"
                                                onclick="GenerarCompra()" id="btnCrearCompra">
                                                </i>Generar compra
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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

    <div class="modal fade" id="modal-form-compras" tabindex="-1" role="dialog" aria-labelledby="modal-form-compras"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase font-weight-bolder" id="titleModal"></h5>
                    <button type="button" class="btn bg-gradient-danger" data-bs-dismiss="modal">X</button>
                </div>
                <div class="modal-body p-0">
                    <div class="card card-plain">
                        <div class="card-body">
                            <form role="form text-left">
                                <div class="form-group">
                                    <label for="productQuantity"
                                        class="col-form-label text-uppercase font-weight-bolder">Cantidad:</label>
                                    <input class="form-control" type="number" id="productQuantity"
                                        oninput="validarCantidad(this)">

                                </div>
                                <div class="form-group">
                                    <label for="purchasePrice"
                                        class="col-form-label text-uppercase font-weight-bolder">Precio de
                                        compra:</label>
                                    <input class="form-control" type="text" name="purchasePrice" id="purchasePrice">
                                </div>
                                <button type="button" id="confirmButtonQuantity"
                                    class="btn btn-round bg-gradient-primary btn-lg w-100 mt-4 mb-0 text-uppercase font-weight-bolder">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--   Core JS Files and scripts  -->
    <script src="js/login.js"></script>
    <script src="js/compras.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="assets/js/plugins/chartjs.min.js"></script>

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