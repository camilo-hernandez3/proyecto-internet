products = [];
selectedProduct = null;
sumaTotales = 0;




// Función que se ejecutará al cambiar el valor del input
function handleChange(event) {
    if (event.target.value === '') {
        clearProducts();
        return;
    }

    let datos = new FormData();

    datos.append("term", event.target.value);


    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            clearProducts();
            renderProducts(response)
        }

    });


    // Aquí puedes realizar alguna acción con el valor del input
}

// Debounce function
function debounce(func, delay) {
    let timeoutId;

    return function () {
        const context = this;
        const args = arguments;

        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            func.apply(context, args);
        }, delay);
    };
}

// Obtener el input
const inputElement = document.getElementById('keyword');

// Aplicar debounce a la función handleChange con un retraso de 300 milisegundos
const debouncedChange = debounce(handleChange, 300);

// Escuchar el evento de cambio en el input y ejecutar la función debouncedChange
inputElement.addEventListener('input', debouncedChange);



function showProductsByCategory(category, name) {

    let datos = new FormData();

    datos.append("id_categoria", category);


    title = document.getElementById('selected_category')
    title.textContent = name;

    $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            renderProducts(response)
        }

    });

}

function resetTitle() {
    title = document.getElementById('selected_category')
    title.textContent = '';
}



function resetButtonVenta() {
    const btnCrearVenta = document.getElementById("btnCrearVenta");
    btnCrearVenta.disabled = true;
}

function GenerarVenta() {
    let datos = new FormData();
    datos.append("productos", JSON.stringify(products));
    datos.append("total", sumaTotales);
    Swal.fire({
        title: `¿Quieres generar la venta?`,
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "ajax/ventas.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire({
                        title: "Generar venta",
                        text: "La venta se generó de forma exitosa",
                        icon: "success",
                        timer: 1500
                    });
                    this.products = [];
                    let tabla = document.getElementById("data_table");
                    let filas = tabla.getElementsByTagName("tr");

                    for (var i = filas.length - 1; i > 0; i--) {
                        tabla.deleteRow(i);
                    }
                    resetButtonVenta();
                    resetTitle();
                    clearProducts();
                    renderSumTotal(0);
                    selectedProduct = null;
                    sumaTotales = 0;
                    products = [];
                }
            });
        }
    });
}

function clearProducts() {
    divContainer = document.getElementById('products_category')

    while (divContainer.firstChild) {
        divContainer.removeChild(divContainer.firstChild);
    }
}

function renderProducts(data) {
    data = JSON.parse(data);
    clearProducts();
    if (data.length > 0) {
        data.forEach(pr => {
            // Crear un contenedor div con clase "col-md-4"
            var colDiv = document.createElement("div");
            colDiv.className = "col-xl-4";
            colDiv.addEventListener("click", function () {
                // Llama a la función addProductTable con el valor de pr
                addProductTable(pr);
            });

            // Crear un div con clase "producto"
            var productoDiv = document.createElement("div");
            productoDiv.className = "producto";

            // Crear un botón
            var btn = document.createElement("a");
            btn.className = "btn btn-primary";
            btn.style.background = "#c3c3c3";
            btn.style.color = "black";
            btn.style.width = "100%"; // Asegura que todos los botones tengan el mismo ancho
            btn.style.overflow = "hidden";
            btn.style.textOverflow = "ellipsis";
            btn.style.whiteSpace = "nowrap";
            btn.style.maxWidth = "100%";
            if (window.innerWidth > 1200) {
                btn.setAttribute("data-toggle", "tooltip");
                btn.setAttribute("data-placement", "top");
                btn.setAttribute("title", pr.nombre); // Agrega el nombre del artículo como título del tooltip
            }
            // Crear un div para el nombre del producto
            var nombreDiv = document.createElement("div");
            nombreDiv.textContent = pr.id_articulo + " - " + pr.nombre;
            nombreDiv.style.whiteSpace = "nowrap";
            nombreDiv.style.overflow = "hidden";
            nombreDiv.style.textOverflow = "ellipsis";
            // Crear un div para el precio de venta
            var precioNumerico = parseFloat(pr.precio_venta);
            var precioFormateado = precioNumerico.toLocaleString('es-CO');
            var precioDiv = document.createElement("div");
            precioDiv.textContent = '$' + precioFormateado;

            function destroyTooltip() {
                $(btn).tooltip('dispose');
            }
            
            // Verificar si la pantalla es lo suficientemente grande para los tooltips
            if (window.innerWidth >= 1200) {
                destroyTooltip();

                // Agregar el nuevo tooltip solo en pantallas grandes
                $(btn).tooltip();
            } else {
                // Si la pantalla es pequeña, destruir el tooltip para evitar que se quede pegado
                destroyTooltip();
            }
            // Agregar los divs al botón
            btn.appendChild(nombreDiv);
            btn.appendChild(precioDiv);
            // Crear un div para el modal
            var modalDiv = document.createElement("div");
            modalDiv.className = "modal fade";
            modalDiv.id = "modal-form-product";
            modalDiv.tabIndex = "-1";
            modalDiv.role = "dialog";
            modalDiv.setAttribute("aria-labelledby", "modal-form");
            modalDiv.setAttribute("aria-hidden", "true");

            productoDiv.appendChild(btn);

            colDiv.appendChild(productoDiv);
            colDiv.appendChild(modalDiv);

            divContainer.appendChild(colDiv);
            $(btn).tooltip();
        });
    } else {
        msg = '<div class="col-md-12 text-center"><h5>No se encontraron artículos</h5></div>';
        divContainer.innerHTML = msg;
    }
}

