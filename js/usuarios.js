selectedUser = null;
users

    = [];


getUsers();


function getUsers() {

    let datos = new FormData();

    datos.append('all', 'all');

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            renderUsers(response)

        }
    });

}






function guardarUsuario(nombres, email, password, selected_rol, piso_selected, new_rol) {

    let newUser = {
        nombres,
        email,
        password,
        selected_rol,
        piso_selected,
        new_rol
    }

    let datos = new FormData();

    datos.append('new_user', JSON.stringify(newUser));

    let rol = document.getElementById('rol_selected');

    let selectedText = rol.options[rol.selectedIndex].text;
    console.log(newUser);

    let piso = document.getElementById('piso_selected');


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


function editUser(id) {
    let datos = new FormData();

    datos.append("id_usuario", id);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);

            renderData(response)
           
        }
    });
}


function eliminarUsuario(id_usuario) {
    let datos = new FormData();
    datos.append("id_eliminar", id_usuario);
    Swal.fire({
        title: `¿Quieres borrar el usuario?`,

        showCancelButton: true,
        confirmButtonText: 'Aceptar',
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                url: "ajax/usuarios.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    users = users.filter(p => p.id_usuario !== id_usuario)
                    renderTable();
                }
            });

        }
    })



}

function renderTable() {
    let tabla = document.getElementById("data_table_users");
    let filas = tabla.getElementsByTagName("tr");
    for (var i = filas.length - 1; i > 0; i--) {
        tabla.deleteRow(i);
    }

    console.log(users);

    users

        .forEach(pr => {
            let nuevaFila = document.createElement("tr");
            nuevaFila.classList.add('text-center', 'text-uppercase', 'text-black', 'text-xs', 'font-weight-bolder');
            var precioVentaFormateado = parseFloat(pr.precio_venta).toLocaleString('es-CO');


            let contenidoCeldas = [
                pr.nombre,
                pr.email,
                ` <a data-bs-toggle="tooltip" title="Borrar" class="text-danger font-weight-bold text-xs"   onclick="eliminarUsuario(${pr.id_usuario})"><i class="fas fa-trash" style='font-size:24px'></i></a>`,
                ` <a data-bs-toggle="tooltip" title="Borrar" class="text-info font-weight-bold text-xs"   onclick="editUser(${pr.id_usuario})"><i class="fa fa-pencil" style='font-size:24px'></i></a>`

            ];


            contenidoCeldas.forEach(function (contenido) {
                var celda = document.createElement("td");
                var parrafo = document.createElement("p");
                parrafo.innerHTML = contenido;
                celda.appendChild(parrafo);
                nuevaFila.appendChild(celda);
            });


            tabla.querySelector("tbody").appendChild(nuevaFila);
        });
}

function renderUsers(data) {
    users

        = JSON.parse(data);
    renderTable();

}

function saveUser() {
    let nombres = document.getElementById('user_name').value;

    let password = document.getElementById('password').value;
    let email = document.getElementById('email').value;

    let selected_rol = document.getElementById('rol_selected').value;
    let piso_selected = document.getElementById('piso_selected');
    let new_rol = document.getElementById('new_rol').value;

    const selectedOptions = Array.from(piso_selected.selectedOptions);

    // Extraer los valores de las opciones seleccionadas
    const selectedValues = selectedOptions.map(option => +option.value);

    console.log(selectedValues);

    if (selectedUser) {
        saveEditProduct(
            nombres,
            email,
            password,
            selected_rol,
            selectedValues
        );
        return;
    }
    guardarUsuario(nombres,
        email,
        password,
        selected_rol,
        selectedValues,
        new_rol);
}

function saveEditProduct(nombres,
    email,
    password,
    selected_rol,
    selectedValues) {



    let datos = new FormData();

    let user_edit = {
        nombres,
        email,
        password,
        selected_rol,
        piso_selected:selectedValues,
        id_usuario: selectedUser[0].id_usuario
    }

    datos.append("user_edit", JSON.stringify(user_edit));
    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                title: "Usuarios",
                text: "El usuario fue editado de forma exitosa",
                icon: "success",
                timer: 1500
            });

            //! TODO end

            users

                = users

                    .map(ar => {
                        if (ar.id_usuario === selectedUser[0].id_usuario) {
                            return {
                                ...ar,
                                nombre: nombres,
                                email: email,
                                user_password: password,
                                rol_id_rol: selected_rol,

                            }

                        }
                        return ar
                    })

            renderTable();
            selectedUser = null;

            $('#modal-form-users').modal('hide');
        }
    });
}


function crearUsuario(){

    document.getElementById('user_name').value = null;
    document.getElementById('email').value = null;
    document.getElementById('password').value = null;
    document.getElementById('rol_selected').value = null;
    document.getElementById('piso_selected').value = null
    $('#modal-form-users').modal('show');
}

function renderData(data) {
    selectedUser = JSON.parse(data);

    $('#modal-form-users').modal('show');

    let nombre = document.getElementById('user_name');
    let email = document.getElementById('email');
    let password = document.getElementById('password');
    let rol = document.getElementById('rol_selected');


    nombre.value = selectedUser[0].nombre;
    email.value = selectedUser[0].email;
    password.value = selectedUser[0].user_password;
    rol.value = selectedUser[0].rol_id_rol;

    const selectElement = document.getElementById('piso_selected');

    selectElement.selectedIndex = -1;

    let pisosSeleccionados = selectedUser.map(u => u.piso_id_piso)

    for (const pisoId of pisosSeleccionados) {
        const option = Array.from(selectElement.options).find(opt => opt.value == pisoId);
        if (option) {
            option.selected = true; // Selecciona la opción
        }
    }



   

}


function printUsuariosPDF(divName) {

    let printContents;
    let popupWin;
    let title = "<h1>Reporte de usuarios</h1>";

    printContents = document.getElementById(divName).innerHTML;
    printContents = title  +  printContents;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html lang="es">
        <head>
          <title></title>
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

}


