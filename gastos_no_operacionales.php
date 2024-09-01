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
                        <a class="nav-link active" href="gastos_no_operacionales.php">
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
            <div class="">
                <input type="text" id="daterange" name="daterange"
                    style="height: 50px !important; width: auto; box-shadow: 4px 4px 8px #303030;" />
            </div>

            <div class="row mt-4">
                <div class="col-xl-12 mt-2 mb-2">
                    <div class="card">
                        <div class="card-header pb-1">
                            <div class="d-flex justify-content-end mb-2">
                                <div>
                                    <button type="button"
                                        onclick="printProductsPDF('data_table_gastos_no_operacionales')"
                                        class="btn mb-0 text-uppercase" style="background: #5e72e4; color:white"><i
                                            class="fas fa-file-pdf"></i> EXPORTAR A PDF</button>
                                </div>
                            </div>
                        </div>
                        <div id="data_table_gastos_no_operacionales" class="table-responsive">
                            <table id="no_operacionales" class="table align-items-center mb-">
                                <thead>
                                    <tr>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Fecha</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Descripción</th>
                                        <th align="center"
                                            class="text-center text-uppercase text-black text-sm font-weight-bolder">
                                            Egreso</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

    ¡
    <script src="js/login.js"></script>
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
                datos.append('no_operacionales', true);
                $.ajax({
                    url: "ajax/gastos_op.ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        let tabla = document.getElementById('no_operacionales');
                        let filas = tabla.getElementsByTagName("tr");

                        for (var m = filas.length - 1; m > 0; m--) {
                            tabla.deleteRow(m);
                        }


                        gastos_nop = [];
                        gastos_nop = JSON.parse(response);

                        let currentDate = new Date(start);


                        for (let currentDate = new Date(start); currentDate <= end; currentDate.setDate(currentDate.getDate() + 1)) {


                            gastos_nop.forEach(g => {
                                let contenidoCeldas = [];
                                if (moment(new Date(g.fecha)).format('YYYY-MM-DD') === moment(currentDate).format('YYYY-MM-DD')) {
                                    let nuevaFila = document.createElement("tr");
                                    contenidoCeldas = [
                                        moment(currentDate).format('YYYY-MM-DD'),
                                        g.descripcion,
                                        '$ ' + parseFloat(g.total).toLocaleString('es-CO'),
                                    ];
                                    contenidoCeldas.forEach(function (contenido) {
                                        var celda = document.createElement("td");
                                        var parrafo = document.createElement("p");
                                        parrafo.innerHTML = contenido;
                                        celda.appendChild(parrafo);
                                        celda.classList.add("text-center");
                                        nuevaFila.appendChild(celda);
                                    });
                                    tabla.querySelector("tbody").appendChild(nuevaFila);
                                }

                            })



                            // Itera sobre el contenido de las celdas y crea celdas <td>





                        }




                    }
                });

            });
        });
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
        height: 50px;
        width: 50px;
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
</style>