function addProductTable(pr) {


    const rs = products.filter(
        (art) => art.id_articulo === pr.id_articulo
    );
    let title = document.querySelector('#titleModal');
    let cantidad = document.querySelector('#productQuantity');

    if (rs?.length) {
        Swal.fire({
            title: "Agregar un producto",
            text: "El producto ya se encuentra en el carrito de compras",
            icon: "warning",
            timer: 1500
        });
        return;
    } else {

        selectedProduct = pr;

        $('#modal-form').modal('show');

        title.textContent = pr.nombre;
        cantidad.value = 1;

    }
    let stock = document.getElementById('stock');
    stock.textContent = selectedProduct.stock + ' productos';
}

function renderSumTotal(value) {
    let total = document.getElementById('total');
    var precioVentaFormateado = parseFloat(value).toLocaleString('es-CO');
    total.textContent = '$' + precioVentaFormateado;

}

function confirmQuantity() {
    const quantityInput = document.getElementById('productQuantity');
    const quantity = parseInt(quantityInput.value);
   
    const metodo_pago = document.getElementById('metodos_pagos');
    
    const indiceSeleccionado = metodo_pago.selectedIndex;
    if (!isNaN(quantity) && quantity > 0) {
        const rs = products.filter(
            (art) => art.id_articulo === selectedProduct.id_articulo
        );

        if (rs?.length) {
            prActualizado = products.find(art => art.id_articulo === selectedProduct.id_articulo)
            prActualizado.cantidad = quantity;
            prActualizado.metodo_pago_id = metodo_pago.value;
            prActualizado.metodo_pago_text = metodo_pago.options[indiceSeleccionado].text;
           
            metodo_pago.selectedIndex = 0;
            quantityInput.value = 1;
            $('#modal-form').modal('hide');
            Swal.fire({
                title: "Editar producto",
                text: "El registro fue editado exitosamente",
                icon: "success",
                timer: 1500
            });
            renderTable();
            return;
        } else {
            products.push({
                ...selectedProduct,
                cantidad: quantity,
                metodo_pago_id: metodo_pago.value,
                metodo_pago_text: metodo_pago.options[indiceSeleccionado].text
            });
            metodo_pago.selectedIndex = 0;
            quantityInput.value = 1;
            Swal.fire({
                title: "Agregar producto",
                text: "El producto fue agregado al carrito de compras",
                icon: "success",
                timer: 1500
            });
            
    
        }
       
        renderTable();

    } else {
        Swal.fire({
            title: "Error",
            text: "Ingrese una cantidad válida",
            icon: "error",
            timer: 1500
        });
    }
}



