<?php
include_once("conexion.php");
include_once("Consultas.php");
require('./models/venta.php');
require('./models/articulo.php');
require('./models/categoria.php');
require('./models/gastos.php');
require('./models/usuario.php');
require('./models/equipos.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}


$rol = intval($_SESSION['rol']);

$usuarios = new Usuario();

$roles = $usuarios->allroles();

$permisos = [
    (object) ['value' => 0, 'label' => 'No'],
    (object) ['value' => 1, 'label' => 'Si'],
]



?>
<!DOCTYPE html>
<html lang="es">
<!-- librerías -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="./img/logo.png">
    <link rel="icon" type="image/png" href="./img/logo.png">
    <title>Monitoreo callcenter</title>
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


    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />

    <link id="pagestyle" href="./assets/css/argon-dashboard.css?v=2.0.2" rel="stylesheet" />

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-dy5PEnU+g4HRQnD6uPXU5d8VU9V9WvSj+G8xRQNgi9l4ebwnzmtv+pW2faa5zjrI9qMGl5VpBaDKk9G1t0Bi9zg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="g-sidenav-show" style="background-color: #009ad5;">
    <div class="h-100 bg-primary position-absolute w-100" style="
  background-size: cover !important;
  background-position: center !important;
  background-repeat: no-repeat !important;">></div>

    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-black opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 mr-4">


            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">



                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Gestión de usuarios</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="users.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa fa-user text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="equipos_piso.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa fa-building text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Equipos piso</span>
                    </a>
                </li>
                <?php if ($rol === 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="equipos.php">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa fa-laptop text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Equipos</span>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a class="nav-link active" href="permisos.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa fa-laptop text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-uppercase font-weight-bolder">Permisos</span>
                    </a>
                </li>



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

                            <div class="row pb-2 p-3">
                                <div class="col-xl-4 d-flex align-items-center text-uppercase">
                                    <h4 class="font-weight-bolder">Permisos</h4>
                                </div>
                                <div class="col-xl-8 text-end">
                                    <div class="d-flex justify-content-end mb-2">
                                        <div>
                                            <button type="button"
                                                onclick="printDispositivosPDF('data_table_equipos_export')"
                                                class="btn mb-0 text-uppercase" style="background: #5e72e4; color:white"><i
                                                    class="fas fa-file-pdf"></i> EXPORTAR A PDF</button>
                                            <button class="btn mb-0 text-uppercase" data-bs-toggle="modal"
                                                style="background: #5e72e4; color:white" data-bs-target="#modal-form-users">
                                                <i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Crear Permiso</button>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-form-users" tabindex="999999" style="z-index: 9999999"
                                        role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title text-uppercase font-weight-bold">Crear permiso
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
                                                                        <div class="col-xl-12">
                                                                            <label for=""
                                                                                class="col-form-label text-uppercase">Rol</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="rol_selected"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($roles as $r) { ?>
                                                                                    <option value="<?php echo $r->id_rol ?>">
                                                                                        <?php echo $r->nombre ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            
                                                                        </div>
                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede ver usuarios</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_view_users"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                              
                                                                        </div>
                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede editar usuarios</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_edit_users"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                              
                                                                        </div>
                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede exportar usuarios</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_export_users"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                              
                                                                        </div>

                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede visualizar equipos</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_view_pc"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                              
                                                                        </div>

                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede exportar equipos</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_export_pc"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                              
                                                                        </div>

                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede crear equipos</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_create_pc"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                              
                                                                        </div>

                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede editar equipos</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_edit_pc"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                              
                                                                        </div>
                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede visualizar equipos de usuario</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_view_users_pc"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                       
                                                                        </div>

                                                                        <div class="col-xl-6">
                                                                            <label for=""
                                                                            class="col-form-label text-uppercase">Puede ver historial de equipos</label>
                                                                            <select class="form-control"
                                                                                name="choices-button" id="could_view_history_users_pc"
                                                                                placeholder="Departure">
                                                                                <?php foreach ($permisos as $r) { ?>
                                                                                    <option value="<?php echo $r->value ?>">
                                                                                        <?php echo $r->label ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                              
                                                                        </div>



                                                                        <button type="button" id="confirmButton"
                                                                            onclick="saveEquipo()"
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
                        <div id="data_table_equipos_export" class="table-responsive">
                            <table class="table align-items-center mb-0" id="data_table_dispositivos">
                                <thead>
                                    <tr>

                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Rol</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede ver usuarios
                                        </th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede editar usuarios</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede exportar usuarios</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede Visualizar equipos</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede exportar equipos</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede crear equipos</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede editar equipos</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede visualizar equipos de piso</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Puede visualizar historial de equipos</th>

                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                        </th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
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


    <script src="js/login.js"></script>
    <script src="js/permisos.js"></script>


    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="assets/js/plugins/chartjs.min.js"></script>
    <!-- Tus archivos JavaScript -->


    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
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