selectedUser = null;
users

    = [];



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
            getDispositivos();

        }
    });

}




function getDispositivos() {

    let datos = new FormData();

    datos.append('list_permisos', 'list_permisos');

    $.ajax({
        url: "ajax/permisos.ajax.php",
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




function guardarEquipos(
    rol_selected,
    could_view_users,
    could_edit_users,
    could_export_users,
    could_view_pc,
    could_export_pc,
    could_create_pc,
    could_edit_pc,
    could_view_users_pc,
    could_view_history_users_pc,
    could_view_permission,
    could_edit_permission,
    could_export_permission,
    could_create_permission,

) {

    let newEquipo = {
        rol_selected,
        could_view_users,
        could_edit_users,
        could_export_users,
        could_view_pc,
        could_export_pc,
        could_create_pc,
        could_edit_pc,
        could_view_users_pc,
        could_view_history_users_pc,
        could_view_permission,
        could_edit_permission,
        could_export_permission,
        could_create_permission,
    }

    let datos = new FormData();

    datos.append('new_equipo', JSON.stringify(newEquipo));






    $.ajax({
        url: "ajax/permisos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                title: "Generar permiso",
                text: "El permiso se guardó correctamente",
                icon: "success",
                timer: 1500
            });
            let newUser = JSON.parse(response);
            users

                .push({ ...newUser })

            renderTable();
            $('#modal-form-users').modal('hide');
        }
    });
}


function editUser(id) {
    let datos = new FormData();

    datos.append("id_equipo", id);

    $.ajax({
        url: "ajax/permisos.ajax.php",
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




function eliminarUsuario(dispositivos) {
    let datos = new FormData();
    datos.append("id_eliminar", dispositivos);
    Swal.fire({
        title: `¿Quieres borrar el permiso?`,

        showCancelButton: true,
        confirmButtonText: 'Aceptar',
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                url: "ajax/permisos.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    users = users.filter(p => p.id_user_permission_id !== dispositivos)
                    renderTable();
                }
            });

        }
    })



}