function renderTable() {
    let tabla = document.getElementById("data_table");

    let filas = tabla.getElementsByTagName("tr");

    sumaTotales = 0;
    sumaEfectivo = 0;

    for (var i = filas.length - 1; i > 0; i--) {
        tabla.deleteRow(i);
    }
    products.forEach(pr => {
        let nuevaFila = document.createElement("tr");
        var precioVentaFormateado = parseFloat(pr.precio_venta).toLocaleString('es-CO');
        var subtotal = pr.cantidad * pr.precio_venta;
        var subtotalFormateado = subtotal.toLocaleString('es-CO');

        sumaTotales += subtotal;
        if (pr.metodo_pago_text === "Efectivo") {
            sumaEfectivo += subtotal;
        }
        // Define el contenido de cada celda
        let contenidoCeldas = [
            pr.nombre,
            pr.cantidad,
            "$" + precioVentaFormateado,
            pr.metodo_pago_text,
            "$" + subtotalFormateado,
            // Celda con el botón "Editar"
            '<a class="text-primary font-weight-bold text-md" onclick="editProduct(' + pr.id_articulo + ')" data-original-title="Delete user">Editar</a>',
            // Celda con el botón "Borrar"
            '<a class="text-danger font-weight-bold text-md"  onclick="removeProduct(' + pr.id_articulo + ')" data-original-title="Delete user">Borrar</a>',
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

        const modal = document.getElementById('modal');
        renderSumTotal(sumaTotales)

        $('#modal-form').modal('hide');
        document.getElementById('productQuantity').value = 1;
    })
    const modalFormChange = document.getElementById('modal-form-change');
    modalFormChange.dataset.sumaEfectivo = sumaEfectivo;

    renderSumTotal(sumaTotales);
    habilitarBotonVenta();
}

function calcularCambio() {
    // Obtén el valor ingresado por el usuario
    var montoIngresado = parseFloat(document.getElementById('product_price').value);

    if (isNaN(montoIngresado) || montoIngresado <= 0) {
        alert("Por favor, ingresa un valor válido para el monto.");
        return;
    }
    var sumaEfectivo = parseFloat(document.getElementById('modal-form-change').dataset.sumaEfectivo);

    // Realiza la resta para calcular el cambio
    var cambio = montoIngresado - sumaEfectivo;

    if (montoIngresado < sumaEfectivo) {
        alert("Por favor, ingresa un valor válido para el monto.");
        return;
    }
    // Muestra el cambio en algún lugar del modal (por ejemplo, puedes agregar un elemento <p> en el modal para mostrar el cambio)
    var cambioElement = document.getElementById('cambio_resultado');
    cambioElement.textContent = "Debes devolverle al cliente $" + cambio.toLocaleString('es-CO');
}

document.getElementById('btnCalcular').addEventListener('click', function () {
    calcularCambio();
});

function habilitarBotonVenta() {

    const tabla = document.getElementById("data_table");
    const btnCrearVenta = document.getElementById("btnCrearVenta");

    if (tabla && tabla.rows.length > 1) { // Verifica que la tabla tenga más de una fila (cabecera + al menos un elemento)
        btnCrearVenta.disabled = false; // Habilita el botón
    } else {
        btnCrearVenta.disabled = true; // Deshabilita el botón
    }
}
// Llama a la función para habilitar o deshabilitar el botón cuando se carga la página
window.onload = habilitarBotonVenta;

function editProduct(id_articulo) {
    selectedProduct = products.find(p => p.id_articulo === id_articulo);

    title = document.getElementById('titleModal');
    title.textContent = selectedProduct.nombre;

    $('#modal-form').modal('show');
    let cantidad = document.getElementById('productQuantity');
    let stock = document.getElementById('stock');
    stock.textContent = selectedProduct.stock + ' productos';
    let cantidadValue = +selectedProduct.cantidad;

    let pago = document.getElementById('metodos_pagos');
    pago.selectedIndex = selectedProduct.metodo_pago_id - 1;

    /* if (!cantidad.value) { */
        cantidad.value = cantidadValue;
    /* } */

    // Agrega una validación adicional
    cantidad.addEventListener('input', function () {
        if (cantidad.value <= 0) {
            cantidad.value = 1;
        }
    });
}

function validarCantidad(input) {
    if (input.value < 0) {
        input.value = 0;
    }
}



function removeProduct(id_articulo) {


    selectedProduct = products.find(p => p.id_articulo === id_articulo);

    Swal.fire({
        title: `¿Quieres borrar el producto ${selectedProduct.nombre}?`,

        showCancelButton: true,
        confirmButtonText: 'Aceptar',
    }).then((result) => {

        if (result.isConfirmed) {
            products = products.filter(p => p.id_articulo !== id_articulo)
            sumaTotales = 0;
            products.forEach(pr => {
                sumaTotales += pr.cantidad * pr.precio_venta;
            });

            // Actualizar el elemento HTML del total

            renderSumTotal(sumaTotales)
            renderTable();
        }
    })


}



function generarCierre() {

    const url = `reports/cierreCaja.php`;

    // Abre una ventana emergente
    window.open(url, '_blank', 'width=800,height=600,scrollbars=yes');


}

const confirmButton = document.getElementById('confirmButton');
confirmButton.addEventListener('click', confirmQuantity);

let cant = document.getElementById('productQuantity');

/* confirmQuantity(); */

cant.addEventListener('keypress', function (event) {
    // Verifica si la tecla presionada es 'Enter' (cuyo código es 13)
    if (event.key === 'Enter') {
        // Evita que el formulario se envíe (si está dentro de un formulario)
        event.preventDefault();
        confirmQuantity();

        // Llama a la función que deseas ejecutar al presionar 'Enter'
    }
});


