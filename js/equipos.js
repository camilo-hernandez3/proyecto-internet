selectedUser = null;
users

    = [];


setInterval(getEquipos, 10000);

permisos = null;
getPermisos();



function getPermisos() {
    let datos = new FormData();

    datos.append('permisos', 'permisos');

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
         

            permisos = JSON.parse(response);
            
            getEquipos();



        }
    });

}


function getEquipos() {

    let datos = new FormData();

    datos.append('all', 'all');

    $.ajax({
        url: "ajax/equipos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            
            let info = JSON.parse(response);
            renderTable(info)


            /*  renderUsers(response) */

        }
    });

}




function guardarUsuario(nombres, email, password, selected_rol) {

    let newUser = {
        nombres,
        email,
        password,
        selected_rol
    }

    let datos = new FormData();

    datos.append('new_user', JSON.stringify(newUser));

    let rol = document.getElementById('rol_selected');

    let selectedText = rol.options[rol.selectedIndex].text;
   

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                title: "Generar Usuario",
                text: "El usuario se guardó correctamente",
                icon: "success",
                timer: 1500
            });
            let newUser = JSON.parse(response);
            users

                .push({ ...newUser, rol_id_rol: selectedText })

            renderTable();
            $('#modal-form-users').modal('hide');
        }
    });
}


function editProduct(id) {
    let datos = new FormData();

    datos.append("id_articulo", id);

    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            renderData(response)
            $('.modal-title').text('Editar producto');
        }
    });
}


function eliminarUsuario(id_usuario) {
    let datos = new FormData();
    datos.append("id_eliminar", id_usuario);
    Swal.fire({
        title: `¿Quieres borrar el equipo?`,

        showCancelButton: true,
        confirmButtonText: 'Aceptar',
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                url: "ajax/equipos.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    users = users.filter(p => p.id_equipo !== id_usuario)
                    renderTable();
                }
            });

        }
    })



}