function renderTable() {
    let tabla = document.getElementById("data_table_dispositivos");
    let filas = tabla.getElementsByTagName("tr");
    for (var i = filas.length - 1; i > 0; i--) {
        tabla.deleteRow(i);
    }
    users

        .forEach(pr => {
            let nuevaFila = document.createElement("tr");
            nuevaFila.classList.add('text-center', 'text-uppercase', 'text-black', 'text-xs', 'font-weight-bolder');

            const isDisabled = permisos.could_edit_permission === 0;

            let contenidoCeldas = [
                pr.nombre,
                pr.could_view_users === 1 ? 'SI' : 'NO',
                pr.could_edit_users === 1 ? 'SI' : 'NO',
                pr.could_export_users === 1 ? 'SI' : 'NO',
                pr.could_view_pc === 1 ? 'SI' : 'NO',
                pr.could_export_pc === 1 ? 'SI' : 'NO',
                pr.could_create_pc === 1 ? 'SI' : 'NO',
                pr.could_edit_pc === 1 ? 'SI' : 'NO',
                pr.could_view_users_pc === 1 ? 'SI' : 'NO',
                pr.could_view_history_users_pc === 1 ? 'SI' : 'NO',
                pr.could_view_permission === 1 ? 'SI' : 'NO',
                pr.could_edit_permission === 1 ? 'SI' : 'NO',
                pr.could_export_permission === 1 ? 'SI' : 'NO',
                pr.could_create_permission === 1 ? 'SI' : 'NO',
                ` <a data-bs-toggle="tooltip" title="Borrar" class="text-danger font-weight-bold text-xs"   onclick="eliminarUsuario(${pr.id_user_permission_id})"><i class="fas fa-trash" style='font-size:24px'></i></a>`,
                `<a data-bs-toggle="tooltip" title="Editar" class="text-info font-weight-bold text-xs ${isDisabled ? 'disabled-button' : ''}" 
                onclick="${isDisabled ? 'event.preventDefault();' : `editUser(${pr.id_user_permission_id})`}">
                <i class="fa fa-pencil" style='font-size:24px'></i>
            </a>`
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
}

function renderUsers(data) {
    console.log(data);

    users

        = JSON.parse(data);
    renderTable();

}

function saveEquipo() {
    let rol_selected = document.getElementById('rol_selected').value;
    let could_view_users = document.getElementById('could_view_users').value;
    let could_edit_users = document.getElementById('could_edit_users').value;
    let could_export_users = document.getElementById('could_export_users').value;
    let could_view_pc = document.getElementById('could_view_pc').value;
    let could_export_pc = document.getElementById('could_export_pc').value;
    let could_create_pc = document.getElementById('could_create_pc').value;
    let could_edit_pc = document.getElementById('could_edit_pc').value;
    let could_view_users_pc = document.getElementById('could_view_users_pc').value;
    let could_view_history_users_pc = document.getElementById('could_view_history_users_pc').value;
    let could_view_permission = document.getElementById('could_view_permission').value;
    let could_edit_permission = document.getElementById('could_edit_permission').value;
    let could_export_permission = document.getElementById('could_export_permission').value;
    let could_create_permission = document.getElementById('could_create_permission').value;

    if (selectedUser) {
        saveEditProduct(
            rol_selected,
            could_view_users,
            could_edit_users,
            could_export_users,
            could_view_pc,
            could_export_pc,
            could_create_pc,
            could_edit_pc,
            could_view_users_pc,
            could_view_history_users_pc,
            could_view_permission,
            could_edit_permission,
            could_export_permission,
            could_create_permission
        );
        return;
    }


    guardarEquipos(
        rol_selected,
        could_view_users,
        could_edit_users,
        could_export_users,
        could_view_pc,
        could_export_pc,
        could_create_pc,
        could_edit_pc,
        could_view_users_pc,
        could_view_history_users_pc,
        could_view_permission,
        could_edit_permission,
        could_export_permission,
        could_create_permission
    );
}

function saveEditProduct(
    rol_selected,
    could_view_users,
    could_edit_users,
    could_export_users,
    could_view_pc,
    could_export_pc,
    could_create_pc,
    could_edit_pc,
    could_view_users_pc,
    could_view_history_users_pc,
    could_view_permission,
    could_edit_permission,
    could_export_permission,
    could_create_permission
) {



    let datos = new FormData();

    let product_edit = {
        rol_selected,
        could_view_users,
        could_edit_users,
        could_export_users,
        could_view_pc,
        could_export_pc,
        could_create_pc,
        could_edit_pc,
        could_view_users_pc,
        could_view_history_users_pc,
        could_view_permission,
        could_edit_permission,
        could_export_permission,
        could_create_permission,
        id_equipo: selectedUser.id_user_permission_id
    }

    datos.append("equipo_edit", JSON.stringify(product_edit));
    $.ajax({
        url: "ajax/permisos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                title: "Permisos",
                text: "El permiso fue editado de forma exitosa",
                icon: "success",
                timer: 1500
            });

            console.log(users);
            console.log(selectedUser);
            console.log(product_edit);



            users

                = users

                    .map(ar => {
                        if (ar.id_user_permission_id === selectedUser.id_user_permission_id) {
                            return {
                                ...ar,
                                id_rol: +rol_selected,
                                could_view_users: +could_view_users,
                                could_edit_users: +could_edit_users,
                                could_export_users: +could_export_users,
                                could_view_pc: +could_view_pc,
                                could_export_pc: +could_export_pc,
                                could_create_pc: +could_create_pc,
                                could_edit_pc: +could_edit_pc,
                                could_view_users_pc: +could_view_users_pc,
                                could_view_history_users_pc: +could_view_history_users_pc,
                                could_view_permission: +could_view_permission,
                                could_edit_permission: +could_edit_permission,
                                could_export_permission: +could_export_permission,
                                could_create_permission: +could_create_permission
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



function renderData(data) {
    selectedUser = JSON.parse(data);

    $('#modal-form-users').modal('show');

    let rol_selected = document.getElementById('rol_selected');
    let could_view_users = document.getElementById('could_view_users');
    let could_edit_users = document.getElementById('could_edit_users');
    let could_export_users = document.getElementById('could_export_users');
    let could_view_pc = document.getElementById('could_view_pc');
    let could_export_pc = document.getElementById('could_export_pc');
    let could_create_pc = document.getElementById('could_create_pc');
    let could_edit_pc = document.getElementById('could_edit_pc');
    let could_view_users_pc = document.getElementById('could_view_users_pc');
    let could_view_history_users_pc = document.getElementById('could_view_history_users_pc');
    let could_view_permission = document.getElementById('could_view_permission');
    let could_edit_permission = document.getElementById('could_edit_permission');
    let could_export_permission = document.getElementById('could_export_permission');
    let could_create_permission = document.getElementById('could_create_permission');




    rol_selected.value = selectedUser.id_rol;
    could_view_users.value = selectedUser.could_view_users;
    could_edit_users.value = selectedUser.could_edit_users;
    could_export_users.value = selectedUser.could_export_users;
    could_view_pc.value = selectedUser.could_view_pc;
    could_export_pc.value = selectedUser.could_export_pc;
    could_create_pc.value = selectedUser.could_create_pc;
    could_edit_pc.value = selectedUser.could_edit_pc;
    could_view_users_pc.value = selectedUser.could_view_users_pc;
    could_view_history_users_pc.value = selectedUser.could_view_history_users_pc;
    could_view_permission.value = selectedUser.could_view_permission;
    could_edit_permission.value = selectedUser.could_edit_permission;
    could_export_permission.value = selectedUser.could_export_permission;
    could_create_permission.value = selectedUser.could_create_permission;





}

function printDispositivosPDF(divName) {

    let printContents;
    let popupWin;
    let title = "<h1>Reporte de Equipos</h1>";
    printContents = document.getElementById(divName).innerHTML;
    printContents = title + printContents;

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


