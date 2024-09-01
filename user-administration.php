<?php
include_once("conexion.php");
include_once("Consultas.php");
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

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
</head>

<body class="g-sidenav-show" style="background-color: #009ad5;">
    <div class="h-100 bg-primary position-absolute w-100"></div>
    <!-- sidebar -->
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-black opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0">
                <img src="./img/logo.png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">GSECO</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Artículos</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="stats.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1  text-black">Estadísticas</span>
                    </a>
                </li>
                <?php if ($rol == 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="balance.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 font-weight-bolder text-uppercase">Balance</span>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link" href="sales.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-cart text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Ventas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="bills.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Facturas</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">PROVEEDORES</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="purchases.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 font-weight-bolder">COMPRAS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="purchases-bills.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 font-weight-bolder">FACTURA DE COMPRA</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="inventory-expenses.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-chart-bar-32 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 font-weight-bolder text-uppercase">Registro de gastos</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administración</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="user-administration.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Usuarios</span>
                    </a>
                </li>
                <li class="sidenav-footer mx-3">

                </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
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
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                        <!-- <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
              </ul>
            </li> -->
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-4">
            <!-- main content -->
            <div class="row">
                <div class="col-xl-4">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="row pb-2 p-3">
                                <div class="col-6 d-flex align-items-center">
                                    <h6>Roles</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a class="btn bg-gradient-primary mb-0" data-bs-toggle="modal" data-bs-target="#modal-form"><i class="fas fa-plus"></i>&nbsp;&nbsp;Añadir
                                        rol</a>
                                    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body p-0">
                                                    <div class="card card-plain">
                                                        <div class="card-body">
                                                            <form role="form text-left">
                                                                <div class="text-center">
                                                                    <button type="button" class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Añadir
                                                                        rol</button>
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
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-black text-xxs font-weight-bolder opacity-7 pl-2">
                                                Rol</th>
                                            <th class="text-black opacity-7"></th>
                                            <th class="text-black opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">Administrador</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-black font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                                    Editar
                                                </a>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-danger font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete user">
                                                    Borrar
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="row pb-2 p-3">
                                <div class="col-8 d-flex align-items-center">
                                    <h6>Usuarios</h6>
                                </div>
                                <div class="col-4 text-end">
                                    <a class="btn bg-gradient-primary mb-0" data-bs-toggle="modal" data-bs-target="#modal-form-user"><i class="fas fa-plus"></i>&nbsp;&nbsp;Añadir
                                        usuario</a>
                                    <div class="modal fade" id="modal-form-user" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body p-0">
                                                    <div class="card card-plain">
                                                        <div class="card-body">
                                                            <form role="form text-left">
                                                                <div class="text-center">
                                                                    <button type="button" class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Añadir
                                                                        usuario</button>
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
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-black text-xxs font-weight-bolder opacity-7">
                                                Nombre</th>
                                            <th class="text-uppercase text-black text-xxs font-weight-bolder opacity-7 ps-2">
                                                Rol</th>
                                            <th class="text-center text-uppercase text-black text-xxs font-weight-bolder opacity-7">
                                                Estado</th>
                                            <th class="text-black opacity-7"></th>
                                            <th class="text-black opacity-7">
                                                <a href="./reports/usuarios.php" target="_blank">
                                                    <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4">
                                                        <i class="fas fa-file-pdf text-lg me-1"></i> PDF
                                                    </button>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">John Michael</h6>
                                                        <p class="text-xs text-black mb-0">john@creative-tim.com</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">Manager</p>
                                                <p class="text-xs text-black mb-0">Organization</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">Activo</span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-primary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                                    Editar
                                                </a>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-danger font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete user">
                                                    Borrar
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
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
</style>