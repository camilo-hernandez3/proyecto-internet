function ProductsByCategory() {

    let datos = new FormData();

    datos.append("all", "all");

    $.ajax({
        url: "ajax/factura.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {

        }

    });

}

function changeStatus(id) {

    let datos = new FormData();
    datos.append("id_factura", id);

    Swal.fire({
        title: `¿Quieres realizar el pago de la factura?`,
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "ajax/factura.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire({
                        title: "Compra",
                        text: "La compra se realizo de forma exitosa",
                        icon: "success",
                        timer: 1500
                    });
                    location.reload();

                }
            })
        }
    });

}

function viewPDFCompra(id_compra) {

    const url = `reports/compra.php?id_compra=${id_compra}`;

    // Abre una ventana emergente
    window.open(url, '_blank', 'width=800,height=600,scrollbars=yes');

}