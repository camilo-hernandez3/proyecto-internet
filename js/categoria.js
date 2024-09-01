selectCategory = null;
categories = [];


getCategories();



function getCategories(){
    let datos = new FormData();
  
    datos.append('all', 'all');

    $.ajax({
        url: "ajax/categoria.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            renderCategories(response)

        }
    });
}

function renderCategories(data) {
    categories = JSON.parse(data);
    renderTableCategories();
}

function editCategoria(id_categoria){

    let datos = new FormData();

    datos.append("id_categoria", id_categoria);

    $.ajax({
        url: "ajax/categoria.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            renderDataCategories(response)
            $('.modal-title').text('Editar Categoria');
        }
    });

}

function renderDataCategories(data) {
   
    selectCategory = JSON.parse(data);

    let nombre = document.getElementById('categoria');
    nombre.value = selectCategory.nombre;
    $('#modal-form-categories').modal('show');

}


function guardarCategoria(){
    let categoria = document.getElementById("categoria").value
   
    if(selectCategory){
        //! Todo realizar edit category
        saveEditCategory(categoria);
        return;
    }

    createCategory(categoria);
   
}
function eliminarCategoria(id_categoria) {
    let datos = new FormData();
    datos.append("id_eliminar", id_categoria);
    Swal.fire({
        title: `¿Quieres borrar la categoria?`,

        showCancelButton: true,
        confirmButtonText: 'Aceptar',
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                url: "ajax/categoria.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    categories = categories.filter(c => c.id_categoria !== id_categoria)
                    renderTableCategories();
                }
            });  
           
        }
    })}

function saveEditCategory(nombre){
   
    let datos = new FormData();

    let editCategory = {
        ...selectCategory,
        nombre
    }

    datos.append('edit_category', JSON.stringify(editCategory));

    $.ajax({
        url: "ajax/categoria.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                title: "Categoría",
                text: "La categoría fue editado de forma exitosa",
                icon: "success",
                timer: 1500
            });

            categories = categories.map(c => {
                if (c.id_categoria === selectCategory.id_categoria) {
                    return {
                        ...c,
                        nombre: nombre,
                    }

                }
                return c
            })

            renderTableCategories();
            $('#modal-form-categories').modal('hide');

        }

    });
}



function createCategory(categoria){

    let datos = new FormData();
    datos.append("categoria", categoria);
    $.ajax({
        url: "ajax/categoria.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            categories.push({
                ...JSON.parse(response)
            })
            renderTableCategories();
            Swal.fire({
                title: "Generar categoria",
                text: "La categoria se guardó correctamente",
                icon: "success"
            });
            $('#modal-form-categories').modal('hide');
        }
    });

}

function renderTableCategories(){
    let tabla = document.getElementById("categories_table");

    let filas = tabla.getElementsByTagName("tr");


    for (var i = filas.length - 1; i > 0; i--) {
        tabla.deleteRow(i);
    }

    categories.forEach(c => {

        let nuevaFila = document.createElement("tr");
        nuevaFila.classList.add('text-center', 'text-uppercase', 'text-black', 'text-xs', 'font-weight-bolder');
      
        // Define el contenido de cada celda
        let contenidoCeldas = [
            c.nombre,
            c.estado === 1 ? '<span class="badge badge-sm bg-gradient-success">Stock disponible</span>': '<span class="badge badge-sm bg-gradient-danger">Stock no disponible</span>',
            ` <a data-bs-toggle="tooltip" title="Editar" class="text-primary font-weight-bold text-xs"  onclick="editCategoria(${c.id_categoria})" ><i class="fas fa-edit" style='font-size:24px'></i></a>`,
            `<a data-bs-toggle="tooltip" onclick='eliminarCategoria(${c.id_categoria})' title="Borrar" class="text-danger font-weight-bold text-xs"><i class="fas fa-trash" style='font-size:24px'></i></a>`
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