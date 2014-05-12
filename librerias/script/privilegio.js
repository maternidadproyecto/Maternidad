$(document).ready(function() {
    var TPrivilegio = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "4%"},
            {"sClass": "center"},
            {"sWidth": "1%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "1%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmprivilegio = $('form#frmprivilegio');
    var $privilegio    = $frmprivilegio.find('input:text#privilegio');
    var $btnaccion     = $frmprivilegio.find('input:button#btnaccion');
    var $btnlimpiar    = $frmprivilegio.find('input:button#btnlimpiar');
    
    //Ejecutar la accion a realizar Agregar o Modificar
    $btnaccion.on('click', function() {
        $('#accion').remove();
        var accion  = $(this).val();
        var $accion = ' <input type="hidden" id="accion" name="accion" value="'+accion+'" />';
        $($accion).prependTo( $(this));
        if (accion === 'Agregar') {
            add($frmprivilegio, TPrivilegio);
        } else {
            edit($frmprivilegio, TPrivilegio);
        }
    });
    
    // para buscar los datos  modidificar
    
    $('table#tabla').on('click', 'img.modificar', function() {
        
        $('span.error_val').fadeOut();
        $('#codigo_privilegio').remove();
        $('#fila').remove();
        $('#accion').remove();

        var fila              = $(this).parent("td").parent().parent().children().index($(this).parent("td").parent());
        var codigo_privilegio = $(this).parents('tr').children('td:eq(0)').text();
        var privilegio        = $(this).parents('tr').children('td').eq(1).html();
        
        $privilegio.val(privilegio);

        var $codigo_privilegio = '<input type="hidden" id="codigo_privilegio"  value="' + codigo_privilegio + '" name="codigo_privilegio">';
        $($codigo_privilegio).prependTo($btnaccion);

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).prependTo($btnaccion);

        $('#btnaccion').val('Modificar');
    });
    
    // Eliminar Registros
    
     $('table#tabla').on('click', 'img.eliminar', function() {
         
        $('span.error_val').fadeOut();
        $('#codigo_privilegio').remove();
        $('#fila').remove();
        $('#accion').remove();
        $btnaccion.val('Agregar');
        $privilegio.val('');

        var nRow = $(this).parents('tr')[0];
        var codigo_privilegio = $(this).parents('tr').children('td:eq(0)').text();
        
        del(codigo_privilegio, nRow, TPrivilegio);
    });
    $btnlimpiar.on('click',function(){
        limpiar();
    });
    
    var limpiar = function(){
        $('span.error_val').fadeOut();
        $('#codigo_privilegio').remove();
        $('#fila').remove();
        $('#accion').remove();
        $privilegio.val('');
        $btnaccion.val('Agregar');
    }

  });

function validar(campo) {
    if (requerido(campo) === false) {
        $('.error').fadeOut();
        var msgerror = 'Campo obligatorio';
        msgError(campo, msgerror);
        return  false;
    } else if (validacion(campo, 'letra') === false) {
        var msgerror = 'Debe ser mayor a 3 caracteres';
        msgError(campo, msgerror);
        return  false;
    } else {
        $('span.error_val').fadeOut();
        return  true;
    }
}

var url = 'controlador/seguridad/privilegio.php';
function add(formulario, tabla) {

    var $privilegio = formulario.find('input:text#privilegio');

    if (validar($privilegio) === true){
        var modificar = '<img class="modificar" title="Modificar"         style="cursor: pointer" src="imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
        var eliminar  = '<img class="eliminar"  title="Eliminar"          style="cursor: pointer" src="imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';
        $.post(url, formulario.serialize(), function(data) {

            var cod_msg = parseInt(data.error_codmensaje);
            var mensaje = data.error_mensaje;
            var tipo = data.tipo_error;

            jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
            if (cod_msg === 21) {
                $('#accion').remove();
                var codigo = tabla.fnGetData().length;
                codigo = codigo + 1;
                tabla.fnAddData([codigo, $privilegio.val(), modificar, eliminar]);
                $privilegio.val('');
            }
        }, 'json');
    }
}

function edit(formulario, tabla) {

    var $privilegio = formulario.find('input:text#privilegio');
    if (validar($privilegio) === true) {
        jConfirm('¿Desea Modificar los Datos?', 'Confirmación', function(respuesta) {
            if (respuesta === true) {
                $.post(url, formulario.serialize(), function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo    = data.tipo_error;

                    jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');

                    if (cod_msg === 22) {

                        var fila = $("#fila").val();

                        tabla.fnUpdate($privilegio.val(), parseInt(fila), 1);
                        $privilegio.val('');
                        $('#codigo_privilegio').remove();
                        $('#fila').remove();
                        $('#accion').remove();
                        $('#btnaccion').val('Agregar');
                    }
                }, 'json');
            }
        });
     }
}

function del(codigo_privilegio, nfila, tabla) {
    jConfirm('¿Desea Eliminar los Datos?', 'Confirmación', function(respuesta) {
        if (respuesta === true) {
            $.post(url, {codigo_privilegio: codigo_privilegio, accion: 'Eliminar'}, function(data) {
  
                var cod_msg = parseInt(data.error_codmensaje);
                var mensaje = data.error_mensaje;
                var tipo    = data.tipo_error;

                jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
                if (cod_msg === 23) {
                    tabla.fnDeleteRow(nfila);
                }
            }, 'json');
        }
    });
}