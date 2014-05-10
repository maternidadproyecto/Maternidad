$(document).ready(function() {
    var TConsultorio = $('#tabla_consultorio').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "10%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmconsultorio  = $('form#frmconsultorio');
    var $consultorio     = $frmconsultorio.find('input:text#consultorio');
    var $select_espe     = $frmconsultorio.find('select#especialidad');
    var $select_turno    = $frmconsultorio.find('select#turno');
    var $div_consultorio = $frmconsultorio.find('div#div_consultorio');
    var $btnaccion       = $frmconsultorio.find('input:button#btnaccion');

    $select_espe.select2();
    $select_turno.select2();

    $('#select_mostrar').select2();
    var letra = ' abcdefghijklmnñopqrstuvwxyzáéíóú123456789#/º,.';

    $consultorio.validar(letra);

    var cons_texto = '<span class="requerido">Requerido</span><br/>El campo consultorio no debe estar en <span class="alerta">blanco</span>,<br/> solo permite (<span class="alerta"> letras, n&uacute;meros</span>) y (<span class="alerta">#/º,.</span>), entre <span class="alerta">5 y 50</span> caracteres';
    $('#imgconsultorio').tooltip({
        html: true,
        placement: 'right',
        title: cons_texto
    });

    var espes_texto = '<span class="requerido">Requerido</span><br/>Indica la Especialidad para el consultorio';
    $('#imgespecialidad').tooltip({
        html: true,
        placement: 'left',
        title: espes_texto
    });

    var turno_texto = '<span class="requerido">Requerido</span><br/>Indica el Turno para el consultorio';

    $('#imgturno').tooltip({
        html: true,
        placement: 'right',
        title: turno_texto
    });


    var desde_texto = '<span class="requerido">Requerido</span><br/>Indica el horario de comienzo para el consultorio';

    $('#imgdesde').tooltip({
        html: true,
        placement: 'right',
        title: desde_texto
    });

    var hasta_texto = '<span class="requerido">Requerido</span><br/>Indica el horario de fin para el consultorio';

    $('#imghasta').tooltip({
        html: true,
        placement: 'left',
        title: hasta_texto
    });

    var url = '../../controlador/mantenimiento/consultorio.php';

    // evento click en btnaaccion

    $('input:checkbox[name^="turno"]').change(function() {
        var este  = $(this);
        var padre = este.closest('label');
        if (este.is(':checked')) {
            padre.addClass('btn-success');
        }else{
            padre.removeClass('btn-success');
        }
    });
    $btnaccion.on('click', function() {
        var countTurno = $("input:checkbox[name^='turno']:checked").length;

        if ($consultorio.val() === null || $consultorio.val().length === 0 || /^\s+$/.test($consultorio.val())) {
            $div_consultorio.addClass('has-error');
            $consultorio.focus();
        } else if ($select_espe.val() == 0) {
           $select_espe.addClass('has-error');
        } else if (countTurno == 0) {
            window.parent.apprise('Debe seleccionar un turno', {'textOk': 'Aceptar'});
        } else {

            
            $('#accion').remove();
            var $accion = '<input type="hidden" id="accion"  value="' + $(this).val() + '" name="accion">';
            $($accion).appendTo($frmconsultorio);

            if ($(this).val() === 'Agregar') {
                $('#num_consultorio').remove();
                var especilaidad = $select_espe.find('option').filter(':selected').text();
                var turnos = '';
                $("input:checkbox[name^='turno']:checked").each(function() {
                     var este  = $(this);
                     var padre = este.closest('label');
                     turnos += padre.text().trim() + ',';
                });
                turnos = turnos.substring(0, turnos.length - 1);
                
                var codigo   = 1;
                var TotalRow = TConsultorio.fnGetData().length;
                if(TotalRow > 0){
                    var lastRow   = TConsultorio.fnGetData(TotalRow - 1);
                    codigo    = parseInt(lastRow[0]) + 1;
                }
                var $cod_modulo = '<input type="hidden" id="num_consultorio"  value="' + codigo + '" name="num_consultorio">';
                $($cod_modulo).prependTo($frmconsultorio);
                
                var accion = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                accion += '&nbsp;&nbsp;<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

                $.post(url, $frmconsultorio.serialize(), function(data) {
  
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});

                    if (cod_msg === 21) {
                        TConsultorio.fnAddData([$consultorio.val(), especilaidad, turnos, accion]);
                        limpiar();
                    }

                }, 'json');
            } else {
                 var turnos = '';
                 $("input:checkbox[name^='turno']:checked").each(function() {
                     var este  = $(this);
                     var padre = este.closest('label');
                     turnos += padre.text().trim() + ',';
                });
                turnos = turnos.substring(0, turnos.length - 1);
                window.parent.apprise('&iquest;Desea Modificar los datos del registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $.post(url, $frmconsultorio.serialize(), function(data) {
                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
      
                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                            var fila = $("#fila").val();

                            if (cod_msg === 22) {
                                $("#tabla_consultorio tbody tr:eq(" + fila + ")").find("td:eq(0)").html($consultorio.val());
                                $("#tabla_consultorio tbody tr:eq(" + fila + ")").find("td:eq(1)").html(especilaidad);
                                $("#tabla_consultorio tbody tr:eq(" + fila + ")").find("td:eq(2)").html(turnos);

                                limpiar();
                            }

                        }, 'json');
                    }
                });
            }
        }
    });

    var $tabla = $('table#tabla_consultorio');
    
    $tabla.on('click', 'img.modificar', function() {
        
        limpiar();
      
        var $padre      = $(this).closest('tr');
        var fila        = $padre.index();
        var consultorio = $padre.children('td').eq(0).text();
        var turnos      = $padre.children('td').eq(2).text();
        var ar_turnos   =  turnos.split(',');
        $('#consultorio').val(consultorio);
        if (ar_turnos.length == 1) {
            if (ar_turnos[0] == 'MAÑANA') {
                $("input:checkbox#manana").prop('checked',true);
                $("form#frmconsultorio label#l_manana").addClass('btn-success active');
            }else{
                $("form#frmconsultorio label#l_tarde").addClass('btn-success active');
                $("input:checkbox#tarde").prop('checked',true);
            }
        } else {
           $("form#frmconsultorio label[id^=l_]").addClass('btn-success active');
           $("input:checkbox[name^='turno']").prop('checked',true);
        }
  
        $.post(url, {consultorio: consultorio, accion: 'BuscarDatos'}, function(respuesta) {
            $('#especialidad').select2('val',respuesta.cod_especialidad);
        },'json');
 
        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).prependTo($btnaccion);
        $btnaccion.val('Modificar');
    });

    var $btlimpiar = $('#btnlimpiar');

    $btlimpiar.on('click', function() {
        limpiar();
    });

});

function limpiar()
{
    $('#fila').remove();
    $('#num_consultorio').remove();
    $('#accion').remove();
    $('form#frmconsultorio input:text#consultorio').val('');
    $('form#frmconsultorio select#especialidad').select2('val', 0);
    $('form#frmconsultorio select#turno').select2('val', 0);
    $('select,div').removeClass('has-error');
    $('form#frmconsultorio input:button#btnaccion').val('Agregar');
    $("input:checkbox").prop('checked', false);
    $("input:checkbox + a").removeClass('checked');
    $("input:checkbox + a").parent('div').removeClass('disabled');
    $("input:checkbox[name^='turno']").prop('checked',false);
    $("form#frmconsultorio label[id^='l_']").removeClass('btn-success active');

}