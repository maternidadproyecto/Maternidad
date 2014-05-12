jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-es-pre": function(a) {
        var esDatea = a.split('-');
        return (esDatea[2] + esDatea[1] + esDatea[0]) * 1;
    },
    "date-es-asc": function(a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
    "date-es-desc": function(a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});

$(document).ready(function() {
    var TAsignar = $('#tabla_asignar').dataTable({

        "bLengthChange": false,
        "bSort": false,
        "bStateSave": true,
        "bAutoWidth": false,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "bSearchable": false,
        "bInfo": false,
        "aaSorting": [],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sType": "date-es"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "8%"},
            {"sClass": "center", "sWidth": "40%"},
            {"sClass": "center","sWidth": "4%"}
        ]
    });

    var $frmasignar  = $('form#frmasignar');
    var $btn_nac     = $frmasignar.find('button:button#btn_nac');
    var $text_nac    = $frmasignar.find('input:text#text_nac');
    var $hnac        = $frmasignar.find('input:hidden#hnac');
    var $cedula_p    = $frmasignar.find('input:text#cedula_p');
    var $nombre      = $frmasignar.find('input:text#nombre');
    var $apellido    = $frmasignar.find('input:text#apellido');
    var $telefono    = $frmasignar.find('input:text#telefono');
    var $consultorio = $frmasignar.find('select#num_consultorio');
    var $fecha       = $frmasignar.find('input:text#fecha');
    var $btnaccion   = $frmasignar.find('input:button#btnaccion');
    var $btnlimpiar  = $frmasignar.find('input:button#btnlimpiar');
    var $btnbuscar   = $frmasignar.find('input:button#btnbuscar');
    var $btnimprimir = $frmasignar.find('input:button#btnimprimir');
    var $div_cedula  = $frmasignar.find('div#div_cedula');
    var $div_fecha   = $frmasignar.find('div#div_fecha');
    var $btn_nac     = $frmasignar.find('button:button#btn_nac');

    var val_cedula = '1234567890';

    $consultorio.select2();
    $cedula_p.validar(val_cedula);

    $fecha.datepicker({
        language: "es",
        format: 'dd-mm-yyyy',
        startDate: "+1d",
        daysOfWeekDisabled: "0,6",
        //todayBtn: "linked",
        autoclose: true,
        orientation: "top auto"
    }).on('changeDate', function(ev) {
        $div_fecha.removeClass('has-error');
    });

    $('#imgcedula').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>La C&eacute;dula no puede estar en <span class="alerta">blanco</span>,<br/> no debe tener menos de <span class="alerta">7 digitos</span>, ni comenzar con 0,<br/> debe seleccionar la nacionalidad en<span class="alerta"> (N)</span><br/> Haga Click en buscar par obtener los datos de la C&eacute;dula.'
    });

    $('#imgconsultorio').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido,<br/> indica el consultorio que se asignar&aacute; la fecha dela cita. '
    });
    $('#imgfechacita').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido,<br/>fecha asignada para la cita. '
    });
    $('#imgturno').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido,<br/> indica el o los turnos del consultorio. '
    });
    var url = '../../controlador/cita/asignar.php';

    $("ul#nacionalidad > li > span").click(function() {
        var nac = $(this).attr('id');
        if (nac != 'N') {
            $btn_nac.removeClass('btn-danger');
            if (nac == 'V') {
                var tamano = 10;
            } else {
                tamano = 11;
            }
            $hnac.val(nac);
            $cedula_p.attr('maxlength', tamano).val(nac + '-').focus();
        } else {
            $btnaccion.prop('disabled', true);
            $hnac.val('');
            $cedula_p.val('');
        }
    });

    $cedula_p.keypress(function(e) {
        var carac = $(this).val().length;
        if (e.which == 8 && carac < 3) {
            e.preventDefault();
        }
    });

    $btnbuscar.on('click', function() {
        
        $consultorio.select2('enable', true);
        $fecha.prop('disabled', false);
        $btnimprimir.prop('disabled', true);
        $consultorio.select2('focus');
        if ($hnac.val() == '') {
            $btn_nac.addClass('btn-danger');
            $btn_nac.focus();
        } else if ($cedula_p.val().length === 2) {
            $div_cedula.addClass('has-error');
            $cedula_p.focus();
        } else {
            var cedula_p = $cedula_p.val();
            $.post(url, {cedula_p: cedula_p, accion: 'BuscarDatos'}, function(data) {
                var cod_msg = parseInt(data.error_codmensaje);
                var mensaje = data.error_mensaje;
                if (cod_msg === 17) {
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                        $cedula_p.focus();
                        $nombre.val('');
                        $apellido.val('');
                        $telefono.val('');
                        TAsignar.fnClearTable();
                    });
                } else if (cod_msg === 15) {
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                        $cedula_p.focus();
                        $nombre.val('');
                        $apellido.val('');
                        $telefono.val('');
                        TAsignar.fnClearTable();
                    });
                } else {
                    var total = parseInt(data.total);
                    var asistencia = parseInt(data.asistencia);
                    var fech_max = data.fech_max;
                    $nombre.val(data.nombre);
                    $apellido.val(data.apellido);
                    $telefono.val(data.telefono);
                    $btnaccion.prop('disabled', false);
                    TAsignar.fnClearTable();
                    
                    var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    var img = modificar;
                    if (total == 1 && asistencia == 0 && fech_max == 0) {
                        //$btnaccion.prop('disabled',true);
                        $('table#tabla_asignar > thead > tr').find('th').eq(4).html('Eliminar');
                        var img = '<img class="eliminar" title="Eliminar" style="cursor: pointer" src="../../imagenes/datatable/eliminar.png" width="18" height="18" alt="Eliminar"/>';
                    
                    } else if (total === asistencia) {
                        var img = '';
                    }
                    var dias_max = 0;
                    
                    if (data.citas != 0) {
                        $btnimprimir.prop('disabled', false);
                        var datos = data.citas.split(',');
                        for (var i = 0; i < datos.length; i++) {
                            var citas = datos[i].split(';');
                            var turno = 'MAÑANA';
                            if (citas[4] == 2) {
                                turno = 'TARDE';
                            }
                            if (citas[1] == 1 && citas[0] < 0) {
                                img = 'Asistio';
                            }
                            if (citas[5] != '') {
                                img = '';
                            }
                            TAsignar.fnAddData([citas[2], citas[3], turno, citas[5], img]);
                        }
                    }else{
                       
                    }
                }
            }, 'json');
        }
    });

    $consultorio.on('change', function() {
        var num_consultorio = $(this).val();
        $('div.btn-group').find('label').removeClass('btn-success active').addClass('disabled btn-default');
        $.post(url, {num_consultorio: num_consultorio, accion: 'BuscarTurno'}, function(resultado) {
            var turnos = resultado.split(';');
            var manana = turnos[0];
            var tarde  = turnos[1];
            if (manana == 1) {
                $('label#l_manana').removeClass('disabled');
            }
            if (tarde == 1) {
                $('label#l_tarde').removeClass('disabled');
            }

        });
    });

    $('input:radio[name="turno"]').change(function() {
        
        $('div.btn-group').find('label').removeClass('btn-success').addClass('btn-default');
        var este = $(this);
        var padre = este.closest('label');
        var turno = este.attr('id');
        var id_padre = padre.attr('id');
        if (este.is(':checked')) {
            $('#'+id_padre).removeClass('btn-default');
            $('#'+id_padre).addClass('btn-success');
        }
    });

    $btnaccion.on('click', function() {
        var accion = $(this).val();
        var countTurno = $("input:radio[name='turno']:checked").length;
        if ($hnac.val() == '') {
            $btn_nac.addClass('btn-danger');
            $btn_nac.focus();
        } else if ($cedula_p.val().length === 2) {
            $div_cedula.addClass('has-error');
            $cedula_p.focus();
        } else if ($consultorio.val() == 0) {
            $consultorio.addClass('has-error');
        } else if ($fecha.val() === null || $fecha.val().length === 0 || /^\s+$/.test($fecha.val())) {
            $div_fecha.addClass('has-error');
            $fecha.focus();
        } else if (countTurno == 0) {
            window.parent.apprise('<span style="color:#FF0000">Debe seleccionar el turno del consultorio</span>', {'textOk': 'Aceptar'});
        } else {
            $('#accion').remove();
            var $accion = ' <input type="hidden" id="accion" name="accion" value="' + $(this).val() + '" />';
            $($accion).prependTo($frmasignar);
            
           
            var turno = $("input:radio[name='turno']:checked").closest('label').text();
       
            $.post(url, $frmasignar.serialize(), function(data) {
                var cantidad = TAsignar.fnGetData().length;
                var consultorio = $consultorio.find('option').filter(':selected').html();
                var modificar_cita = $('#modifcar_cita').val();

                var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                var eliminar = '<img class="eliminar" title="Eliminar" style="cursor: pointer" src="../../imagenes/datatable/eliminar.png" width="18" height="18" alt="Eliminar"/>';

                if (modificar_cita == 1) {
                    modificar = '';
                }
                if (cantidad == 0) {
                    modificar = eliminar;
                    $('table#tabla_asignar > thead > tr').find('th').eq(4).html('Elimiar');
                }
                var cod_msg = parseInt(data.error_codmensaje);
                var mensaje = data.error_mensaje;
                window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                if (cod_msg === 21) {
                    $btnaccion.prop('disabled', true);
                    TAsignar.fnAddData([$fecha.val(), consultorio, turno,'',modificar]);
                    $fecha.val('');
                    $consultorio.select2('val', 0);
                    $("input:radio[name='turno']").prop('checked',false);
                    $('div.btn-group').find('label').removeClass('btn-success active').addClass('btn-default disabled');

                }
            }, 'json');
        }

    });

    $('table#tabla_asignar').on('click', 'img.modificar', function() {
        var padre = $(this).closest('tr');
        var observacion = padre.children('td:eq(3)');
        var imagen_modificar = padre.children('td:eq(4)');
        var cedula_p = $cedula_p.val();
        window.parent.apprise('Indique una observaci&oacute;n', {'input': true, 'textOk': 'Aceptar', 'textCancel': 'Cancelar'}, function(respuesta) {
            if (respuesta != false) {
                $.post(url, {cedula_p: cedula_p, observacion: respuesta, accion: 'CancelarCita'}, function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;
                    if (cod_msg == 21) {
                        $('#modifcar_cita').val(1);
                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                        observacion.html(respuesta);
                        imagen_modificar.html('');
                        $btnaccion.prop('disabled', false);
                        $fecha.prop('disabled', false);
                        $consultorio.select2("enable", true);
                        $("#mensaje_tabla").fadeIn().html('Debe generar una nueva cita');
                    }

                }, 'json');
            }else{
                $('div.aInput input:text.aTextbox"').css('border','1px solid #FF0000');
            }
        });
    });


    $('table#tabla_asignar').on('click', 'img.eliminar', function() {
        var padre = $(this).closest('tr');
        var cedula_p = $cedula_p.val();
        var fecha = padre.children('td:eq(0)').html();
        window.parent.apprise('<span style="margin-left:20%;color:#FF0000">¿Desea Eliminar la cita?</span>', {'confirm': true, 'textOk': 'Aceptar', 'textCancel': 'Cancelar'}, function(r) {
            if (r) {
                $.post(url, {cedula_p: cedula_p, fecha: fecha, accion: 'EliminarCita'}, function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 23) {
                        $btnaccion.prop('disabled', false);
                        TAsignar.fnClearTable();
                    }
                }, 'json');
            }
        });
    });

    $btnlimpiar.click(function() {
        limpiar();
        TAsignar.fnClearTable();
    });

    $btnimprimir.click(function() {
        var url = '../reporte/rasignar_cita.php?cedula_p=' + $cedula_p.val();
        window.open(url);
        //window.open(url,'popup','width=700,height=800');
    });
});

function limpiar() {
    $('input:text').val('');
    $('#tr_turno').fadeOut();
    $('#accion').remove();
    $('#num_consultorio').select2('val', (0));
    $('button:button#btn_nac').removeClass('has-error');
    $("#consultorio_turno").fadeOut();
    $("#consultorio_turno tbody tr").delay(600).remove();
    $('div').removeClass('has-error');
    $("#mensaje_tabla").fadeOut();

}

