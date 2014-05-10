$(document).ready(function() {
    var THistoria = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmhistoria   = $('form#frmhistoria');
    var $btn_nac       = $frmhistoria.find('button:button#btn_nac');
    var $hnac          = $frmhistoria.find('input:hidden#hnac');
    
    var $btn_nac_m     = $frmhistoria.find('button:button#btn_nac_m');
    var $hnac_m        = $frmhistoria.find('input:hidden#hnac_m');
    
    var $historia      = $frmhistoria.find('input:text#historia');
    var $hhistoria     = $frmhistoria.find('input:hidden#hhistoria');
    var $cedula_p      = $frmhistoria.find('input:text#cedula_p');
    var $cedula_pm     = $frmhistoria.find('input:text#cedula_pm');
    var $nombre        = $frmhistoria.find('input:text#nombre');
    var $apellido      = $frmhistoria.find('input:text#apellido');
    var $fecha         = $frmhistoria.find('input:text#fecha_nacimiento');
    var $cita          = $frmhistoria.find('input:text#fecha_cita');
    var $edad          = $frmhistoria.find('input:text#edad');
    var $tamano        = $frmhistoria.find('input:text#tamano');
    var $consultorio   = $frmhistoria.find('select#num_consultorio');
    var $peso          = $frmhistoria.find('input:text#peso');
    var $tension       = $frmhistoria.find('input:text#tension');
    var $fur           = $frmhistoria.find('input:text#fur');
    var $fpp           = $frmhistoria.find('input:text#fpp');
    var $lugar_control = $frmhistoria.find('textarea#lugar_control');
    var $diagnostico   = $frmhistoria.find('textarea#diagnostico');
    var $observacion   = $frmhistoria.find('textarea#observacion');
    var $btnaccion     = $frmhistoria.find('input:button#btnaccion');
    var $btnlimpiar    = $frmhistoria.find('input:button#btnlimpiar');
    var $btnbuscar     = $frmhistoria.find('input:button#btnbuscar');
    var $btnbuscar_m   = $frmhistoria.find('input:button#btnbuscar_m');
    var $btnver        = $frmhistoria.find('input:button#btnver');
    var $div_cedula    = $frmhistoria.find('div#div_cedula');
    var $div_cedula_pm = $frmhistoria.find('div#div_cedula_pm');
    var val_cedula     = '1234567890';
    var val_direccion  = ' abcdefghijklmnopqrstuvwyzáéíóúñ#/º-';
    var val_fecha      = '1234567890\/\/';
    var val_tamano     = ' mc1234567890\.\,';
    var val_peso       = ' kg1234567890\.';
    var val_peso       = ' abcdefghijklmnopqrstuvwxyz1234567890\-';
    
    $cedula_p.validar(val_cedula);
    $lugar_control.validar(val_direccion);
    $diagnostico.validar(val_direccion);
    $observacion.validar(val_direccion);
    $fur.validar(val_fecha);
    $fpp.validar(val_fecha);
    $tamano.validar(val_tamano);
    $peso.validar(val_peso);
    $tension.validar(val_peso);
    
    $consultorio.select2();
    
    $fur.datepicker({
        language: "es",
        format: 'dd/mm/yyyy',
        startDate: "-9m",
        endDate: "Hoy",
        todayBtn: "linked",
        autoclose: true,
        orientation: "top auto"
    });

    $fpp.datepicker({
        language: "es",
        format: 'dd/mm/yyyy',
        startDate: "Hoy",
        endDate: "+9m",
        todayBtn: "linked",
        autoclose: true,
        orientation: "top auto"
    });

    $('#imgcedula').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>La C&eacute;dula no puede estar en <span class="alerta">blanco</span>,<br/> no debe tener menos de <span class="alerta">7 digitos</span>, ni comenzar con 0,<br/> debe seleccionar la nacionalidad en<span class="alerta"> (N)</span><br/> Haga Click en buscar par obtener los datos de la C&eacute;dula'
    });
    
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
    
    
    $("ul#nacionalidad_m > li > span").click(function() {
        var nac = $(this).attr('id');
        if (nac != 'N') {
            $btn_nac_m.removeClass('btn-danger');
            if (nac == 'V') {
                var tamano = 10;
            } else {
                tamano = 11;
            }
            $hnac_m.val(nac);
            $cedula_pm.attr('maxlength', tamano).val(nac + '-').focus();
        } else {
            
            $hnac_m.val('');
            $cedula_pm.val('');
        }
    });
    
    
    $cedula_p.keypress(function(e) {
        var carac = $(this).val().length;
        if (e.which == 8 && carac < 3) {
            e.preventDefault();
        }
    });
    
    $cedula_pm.keypress(function(e) {
        var carac = $(this).val().length;
        if (e.which == 8 && carac < 3) {
            e.preventDefault();
        }
    });
    var url = '../../controlador/paciente/historia.php';
    $btnbuscar.on('click', function() {
     
        var cedula_p = $cedula_p.val();
        $tamano.prop('disabled', false);
        $peso.prop('disabled', false);
        $tension.prop('disabled', false);
        $fpp.prop('disabled', false).prop('readonly', true).css('background-color', '#FFFFFF');
        $fur.prop('disabled',false).prop('readonly',true).css('background-color','#FFFFFF');
        $lugar_control.prop('disabled', false);
        $diagnostico.prop('disabled', false);
        $observacion.prop('disabled', false);
        $btnaccion.prop('disabled', false);
        $consultorio.select2('focus');
        $btnver.fadeOut('slow');
        if (cedula_p != '') {
            $.post(url, {cedula_p: cedula_p, accion: 'BuscarDatos'}, function(resultado) {
                    
                if(resultado.error == 'error'){
                     var mensaje = resultado.mensaje;
                     window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                        $div_cedula.addClass('has-error');
                        $cedula_p.focus();
                    });
                }else{
                    $historia.val(resultado[0]);
                    $nombre.val(resultado[1]);
                    $apellido.val(resultado[2]);
                    $fecha.val(resultado[3]);
                    $edad.val(resultado[4]);
                    $fur.val(resultado[5]);
                    $fpp.val(resultado[6]);
                    $lugar_control.val(resultado[7]);
                    $cita.val(resultado[8]);
                    if(resultado[15] == 0){
                        $btnver.fadeIn('slow');
                        $btnaccion.prop('disabled',true);
                        /*$tamano.val(resultado[9]).prop('disabled',true);
                        $peso.val(resultado[10]).prop('disabled',true);
                        $tension.val(resultado[11]).prop('disabled',true);
                        $diagnostico.val(resultado[12]).prop('disabled',true);
                        $observacion.val(resultado[13]).prop('disabled',true);*/
                    }
                    
                    /*if(resultado[15] > 0){
                        $btnver.fadeIn('slow');
                    }*/
                }
                
            },'json');
        } else {
            window.parent.apprise('Debe ingresar el Num&eacute;ro de C&eacute;dula', {'textOk': 'Aceptar'}, function() {
                $div_cedula.addClass('has-error');
                $cedula_p.focus();
            });
        }
    });

    
    
    var url_m = '../../controlador/medico/medico.php';
    $btnbuscar_m.on('click', function() {
        var cedula_pm = $cedula_pm.val();
        if (cedula_pm != '') {
            $.post(url_m, {cedula_pm: cedula_pm, accion: 'BuscarDatos'}, function(resultado) {
                if(resultado != 0){
                    var datos   = resultado.split(';');
                    $('#nombre_m').val(datos[0]+' '+datos[1]);
                }else{
                   window.parent.apprise('<span style="color:#FF0000">El N&uacute;mero de C\u00e9dula no se encuentra registrado</span>', {'textOk': 'Aceptar'}, function() {
                        $div_cedula_pm.addClass('has-error');
                        $cedula_pm.focus();
                    }); 
                }
            });
        } else {
            window.parent.apprise('Debe ingresar el Num&eacute;ro de C&eacute;dula', {'textOk': 'Aceptar'}, function() {
                $div_cedula_pm.addClass('has-error');
                $cedula_pm.focus();
            });
        }
    });
    
    $('#otros_dt span').click(function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        $('span:not('+id+')').css('border-top','').tab('show');
    });
    $btnaccion.click(function() {
        //;

        if ($cedula_p.val().length === 2) {
            $div_cedula.addClass('has-error');
            $cedula_p.focus();
        } else if ($consultorio.val() == 0) {
            $consultorio.addClass('has-error');
        } else if ($hnac_m.val() == '') {
            $btn_nac_m.addClass('btn-danger');
            $btn_nac_m.focus();
        }else if ($cedula_pm.val().length === 2) {
            $div_cedula_pm.addClass('has-error');
            $cedula_pm.focus();
        }else if($tamano.val() === null || $tamano.val().length === 0 || /^\s+$/.test($tamano.val())) {
            $('#s_medicos').css('border-top','2px solid #FF0000').tab('show');
            $('#div_tamano').addClass('has-error');
            $tamano.focus();
        }else if($peso.val() === null || $peso.val().length === 0 || /^\s+$/.test($peso.val())) {
            $('#s_medicos').css('border-top','2px solid #FF0000').tab('show');
            $('#div_peso').addClass('has-error');
            $peso.focus();
        }else if($tension.val() === null || $tension.val().length === 0 || /^\s+$/.test($tension.val())) {
            $('#s_medicos').css('border-top','2px solid #FF0000').tab('show');
            $('#div_tension').addClass('has-error');
            $tension.focus();
        } else {
            $('#accion').remove();
            var accion = $(this).val();
            
            var $accion = ' <input type="hidden" id="accion" name="accion" value="' + accion + '" />';

            $($accion).prependTo($(this));
            $('#historia').prop('disabled',false);
            
            $.post(url, $frmhistoria.serialize(), function(data) {
                $('#historia').prop('disabled',true);
                var cod_msg = parseInt(data.error_codmensaje);
                var mensaje = data.error_mensaje;

                window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                if (cod_msg == 21) {
                    limpiar();
                    $('#otros_dt span').css('border-top','1px solid #ddd;');
                    $consultorio.select2('val', 0);
                }
            }, 'json');
        }
    });

    $btnlimpiar.click(function() {
        limpiar();
    });
    $btnver.click(function() {
        var url = '../reporte/ver_historia.php?cedula_p='+$cedula_p.val();
        window.open(url);
    });
});

function limpiar()
{
    $('input:text,textarea,hidden').val('');
    $('#accion').remove();
    $('#fila').remove();
}