gastos = [];

let nuevo_gasto = document.getElementById('nuevo_gasto');
let contenedorGastos = document.getElementById('contenedor-gastos');

nuevo_gasto.addEventListener('click', () => {
    openModalGastos();

})

getGastos();

function openModalGastos() {
    $('#modal-gastos').modal('show');
}

function removeDataGastos() {
    while (contenedorGastos.firstChild) {
        contenedorGastos.removeChild(contenedorGastos.firstChild);
    }
}

function getGastos() {

    let gastos = new FormData();

    gastos.append("all", "all");

    $.ajax({
        url: "ajax/gastos.ajax.php",
        method: "POST",
        data: gastos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            removeDataGastos();
            gastos = JSON.parse(response);
            renderDataGastos(gastos)

        }

    });

}

function renderDataGastos(gastos) {
    // Obtener el contenedor de gastos
    let contenedorGastos = document.getElementById('contenedor-gastos');

    // Limpiar el contenedor antes de renderizar nuevos datos
    contenedorGastos.innerHTML = "";

    // Crear elementos para cada gasto
    const nuevoDiv = document.createElement('div');
    nuevoDiv.classList.add('col-xl-12');

    // Agregar el título una sola vez
    nuevoDiv.innerHTML = `
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <div class="row">
                    <div class="col-6 d-flex align-items-center">
                        <h4 class="mb-0 text-uppercase font-weight-bolder">Gastos realizados</h4>
                    </div>
                </div>
            </div>
            <div class="card-body p-3 pb-0">
                <!-- Lista de gastos -->
                <ul class="list-group">
                </ul>
            </div>
        </div>
    `;

    // Agregar el contenedor principal al contenedorGastos
    contenedorGastos.appendChild(nuevoDiv);

    // Obtener la lista de gastos dentro del contenedor principal
    const listaGastos = nuevoDiv.querySelector('.list-group');

    // Iterar sobre los gastos y agregar cada uno a la lista
    gastos.forEach(g => {
        const listItem = document.createElement('li');
        listItem.classList.add('list-group-item', 'border-0', 'd-flex', 'justify-content-between', 'ps-0', 'mb-2', 'border-radius-lg');
        listItem.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="d-flex flex-column">
                    <h6 class="mb-1 text-dark font-weight-bold text-md text-uppercase font-weight-bolder">
                        ${g.descripcion}
                    </h6>
                    <span class="text-sm text-uppercase font-weight-bolder">
                        ${g.fecha}
                    </span>
                </div>
            </div>
            <div class="column">
                <div class="d-flex justify-content-end text-danger align-items-center text-gradient text-md text-uppercase font-weight-bolder">
                    - $ ${g.total.toLocaleString('es-ES')}
                </div>
            </div>
        `;

        // Agregar el elemento del gasto a la lista
        listaGastos.appendChild(listItem);
    });
}




function guardar() {
    // Obtener los valores de los campos
    let descripcion = document.getElementById("description_bills").value;
    let total = document.getElementById("total_bills").value;

    let categoria_gasto = document.getElementById("categoria_gasto").value;


    // Validar que los campos no estén vacíos
    if (!descripcion || !total) {
        alert("Por favor, completa todos los campos antes de guardar.");
        return; // Detener la ejecución si los campos no están llenos
    }

    // Crear FormData y agregar los valores
    let gastos = new FormData();
    gastos.append("descripcion", descripcion);
    gastos.append("total", total);
    gastos.append("categoria_gasto", categoria_gasto);

    // Realizar la solicitud AJAX
    $.ajax({
        url: "ajax/gastos.ajax.php",
        method: "POST",
        data: gastos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            // Cerrar el modal y actualizar la información
            $('#modal-gastos').modal('hide');
            getGastos();
        }
    });
}




