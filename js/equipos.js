selectedUser = null;
users

    = [];


setInterval(getEquipos, 10000);
getEquipos();


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
            console.log(response);
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

function renderTable(data) {
    let container = document.getElementById("container_equipos");
    container.innerHTML = '';

    data = data.map(el => {
        return {
            ...el,
            equipos: JSON.parse(el.equipos)
        }
    }).filter(pi => pi.equipos[0].id_equipo !== null);


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

            if (equipo.usuario_equipos) {
                equipo.usuario_equipos.forEach(usuario => {
                    const usuarioItem = document.createElement('li');
                    usuarioItem.textContent = `Usuario ${usuario.nombre_user}`;

                    // Aplicar el color verde si el status es 1
                    if (usuario.status === 1) {
                        usuarioItem.classList.add('status-verde');
                        equipoDiv.classList.add('status-verde');
                        equipoTitle.classList.add('status-verde');
                    }

                    usuariosList.appendChild(usuarioItem);

                    const imagen = document.createElement('img');
                    imagen.src = './img/equipo.png'; // Cambia esto a la ruta de tu imagen
                    imagen.alt = 'Imagen del usuario'; // Texto alternativo para la imagen
                    imagen.style.width = '50px'; // Ajusta el tamaño de la imagen según tus necesidades
                    imagen.style.height = '50px'; // Ajusta el tamaño de la imagen según tus necesidades
                    imagen.style.display = 'block'; // Asegura que la imagen esté en su propia línea

                    usuarioItem.appendChild(imagen);
                });
            }

            equipoDiv.appendChild(usuariosList);
            equiposContainer.appendChild(equipoDiv);
        });

        pisoDiv.appendChild(equiposContainer);
        container.appendChild(pisoDiv);
    });

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


