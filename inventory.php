<?php
include_once("conexion.php");
include_once("Consultas.php");
require('./models/venta.php');
require('./models/articulo.php');
require('./models/categoria.php');
require('./models/gastos.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}


$rol = intval($_SESSION['rol']);
$nw = new Venta();
$ar = new Articulo();
$c = new Categoria();
$gastos = new Gastos();
$categorias = $c->index();
$ventas = $nw->ventas($_SESSION['id_usuario']);
$ultimaVenta = $nw->ultimaVenta($_SESSION['id_usuario']);
$articulos = $ar->index();
$gastosDiarios = $gastos->gastosDiarios();
$transacciones = $nw->transacciones($_SESSION['id_usuario']);

?>
<!DOCTYPE html>
<html lang="es">
<!-- librerías -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="./img/logo.png">
    <link rel="icon" type="image/png" href="./img/logo.png">
    <title>Tienda del soldado GSECO</title>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <!-- DataTables Buttons JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Argon Dashboard CSS -->
    <link id="pagestyle" href="./assets/css/argon-dashboard.css?v=2.0.2" rel="stylesheet" />
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- DateRangePicker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-dy5PEnU+g4HRQnD6uPXU5d8VU9V9WvSj+G8xRQNgi9l4ebwnzmtv+pW2faa5zjrI9qMGl5VpBaDKk9G1t0Bi9zg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
            <a class="navbar-brand m-0 mr-4">
                <img src="./img/logo.png" class="navbar-brand-img h-100 mr-5" alt="main_logo">
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
                        <a class="nav-link active" href="inventory.php">
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
                            <a href="javascript:;" class="nav-link text-white p-0">
                                <i class="fa fa-sign-out fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid py-4">
            <?php if ($rol === 1) { ?>
                <div class="col-xl-12 mt-2 mb-2">
                    <div class="card">
                        <div class="card-header pb-4">
                            <?php
                            $productosAgotandose = [];

                            foreach ($articulos as $art) {
                                $total = $art->stock_deseado;
                                $actual = $art->stock;
                                $porcentaje = ($actual / $total) * 100;

                                if ($porcentaje <= 40) {
                                    $productosAgotandose[] = $art->nombre;
                                }
                            }
                            if (!empty($productosAgotandose)) {
                                ?>
                                <!-- <div class="alert alert-warning lowercase" role="alert" style="color: white">
                  <strong>¡Aviso!</strong> Se están agotando los siguientes productos:
                  <strong>
                    <?php echo implode(', ', $productosAgotandose); ?>
                  </strong>
                </div> -->
                            <?php } ?>
                            <div class="row pb-2 p-3">
                                <div class="col-xl-4 d-flex align-items-center text-uppercase">
                                    <h4 class="font-weight-bolder">Productos</h4>
                                </div>
                                <div class="col-xl-8 text-end">
                                    <div class="d-flex justify-content-end mb-2">
                                        <div>
                                            <button type="button" onclick="printProductsPDF('data_table_products_export')"
                                                class="btn mb-0 text-uppercase" style="background: #5e72e4; color:white"><i
                                                    class="fas fa-file-pdf"></i> EXPORTAR A PDF</button>
                                            <button class="btn mb-0 text-uppercase" data-bs-toggle="modal"
                                                style="background: #5e72e4; color:white"
                                                data-bs-target="#modal-form-product">
                                                <i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Crear producto</button>


                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-form-product" tabindex="999999"
                                        style="z-index: 9999999" role="dialog" aria-labelledby="modal-form"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title text-uppercase font-weight-bold">Crear producto
                                                    </h4>
                                                    <button type="button" class="btn bg-gradient-danger"
                                                        data-bs-dismiss="modal">X</button>

                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="card card-plain">
                                                        <div class="card-body text-start">
                                                            <form role="form text-left">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-xl-9">
                                                                            <label for=""
                                                                                class="col-form-label text-uppercase">Nombre
                                                                                del producto</label>
                                                                            <input id="product_name" type="text"
                                                                                placeholder="Ingresa el nombre del producto"
                                                                                class="form-control" />
                                                                        </div>
                                                                        <div class="col-xl-3">
                                                                            <label for=""
                                                                                class="col-form-label text-uppercase">Cantidad</label>
                                                                            <input class="form-control" type="number"
                                                                                id="product_stock"
                                                                                placeholder="Ingresa la cantidad">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xl-4">
                                                                            <label for=""
                                                                                class="col-form-label text-uppercase">Valor
                                                                                unitario</label>
                                                                            <input class="form-control" type="number"
                                                                                id="product_price"
                                                                                placeholder="Ingresa el valor del producto"
                                                                                oninput="validarCantidad(this)">
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <label for=""
                                                                                class="col-form-label text-uppercase">Stock
                                                                                máximo</label>
                                                                            <input class="form-control" type="number"
                                                                                id="stock_maximo"
                                                                                placeholder="Ingresa el stock máximo del producto"
                                                                                oninput="validarCantidad(this)">
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <label for=""
                                                                                class="col-form-label text-uppercase">Categoría</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="categories_select"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($categorias as $c) { ?>
                                                                                    <option
                                                                                        value="<?php echo $c->id_categoria ?>"
                                                                                        selected="true">
                                                                                        <?php echo $c->nombre ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" id="confirmButton"
                                                                        onclick="saveProduct()"
                                                                        class="btn btn-round btn-lg w-100 mt-4 mb-0 text-uppercase"
                                                                        style="background: #5e72e4; color:white">guardar
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="data_table_products_export" class="table-responsive">
                            <table class="table align-items-center mb-0" id="data_table">
                                <thead>
                                    <tr>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Nombre del producto</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Precio de venta</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Unidades</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Estado</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Categoría</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Stock máximo</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Inventario</th>
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
                </div>
            <?php } ?>
            <?php if ($rol === 1) { ?>
                <div class="col-xl-12 mt-2 mb-2">
                    <div class="card">
                        <div class="card-header pb-4">
                            <div class="row pb-2 p-3">
                                <div class="col-4 d-flex align-items-center text-uppercase">
                                    <h4 class="font-weight-bolder">Categorías</h4>
                                </div>
                                <div class="col-md-8 text-end">
                                    <div class="d-flex justify-content-end">
                                        <div>
                                            <button onclick="printProductsPDF('categories_table_export')"
                                                class="btn mb-0 text-uppercase" style="background: #5e72e4; color:white"><i
                                                    class="fas fa-file-pdf"></i> EXPORTAR A PDF</button>

                                            <button class="btn mb-0 text-uppercase" style="background: #5e72e4; color:white"
                                                data-bs-toggle="modal" data-bs-target="#modal-form-categories">
                                                <i class="fas fa-plus"></i>&nbsp;&nbsp;Crear categoría</button>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-form-categories" tabindex="1" role="dialog"
                                        aria-labelledby="modal-form" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title text-uppercase font-weight-bold">Crear categoría
                                                    </h4>
                                                    <button type="button" class="btn bg-gradient-danger"
                                                        data-bs-dismiss="modal">X</button>

                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="card card-plain">
                                                        <div class="card-body text-start">
                                                            <form role="form text-center">
                                                                <div class="row">
                                                                    <div class="col-xl-12">
                                                                        <label for=""
                                                                            class="col-form-label text-uppercase">Nombre de
                                                                            la categoria</label>
                                                                        <input id="categoria" type="text"
                                                                            placeholder="Ingresa el nombre de la categoría"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="text-center">
                                                                        <button type="button" onClick="guardarCategoria()"
                                                                            class="btn btn-round btn-lg w-100 mt-4 mb-0 text-uppercase"
                                                                            style="background: #5e72e4; color:white">Añadir
                                                                            categoría</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" id="categories_table_export">
                            <table class="table align-items-center mb-0" id="categories_table">
                                <thead>
                                    <tr>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Nombre</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Estado</th>
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
                </div>
            <?php } ?>
        </div>
        </div>

        <!--secondary content -->
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

    <script src="js/categoria.js"></script>
    <script src="js/login.js"></script>
    <script src="js/product.js"></script>
    <script src="js/stats.js"></script>

    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="assets/js/plugins/chartjs.min.js"></script>
    <!-- Tus archivos JavaScript -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Obtiene la fecha actual
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-US'); // Formato MM/DD/YYYY (cambiar según el formato deseado)

            // Calcula la fecha que será exactamente un mes antes
            const oneMonthAgo = new Date(currentDate);
            oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
            const formattedOneMonthAgo = oneMonthAgo.toLocaleDateString('en-US'); // Formato MM/DD/YYYY (cambiar según el formato deseado)

            // Obtiene el campo de fecha por su nombre y establece la fecha actual y la fecha de un mes antes como valores
            const dateRangeInput = document.querySelector('input[name="daterange"]');
            dateRangeInput.value = formattedOneMonthAgo + ' - ' + formattedDate;
        });
    </script>
    <!-- Date picker -->
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script>
        let rangeDates;

        function viewPDFVentas(id_usuario) {
            const url = `reports/venta_rango.php?id_usuario=${id_usuario}&fecha_inicio=${rangeDates.start}&fecha_final=${rangeDates.end}`;
            // Abre una ventana emergente
            window.open(url, '_blank', 'width=800,height=600,scrollbars=yes');
        }
        $(function () {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function (start, end, label) {
                rangeDates = {
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                }
                let datos = new FormData();
                datos.append('range_dates', JSON.stringify(rangeDates));
                $.ajax({
                    url: "ajax/ventas.ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        let tabla = document.getElementById('ventas_rango');
                        ventas = JSON.parse(response);

                        ventas.forEach(v => {

                            let nuevaFila = document.createElement("tr");
                            nuevaFila.classList.add('text-center', 'text-uppercase', 'text-black', 'text-xs', 'font-weight-bolder');
                            var precioVentaFormateado = parseFloat(v.total_venta).toLocaleString('es-CO');


                            let contenidoCeldas = [
                                v.nombres + ' ' + v.apellidos,
                                "$" + precioVentaFormateado,
                                `<a class="text-danger font-weight-bold text-md"  onclick="viewPDFVentas( ${v.id_usuario})" data-original-title="Delete user"><i class="fas fa-file-pdf"></i></a>`,
                            ];
                            contenidoCeldas.forEach(function (contenido) {
                                var celda = document.createElement("td");
                                var parrafo = document.createElement("p");
                                parrafo.innerHTML = contenido;
                                celda.appendChild(parrafo);
                                nuevaFila.appendChild(celda);
                            });
                            let tabla = document.getElementById("ventas_rango");
                            let filas = tabla.getElementsByTagName("tr");
                            for (var i = filas.length - 1; i > 0; i--) {
                                tabla.deleteRow(i);
                            }
                            // Agrega la nueva fila a la tabla
                            tabla.querySelector("tbody").appendChild(nuevaFila);
                        });
                    }
                });

            });
        });
    </script>
    <script>
        function renderTable() {
            let tabla = document.getElementById("data_table");
            let filas = tabla.getElementsByTagName("tr");
            for (var i = filas.length - 1; i > 0; i--) {
                tabla.deleteRow(i);
            }
            products.forEach(pr => {
                let nuevaFila = document.createElement("tr");
                nuevaFila.classList.add('text-center', 'text-uppercase', 'text-black', 'text-xs', 'font-weight-bolder');
                var precioVentaFormateado = parseFloat(pr.precio_venta).toLocaleString('es-CO');
                // Define el contenido de cada celda
                let contenidoCeldas = [
                    pr.nombre,
                    "$" + precioVentaFormateado,
                    pr.stock,
                    (pr.stock > 0 && pr.estado === 1) ? '<span class="badge badge-sm bg-gradient-success">Stock disponible</span>' : (pr.estado === 0) ? '<span class="badge badge-sm bg-gradient-danger">Stock no disponible</span>' : '<span class="badge badge-sm bg-gradient-warning">Stock agotado</span>',
                    pr.categoria,
                    pr.stock_deseado,
                    `<div class="d-flex align-items-center justify-content-center">
            <span class="me-2 text-xs font-weight-bold">
             ${((pr.stock / pr.stock_deseado) * 100).toFixed(1) + '%'}
            </span>
            <div class="progress">
                ${((pr.stock / pr.stock_deseado) * 100) <= 40 ?
                        `<div class="progress-bar bg-gradient-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:${((pr.stock / pr.stock_deseado) * 100).toFixed(1)}%"></div>` :
                        (((pr.stock / pr.stock_deseado) * 100) >= 40 && ((pr.stock / pr.stock_deseado) * 100) <= 60) ?
                            `<div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: ${((pr.stock / pr.stock_deseado) * 100).toFixed(1)}%"></div>` :
                            `<div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:${((pr.stock / pr.stock_deseado) * 100).toFixed(1)}%"></div>`
                    }
            </div>
          </div>`,
                    ` <a data-bs-toggle="tooltip" title="Editar" class="text-primary font-weight-bold text-xs" onclick="editProduct(${pr.id_articulo})"><i class="fas fa-edit" style='font-size:24px'></i></a>`,
                    ` <a data-bs-toggle="tooltip" title="Borrar" class="text-danger font-weight-bold text-xs"   onclick="eliminarProducto(${pr.id_articulo})"><i class="fas fa-trash" style='font-size:24px'></i></a>`
                ];
                // Itera sobre el contenido de las celdas y crea celdas <td>
                contenidoCeldas.forEach(function (contenido) {
                    var celda = document.createElement("td");
                    var parrafo = document.createElement("p");
                    parrafo.innerHTML = contenido;
                    celda.appendChild(parrafo);
                    nuevaFila.appendChild(celda);
                });
                // Agrega la nueva fila a la tabla
                tabla.querySelector("tbody").appendChild(nuevaFila);
            });
            if ($.fn.DataTable.isDataTable('#data_table')) {
                $('#data_table').DataTable().destroy();
            }
            $('#data_table').DataTable({
                dom: 'Bfrtip',
                paging: true, // Enable pagination
                searching: true, // Enable search functionality
                buttons: [{
                    extend: 'excel',
                    text: '<span class="fas fa-file-excel" aria-hidden="true"></span> EXPORTAR A EXCEL',
                    exportOptions: {
                        columns: ':visible'
                    },
                    attr: {
                        id: 'exportExcelBtn' // Asigna el id al botón
                    }
                }],
                language: {
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    },
                    search: 'Buscar:',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                    infoEmpty: 'Mostrando 0 a 0 de 0 entradas',
                    infoFiltered: '(filtrado de _MAX_ entradas totales)',
                    lengthMenu: 'Mostrar _MENU_ entradas por página',
                    zeroRecords: 'No se encontraron resultados',
                    emptyTable: 'No hay datos disponibles en la tabla'
                }
            });
        }
    </script>
    <script>
        function renderTableCategories() {
            let tabla = document.getElementById("categories_table");

            let filas = tabla.getElementsByTagName("tr");


            for (var i = filas.length - 1; i > 0; i--) {
                tabla.deleteRow(i);
            }

            categories.forEach(c => {

                let nuevaFila = document.createElement("tr");
                nuevaFila.classList.add('text-center', 'text-uppercase', 'text-black', 'text-xs', 'font-weight-bolder');

                // Define el contenido de cada celda
                let contenidoCeldas = [
                    c.nombre,
                    c.estado === 1 ? '<span class="badge badge-sm bg-gradient-success">Stock disponible</span>' : '<span class="badge badge-sm bg-gradient-danger">Stock no disponible</span>',
                    ` <a data-bs-toggle="tooltip" title="Editar" class="text-primary font-weight-bold text-xs"  onclick="editCategoria(${c.id_categoria})" ><i class="fas fa-edit" style='font-size:24px'></i></a>`,
                    `<a data-bs-toggle="tooltip" onclick='eliminarCategoria(${c.id_categoria})' title="Borrar" class="text-danger font-weight-bold text-xs"><i class="fas fa-trash" style='font-size:24px'></i></a>`
                ];

                // Itera sobre el contenido de las celdas y crea celdas <td>
                contenidoCeldas.forEach(function (contenido) {
                    var celda = document.createElement("td");
                    var parrafo = document.createElement("p");
                    parrafo.innerHTML = contenido;
                    celda.appendChild(parrafo);
                    nuevaFila.appendChild(celda);
                });

                // Agrega la nueva fila a la tabla
                tabla.querySelector("tbody").appendChild(nuevaFila);

            });
            if ($.fn.DataTable.isDataTable('#categories_table')) {
                $('#categories_table').DataTable().destroy();
            }
            $('#categories_table').DataTable({
                dom: 'Bfrtip',
                paging: true, // Enable pagination
                searching: true, // Enable search functionality
                buttons: [{
                    extend: 'excel',
                    text: '<span class="fas fa-file-excel" aria-hidden="true"></span> EXPORTAR A EXCEL',
                    exportOptions: {
                        columns: ':visible'
                    },
                    attr: {
                        id: 'export' // Asigna el id al botón
                    }
                }],
                language: {
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    },
                    search: 'Buscar:',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                    infoEmpty: 'Mostrando 0 a 0 de 0 entradas',
                    infoFiltered: '(filtrado de _MAX_ entradas totales)',
                    lengthMenu: 'Mostrar _MENU_ entradas por página',
                    zeroRecords: 'No se encontraron resultados',
                    emptyTable: 'No hay datos disponibles en la tabla'
                }
            });
        }
    </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="./assets/js/argon-dashboard.js"></script>
</body>

</html>
<!-- style -->
<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans);

    .table.dataTable tbody td {
        border: 0px solid #8898aa;
        background: transparent !important;
    }

    .table {
        background: transparent !important;
    }

    div.dt-buttons>.dt-button,
    div.dt-buttons>div.dt-button-split .dt-button {
        border-radius: 0.5rem;
        color: white;
        background: #5e72e4;
        border: 0px;
        line-height: 1.5;
        font-size: 0.875rem;
        font-weight: 700;
        height: 40px;
        width: auto;
        margin-left: 25px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 25px;
        color: white;
        border: 0px transparent;
        font-size: 0.875rem;
        font-weight: 700;
    }

    div#data_table_info {
        font-size: 0.875rem;
        font-weight: 700;
    }

    div#categories_table_info {
        font-size: 0.875rem;
        font-weight: 700;
    }

    div#data_table_wrapper {
        background-color: transparent !important;
        padding: 10px;
        padding-left: 10px;
        border: none;
    }

    select {
        border: 1px solid #8898aa;
        border-radius: 0.4rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #8898aa;
        background-color: transparent;
    }

    div#categories_table_wrapper {
        background-color: transparent !important;
        padding: 10px;
        padding-left: 10px;
        border: none;
    }

    .dataTables_paginate .pagination {
        justify-content: center;
    }

    .dataTables_filter label input {
        border: 1px solid #8898aa;
        border-radius: 0.4rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #8898aa;
        width: 100%;
        background-color: transparent;
    }

    .custom-daterangepicker {
        /* Estilos para el input del Datepicker */
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        color: #333;
        width: 200px;
        /* Ajusta el ancho según lo necesites */
        background-color: #fff;
    }

    /* Estilos para el Datepicker en modo rango */
    .daterangepicker {
        /* Modifica el estilo del contenedor principal del Datepicker */
        font-family: Arial, sans-serif;
        /* Cambia la fuente si lo deseas */
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .card {
        box-shadow: 4px 8px 8px #303030;
        /* Personaliza los valores según tus preferencias */
    }

    #daterange {
        width: 200px;
        /* ajusta el ancho según sea necesario */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 10px;
        height: 50px;
        font-size: 16px;
    }

    #daterange:focus {
        outline: none;
        border-color: #66afe9;
        /* Cambia el color del borde al enfocar el input */
        box-shadow: 0 0 5px rgba(102, 175, 233, 0.5);
        /* Agrega una ligera sombra al enfocar el input */
    }

    .main-content {
        position: relative;
        border-radius: 10px;
        overflow-y: auto;
        /* Agrega un desplazamiento vertical cuando sea necesario */
        max-height: 100vh;
        /* Establece una altura máxima para evitar que el contenido se desborde */
    }

    .dataTables_wrapper .dataTables_filter {
        float: right;
        /* Coloca el buscador a la derecha */
        margin-bottom: 20px;
        /* Ajusta el margen inferior según tus preferencias */
    }

    .dataTables_wrapper .dataTables_filter label {
        font-weight: bold;
        /* Estilo de fuente del label */
        margin-right: 35px;
        /* Ajusta el margen derecho según tus preferencias */
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ccc;
        /* Borde del cuadro de búsqueda */
        padding: 8px;
        /* Ajusta el relleno según tus preferencias */
        border-radius: 5px;
        /* Bordes redondeados */
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        /* Sombra suave */
    }
</style>