function renderTable(data) {
    let container = document.getElementById("container_equipos");
    container.innerHTML = '';


    data = data.data.map(el => {
        return {
            ...el,
            equipos: JSON.parse(el.equipos)
        }
    }).filter(pi => pi.equipos[0].id_equipo !== null && pi.supervisa > 0);


    

    data.forEach(piso => {
        // Crear un contenedor para el piso
        const pisoDiv = document.createElement('div');
        pisoDiv.className = 'piso';




        // Crear y agregar el encabezado del piso
        const pisoHeader = document.createElement('div');
        pisoHeader.className = 'piso-header';
        const pisoTitle = document.createElement('h3'); // Cambia a h2 si prefieres otro tamaño
        pisoTitle.textContent = piso.nombre;
        pisoHeader.appendChild(pisoTitle);
        pisoDiv.appendChild(pisoHeader);


        // Crear un contenedor para los equipos
        const equiposContainer = document.createElement('div');
        equiposContainer.className = 'equipos-container';

        // Agregar los equipos al contenedor de equipos
        piso.equipos.forEach(equipo => {
            const equipoDiv = document.createElement('div');
            equipoDiv.className = 'equipo';



            // Agregar el nombre del equipo
            const equipoTitle = document.createElement('h3');
            equipoTitle.textContent = equipo.nombre_equipo;
            equipoDiv.appendChild(equipoTitle);

            // Agregar los usuarios asociados al equipo
            const usuariosList = document.createElement('ul');
            usuariosList.className = 'usuario-lista';


            equipoDiv.addEventListener('click', function () {


                let modalContentBody = document.getElementById('modal_content_body');

                modalContentBody.innerHTML = '';

                modalContentBody.appendChild(crearElemento('d-flex justify-content-between mb-2', 'Piso:', piso.nombre, false));
                modalContentBody.appendChild(crearElemento('d-flex justify-content-between mb-2', 'Descripción:', equipo.nombre_equipo, false));
                modalContentBody.appendChild(crearElemento('d-flex justify-content-between mb-2', 'Memoria ram:', equipo.ram, false));
                modalContentBody.appendChild(crearElemento('d-flex justify-content-between mb-2', 'Procesador:', equipo.procesador, false));
                modalContentBody.appendChild(crearElemento('d-flex justify-content-between mb-2', 'Almacenamiento:', equipo.almacenamiento, false));
             
                

                if (equipo.usuario_equipos) {
                    modalContentBody.appendChild(crearElemento('d-flex justify-content-between mb-2', 'Usuario:', equipo.usuario_equipos[0].nombre_user, false));
                    modalContentBody.appendChild(crearElemento('d-flex justify-content-between mb-2', 'Estado:', 'En uso', true));
                } else {

                    modalContentBody.appendChild(crearElemento('d-flex justify-content-between mb-2', 'Estado:', 'Disponible', true));

                }


                const div2 = document.createElement('div');
                div2.className = 'd-flex justify-content-between mb-2';

                const span1 = document.createElement('span');
                span1.className = 'fw-bold text-primary-emphasis';
                span1.textContent = 'Historial';

                const isDisabled = permisos.could_view_history_users_pc === 0;

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'btn btn-danger';
                button.textContent = 'Historial';
                button.disabled = isDisabled;

                button.addEventListener('click', function () {
                    generatePDF(equipo)
                });

                div2.appendChild(span1);
                div2.appendChild(button);
                modalContentBody.appendChild(div2)



                $('#modal-form-equipos').modal('show');

            });



            if (equipo.usuario_equipos) {
                equipo.usuario_equipos.forEach(usuario => {
                    const usuarioItem = document.createElement('li');
                    usuarioItem.textContent = `Usuario ${usuario.nombre_user}`;

                    // Aplicar el color verde si el status es 1
                    if (usuario.status === 1) {
                        usuarioItem.classList.add('status-red');
                        equipoDiv.classList.add('status-red');
                        equipoTitle.classList.add('status-red');
                    }

                    const duration = document.createElement('li');

                    const fechaInicio = new Date(usuario.fecha_inicio);
                    const ahora = new Date();

                    const diferencia = ahora - fechaInicio;


                    const segundos = Math.floor((diferencia / 1000) % 60);
                    const minutos = Math.floor((diferencia / (1000 * 60)) % 60);
                    const horas = Math.floor((diferencia / (1000 * 60 * 60)) % 24);
                    const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));


                    duration.textContent = `Duración: ${horas} : ${minutos} : ${segundos}`;


                    usuariosList.appendChild(duration);
                    usuariosList.appendChild(usuarioItem);

                    const imagen = document.createElement('img');
                    imagen.src = './img/equipo.png'; 
                    imagen.alt = 'Imagen del usuario'; 
                    imagen.style.width = '50px';
                    imagen.style.height = '50px'; 
                    imagen.style.display = 'block'; 

                    usuarioItem.appendChild(imagen);
                });
            }else   {
                equipoDiv.classList.add('status-verde');
                equipoTitle.classList.add('status-verde');
            }

            equipoDiv.appendChild(usuariosList);
            equiposContainer.appendChild(equipoDiv);
        });

        pisoDiv.appendChild(equiposContainer);
        container.appendChild(pisoDiv);
    });

}

function generatePDF(data) {

    let datos = new FormData();

    datos.append('historial', 'historial');
    datos.append('id_dispositivo', data.id_equipo);

    $.ajax({
        url: "ajax/equipos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {

            renderDataPDF(response)

        }
    });



}

