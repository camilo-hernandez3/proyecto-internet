selectProduct = null;
products = [];


getProducts();


function getProducts() {

    let datos = new FormData();

    datos.append('all', 'all');

    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            renderProduct(response)

        }
    });

}




function guardarProducto(nombre, cantidad, precio, stockMaximo, selectCategoria) {
    let newProduct = {
        nombre,
        cantidad,
        precio,
        stockMaximo,
        selectCategoria
    }

    let datos = new FormData();

    datos.append('new_product', JSON.stringify(newProduct));

    let category = document.getElementById('categories_select');

    let selectedText = category.options[category.selectedIndex].text;
    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                title: "Generar Producto",
                text: "El producto se guardó correctamente",
                icon: "success",
                timer: 1500
            });
            let newProduct = JSON.parse(response);
            products.push({ ...newProduct, categoria: selectedText })

            renderTable();
            $('#modal-form-product').modal('hide');
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


function eliminarProducto(id_articulo) {
    let datos = new FormData();
    datos.append("id_eliminar", id_articulo);
    Swal.fire({
        title: `¿Quieres borrar el producto?`,

        showCancelButton: true,
        confirmButtonText: 'Aceptar',
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                url: "ajax/productos.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    products = products.filter(p => p.id_articulo !== id_articulo)
                    renderTable();
                }
            });

        }
    })



}

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
}

function renderProduct(data) {
    products = JSON.parse(data);
    renderTable();

}

function saveProduct() {
    let nombre = document.getElementById('product_name').value;
    let cantidad = document.getElementById('product_stock').value;
    let precio = document.getElementById('product_price').value;
    let stockMaximo = document.getElementById('stock_maximo').value;
    let selectCategoria = document.getElementById('categories_select').value;
    if (selectProduct) {
        saveEditProduct(nombre, cantidad, precio, stockMaximo, selectCategoria);
        return;
    }
    guardarProducto(nombre, cantidad, precio, stockMaximo, selectCategoria);
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
        id_articulo: selectProduct.id_articulo
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

            products = products.map(ar => {
                if (ar.id_articulo === selectProduct.id_articulo) {
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
            selectProduct = null;

            $('#modal-form-product').modal('hide');
        }
    });
}

function renderData(data) {
    selectProduct = JSON.parse(data);

    let nombre = document.getElementById('product_name');
    let cantidad = document.getElementById('product_stock');
    let precio = document.getElementById('product_price');
    let stockMaximo = document.getElementById('stock_maximo');
    let selectCategoria = document.getElementById('categories_select');

    nombre.value = selectProduct.nombre;
    cantidad.value = selectProduct.stock;
    precio.value = selectProduct.precio_venta;
    stockMaximo.value = selectProduct.stock_deseado;
    selectCategoria.value = selectProduct.categoria_id_categoria;


    $('#modal-form-product').modal('show');

}


