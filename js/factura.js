
function viewPDF(id_venta){

   /*  let datos = new FormData();

    datos.append("id_venta", id_venta);

    $.ajax({
        url: "reports/venta.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
        }
    });
 */

    const url = `reports/venta.php?id_venta=${id_venta}`;

    // Abre una ventana emergente
    window.open(url, '_blank', 'width=800,height=600,scrollbars=yes');
    
}

