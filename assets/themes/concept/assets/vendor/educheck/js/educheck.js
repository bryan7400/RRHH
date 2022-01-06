function unhighlight(element, errorClass, validClass) {
    $(element).parents('.control-group').removeClass('error');
    $(element).parents('.control-group').addClass('');
};

function highlight(element, errorClass, validClass) {
    $(element).parents('.control-group').addClass('error');
};

/*function highlight (element, errorClass, validClass) {
	$(element).parents('.control-group').addClass('error');
}
function unhighlight (element, errorClass, validClass) {
	$(element).parents('.control-group').removeClass('error');
	$(element).parents('.control-group').addClass('success');
}*/

//Traducción para el dataTable
var dataTableTraduccion = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

// ============================================================== 
// tooltip
// ============================================================== 
if ($('[data-toggle="tooltip"]').length) {
            
    $('[data-toggle="tooltip"]').tooltip()

}