function renderDataPDF(response) {

    let data = JSON.parse(response);


    const table = document.createElement('table');
    table.id = 'miTabla';

    // Crear el encabezado de la tabla
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const headers = ['Usuario', 'Equipo', 'fecha Ingreso', 'fecha Salida', 'Duración'];
    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Crear el cuerpo de la tabla
    const tbody = document.createElement('tbody');
    data.forEach(dato => {
        const tr = document.createElement('tr');

        const tdNombre = document.createElement('td');
        tdNombre.textContent = dato.nombre;
        tr.appendChild(tdNombre);

        const tdEdad = document.createElement('td');
        tdEdad.textContent = dato.descripcion;
        tr.appendChild(tdEdad);

        const tdCiudad = document.createElement('td');
        tdCiudad.textContent = dato.fecha_inicio;
        tr.appendChild(tdCiudad);

        const tdSalida = document.createElement('td');
        tdSalida.textContent = dato.fecha_final;
        tr.appendChild(tdSalida);



        const fechaInicio = new Date(dato.fecha_inicio);
        const ahora = new Date(dato.fecha_final);

        const diferencia = ahora - fechaInicio;


        const segundos = Math.floor((diferencia / 1000) % 60);
        const minutos = Math.floor((diferencia / (1000 * 60)) % 60);
        const horas = Math.floor((diferencia / (1000 * 60 * 60)) % 24);
        const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));



        const duration = document.createElement('td');
        duration.textContent = `${horas} : ${minutos} : ${segundos}`;
        tr.appendChild(duration);

        tbody.appendChild(tr);
    });
    table.appendChild(tbody);

    let divCont = document.createElement('div');
    divCont.id = 'div-cont'
    divCont.appendChild(table);
    document.body.appendChild(divCont);





    let printContents = document.getElementById('div-cont').innerHTML;
    let title = "<h1>Reporte de Equipos</h1>";
    printContents = title + printContents;
    let popupWin;



    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html lang="es">
        <head>
          <link id="theme-link" rel="stylesheet" type="text/css" href="styles.css">
          <style>
          @media print {
            ::ng-deep {
              .p-sidebar-header
                display: none
            }

            .table-container{
              margin: 1rem 0;
              padding: 0 1rem;
              height: auto !important;
            }

            tr, th {
              border-color: var(--surface-300);
              border-bottom: 1px solid black;
              border-top: 1px solid black;
              border-collapse: collapse;
              padding: .5rem;
            }

            td {
              padding: .5rem;
            }

            .table-container, .p-sidebar-content, .order-summary-container {
              overflow: unset
            }

            .btn-pdf {
              display: none
            }
          }
          </style>
        </head>
        <body onload="window.print();window.close()">${printContents}</body>
      </html>`);
    popupWin.document.close();

    divCont.remove();




}


function crearElemento(divClass, spanText1, spanText2, isButton = false) {
    const div = document.createElement('div');
    div.className = divClass;

    const span1 = document.createElement('span');
    span1.className = 'fw-bold text-primary-emphasis';
    span1.textContent = spanText1;

    if (isButton) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'btn btn-success';
        button.textContent = spanText2;
        div.appendChild(span1);
        div.appendChild(button);
    } else {
        const span2 = document.createElement('span');
        span2.textContent = spanText2;
        div.appendChild(span1);
        div.appendChild(span2);
    }

    return div;
}



function saveUser() {
    let nombres = document.getElementById('user_name').value;

    let password = document.getElementById('password').value;
    let email = document.getElementById('email').value;

    let selected_rol = document.getElementById('rol_selected').value;

    /*  if (selectedUser) {
         saveEditProduct(nombre, cantidad, precio, stockMaximo, selectCategoria);
         return;
     } */
    guardarUsuario(nombres,
        email,
        password,
        selected_rol);
}

function saveEditProduct(nombre, cantidad, precio, stockMaximo, selectCategoria) {

    let category = document.getElementById('categories_select');

    var selectedText = category.options[category.selectedIndex].text;

    let datos = new FormData();

    let product_edit = {
        nombre,
        cantidad,
        precio,
        stockMaximo,
        selectCategoria,
        id_articulo: selectedUser.id_articulo
    }

    datos.append("product_edit", JSON.stringify(product_edit));
    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                title: "Productos",
                text: "El producto fue editado de forma exitosa",
                icon: "success",
                timer: 1500
            });

            users

                = users

                    .map(ar => {
                        if (ar.id_articulo === selectedUser.id_articulo) {
                            return {
                                ...ar,
                                nombre: nombre,
                                precio_venta: precio,
                                stock: cantidad,
                                categoria_id_categoria: selectCategoria,
                                stock_deseado: stockMaximo,
                                categoria: selectedText
                            }

                        }
                        return ar
                    })

            renderTable();
            selectedUser = null;

            $('#modal-form-product').modal('hide');
        }
    });
}

function renderData(data) {
    selectedUser = JSON.parse(data);

    let nombre = document.getElementById('product_name');
    let cantidad = document.getElementById('product_stock');
    let precio = document.getElementById('product_price');
    let stockMaximo = document.getElementById('stock_maximo');
    let selectCategoria = document.getElementById('categories_select');

    nombre.value = selectedUser.nombre;
    cantidad.value = selectedUser.stock;
    precio.value = selectedUser.precio_venta;
    stockMaximo.value = selectedUser.stock_deseado;
    selectCategoria.value = selectedUser.categoria_id_categoria;


    $('#modal-form-product').modal('show');

}


