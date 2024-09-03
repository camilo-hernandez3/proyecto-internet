selectedUser = null;
users

 = [];


getDispositivos();


function getDispositivos() {

    let datos = new FormData();

    datos.append('list_dispositivos', 'list_dispositivos');

    $.ajax({
        url: "ajax/equipos.ajax.php",
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
    console.log(newUser);
    

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


function eliminarUsuario(dispositivos) {
    let datos = new FormData();
    datos.append("id_eliminar", dispositivos);
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
                    users = users.filter(p => p.id_equipo !== id_equipo)
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
        var precioVentaFormateado = parseFloat(pr.precio_venta).toLocaleString('es-CO');
        // Define el contenido de cada celda
        let contenidoCeldas = [
            pr.descripcion,
            pr.ip_address,
            pr.mac_adress,
            pr.piso_id_piso,
            pr.ram,
            pr.procesador,
            pr.almacenamiento,
            ` <a data-bs-toggle="tooltip" title="Borrar" class="text-danger font-weight-bold text-xs"   onclick="eliminarUsuario(${pr.id_equipo})"><i class="fas fa-trash" style='font-size:24px'></i></a>`
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
    users
    
     = JSON.parse(data);
    renderTable();

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


