$(document).ready(function() {
    var TEspecialidad = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

   // asignar la caja de texto a una variable
    var $frmespecialidad  = $('form#frmespecialidad');
    var $especialidad     = $frmespecialidad.find('input:text#especialidad');
    var $div_especialidad = $frmespecialidad.find('div#div_especialidad');
    var $btnaccion        = $frmespecialidad.find('input:button#btnaccion')

    // caracteres permitidos para la caja de texto especialidad cuando escriba
    var letra = ' abcdefghijklmnñopqrstuvwxyzáéíóú-';

    // validación de la caja de texto especialidad mientras este escribiendo
    $especialidad.validar(letra);


    $('#imgespecialidad').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>El campo especialidad no debe estar en <span class="alerta">blanco</span>,<br/> solo permite (<span class="alerta"> letras y -, entre 5 y 50 caracteres</span>)'
    });


    var letras = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]{5,50}$/;

    var url = '../../controlador/mantenimiento/especialidad.php';
    $btnaccion.on('click', function() {

        $('#accion').remove();
        
        var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
        $($accion).prependTo($(this));

        var accion = $(this).val();
      
        if (accion === 'Agregar') {
            $('#accion').val(accion);
            if ($especialidad.val() === null || $especialidad.val().length === 0 || /^\s+$/.test($especialidad.val())) {
                $div_especialidad.addClass('has-error');
                $especialidad.focus();
            } else if (!letras.test($especialidad.val())) {
                $div_especialidad.addClass('has-error');
                $especialidad.focus();
            } else {
                var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                var eliminar  = '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';
                
                
                var codigo = 1;
                var TotalRow = TEspecialidad.fnGetData().length;
                if(TotalRow > 0){
                    var lastRow   = TEspecialidad.fnGetData(TotalRow-1);
                    var codigo    = parseInt(lastRow[0])+1;
                }

                var $cod_modulo = '<input type="hidden" id="cod_especialidad"  value="' + codigo + '" name="cod_especialidad">';
                $($cod_modulo).prependTo($frmespecialidad);
                $.post(url, $frmespecialidad.serialize(), function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo    = data.tipo_error;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 21) {
                         TEspecialidad.fnAddData([codigo, $especialidad.val(), modificar, eliminar]);
                         limpiar();
                         $('#cod_especialidad').remove();
                    }
                },'json');
            }
        } else {
            $('#accion').val('Modificar');

               window.parent.apprise('&iquest;Desea Modificar los datos del registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                    $.post(url, $frmespecialidad.serialize(), function(data) {

                        var cod_msg = parseInt(data.error_codmensaje);
                        var mensaje = data.error_mensaje;
                        var fila    = $("#fila").val();

                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'});

                        if (cod_msg === 22) {
                            $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(1)").html($especialidad.val());
                            limpiar();
                        }

                    }, 'json');
                }
            });
        }
    });

    // Modificar
    $('table#tabla').on('click', 'img.modificar', function() {

        $('#cod_especialidad').remove();
        $('#fila').remove();
        $('#accion').remove();

        var padre        = $(this).closest('tr');
        var fila         = padre.index();
        var cod_esp      = padre.children('td:eq(0)').text();
        var especialidad = padre.children('td').eq(1).html();

        $especialidad.val(especialidad);

        var $cod_esp = '<input type="hidden" id="cod_especialidad"  value="' + cod_esp + '" name="cod_especialidad">';
        $($cod_esp).prependTo($('form#frmespecialidad div#botones'));

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).prependTo($('form#frmespecialidad div#botones'));

        $btnaccion.val('Modificar');

    });

    $('table#tabla').on('click', 'img.eliminar', function() {

        limpiar();
        var padre            = $(this).closest('tr');
        var cod_especialidad = padre.children('td:eq(0)').text();
        var nRow             = $(this).parents('tr')[0];

        window.parent.apprise('&iquest;Desea Eliminar los datos del registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
            if (r) {
                $.post(url, {cod_especialidad: cod_especialidad, accion: 'Eliminar'}, function(data) {
                    var cod_msg = data.error_codmensaje;
                    var mensaje = data.error_mensaje;

                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});

                    if (cod_msg === 23) {
                        TEspecialidad.fnDeleteRow(nRow);
                    }

                    limpiar();
                }, 'json');
            }
        });
    });

    $('#btnlimpiar').on('click', function() {
        limpiar();
    });
});

function limpiar() {
    $('div').removeClass('has-error');
    $('#especialidad').val('');
    $('#cod_especialidad').remove();
    $('#fila').remove();
    $('#accion').remove();
    $('#btnaccion').val('Agregar');
}
