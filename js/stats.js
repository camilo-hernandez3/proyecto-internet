function printProductsPDF(divName){

    let printContents;
    let popupWin;
    printContents = document.getElementById(divName).innerHTML;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html lang="es">
        <head>
          <title>Tienda del Soldado</title>
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

function viewPDFVentas(id_usuario) {
    const url = `reports/venta_rango.php?id_usuario=${id_usuario}&fecha_inicio=${fecha_inicio}&fecha_final=${fecha_final}`;

    // Abre una ventana emergente
    window.open(url, '_blank', 'width=800,height=600,scrollbars=yes');
}

function validarCantidad(input) {
    if (input.value < 0) {
        input.value = 0;
    }
}

