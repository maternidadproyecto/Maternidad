$(document).ready(function() {
    var TMedico = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });


    var $frmpersonalmedico = $('form#frmpersonalmedico');
    var $hnac              = $frmpersonalmedico.find('input:hidden#hnac');
    var $div_cedula        = $frmpersonalmedico.find('div#div_cedula');
    var $cedula_pm         = $frmpersonalmedico.find('input:text#cedula_pm');
    var $div_nombre        = $frmpersonalmedico.find('div#div_nombre');
    var $nombre            = $frmpersonalmedico.find('input:text#nombre');
    var $div_apellido      = $frmpersonalmedico.find('div#div_apellido');
    var $apellido          = $frmpersonalmedico.find('input:text#apellido');
    var $div_telefono      = $frmpersonalmedico.find('div#div_telefono');
    var $hcod_telefono     = $frmpersonalmedico.find('input:hidden#hcod_telefono');
    var $cod_telefono      = $frmpersonalmedico.find('input:text#cod_telefono');
    var $telefono          = $frmpersonalmedico.find('input:text#telefono');
    var $div_direccion     = $frmpersonalmedico.find('div#div_direccion');
    var $direccion         = $frmpersonalmedico.find('textarea#direccion');
    var $especialidad      = $frmpersonalmedico.find('select#cod_esp');
    var $consultorio       = $frmpersonalmedico.find('select#num_cons');
    var $turno             = $frmpersonalmedico.find('select#turno');
    var $btnaccion         = $frmpersonalmedico.find('input:button#btnaccion');
    var $btn_nac           = $frmpersonalmedico.find('button:button#btn_nac');
    var $btn_codlocal      = $frmpersonalmedico.find('button:button#btn_codlocal');

    $especialidad.select2();
    $consultorio.select2();
    $turno.select2();

    // caracteres permitidos para la caja de texto sector cuando escriba
    var val_cedula    = '1234567890';
    var val_letra     = ' abcdefghijklmnopqrstuvwxyzáéíóúñ';
    var val_direccion = ' abcdefghijklmnopqrstuvwxyzáéíóúñ#/º-1234567890';

    // validación de la caja de texto sector mientras este escribiendo
    $cedula_pm.validar(val_cedula);
    $nombre.validar(val_letra);
    $apellido.validar(val_letra);
    $telefono.validar(val_cedula);
    $direccion.validar(val_direccion);

    $('#imgcedula').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>La C&eacute;dula no puede estar en <span class="alerta">blanco</span>,<br/> no debe tener menos de <span class="alerta">7 digitos</span>, ni comenzar con 0,<br/> debe seleccionar la nacionalidad en<span class="alerta"> (N)</span>'
    });

    $('#imgnombre').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>El Nombre no debe estar en <span class="alerta">blanco</span>,<br/> solo acepta<span class="alerta"> letras</span>'
    });

    $('#imgapellido').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>El Apellido no debe estar en <span class="alerta">blanco</span>,<br/> solo acepta<span class="alerta"> letras</span>'
    });

    $('#imgtelefono').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>El Tel&eacute;fono no puede estar en <span class="alerta">blanco</span>,<br/>no debe tener menos de <span class="alerta">9 digitos</span> ,<br/>debe seleccionar el C&oacute;digo <span class="alerta">(Cod)</span>'
    });

    $('#imgdireccion').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>La Direcci&oacute;n no debe estar en <span class="alerta">blanco</span><br/> caracteres permitidos<span class="alerta"> #/º-</span><br/>no debe tener menos de <span class="alerta">10 caracteres</span> '
    });

    $('#imgespecialidad').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });

    $('#imgconsutorio').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });
    $('#imgturno').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });

    var url = '../../controlador/medico/medico.php';
    
    $("ul#nacionalidad > li > span").on('change',function(){
        $(this).removeClass('btn-danger')
    });
    $("ul#nacionalidad > li > span").click(function() {
        var nac = $(this).attr('id');
        $cedula_pm.val('');
        if (nac != 'N') {
            $btn_nac.removeClass('btn-danger');
            if (nac == 'V') {
                var tamano = 10;
            } else {
                tamano = 11;
            }
            $hnac.val(nac);
            $cedula_pm.attr('maxlength', tamano).val(nac + '-').focus();
        } else {
            $hnac.val('');
        }
        
    });

    $cedula_pm.keypress(function(e) {
        var carac = $(this).val().length;
        if (e.which == 8 && carac < 3) {
            e.preventDefault();
        } 
    });
    
    $telefono.keypress(function(e) {
        var carac = $(this).val().length;
        if (e.which == 8 && carac < 6) {
            e.preventDefault();
        } 
    });
    $("ul#cod_local > li > span").click(function() {

        var cod_local = $(this).text();
        var id = $(this).attr('id');
        if (cod_local != 'Cod') {
            $btn_codlocal.removeClass('btn-danger');
            $hcod_telefono.val(id);
            $telefono.val(cod_local + '-').focus();
        } else {
            $hcod_telefono.val('');
            $telefono.val('');
        }
    });

    $btnaccion.on('click', function() {

        if ($hnac.val() == '') {
            $btn_nac.addClass('btn-danger');
            $btn_nac.focus();
        } else if ($cedula_pm.val().length === 2) {
            $div_cedula.addClass('has-error');
            $cedula_pm.focus();
        } else if ($nombre.val() === null || $nombre.val().length === 0 || /^\s+$/.test($nombre.val())) {
            $div_nombre.addClass('has-error');
            $nombre.focus();
        } else if ($apellido.val() === null || $apellido.val().length === 0 || /^\s+$/.test($apellido.val())) {
            $div_apellido.addClass('has-error');
            $apellido.focus();
        } else if ($hcod_telefono.val() == '') {
            $btn_codlocal.addClass('btn-danger');
            $btn_codlocal.focus();
        } else if ($telefono.val().length === 5) {
            $div_telefono.addClass('has-error');
            $telefono.focus();
        } else if ($especialidad.val() == 0) {
            $especialidad.addClass("has-error");
        } else if ($direccion.val() === null || $direccion.val().length === 0 || /^\s+$/.test($direccion.val())) {
            $div_direccion.addClass('has-error');
            $direccion.focus();
        } else {
            $('#accion').remove();

            var accion = $(this).val();
            var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
            $($accion).prependTo($(this));

            var especialidad = $especialidad.find('option').filter(":selected").text();
            var consultorio = $consultorio.find('option').filter(":selected").text();
            var turno        = $turno.find('option').filter(":selected").text();
            if (accion === 'Agregar') {
                $('#accion').val('Agregar');
                $.post(url, $frmpersonalmedico.serialize(), function(data) {
                    var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg == 21) {
                        TMedico.fnAddData([$cedula_pm.val(),$nombre.val(),$apellido.val(),$telefono.val(),modificar]);
                        limpiar();
                    }

                }, 'json');
            } else {
                $('#accion').val('Modificar');

                window.parent.apprise('&iquest;Desea Modificar los datos del registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $cedula_pm.prop('disabled',false);
                        $.post(url, $frmpersonalmedico.serialize(), function(data) {
                        $cedula_pm.prop('disabled',true);
                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
                           
                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});

                            var fila = $('#fila').val();

                            if (cod_msg == 22) {
                                $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(3)").text($telefono.val());
                            }

                            limpiar();
                        }, 'json');
                    }

                });
            }
        }
    });

    $('table#tabla').on('click', 'img.modificar', function() {

        limpiar();
        var padre           = $(this).closest('tr');
        var fila            = padre.index();
        var cedula_completa = padre.children('td:eq(0)').text();
        var nombre          = padre.children('td:eq(1)').text();
        var apellido        = padre.children('td:eq(2)').text();
        var telefono        = padre.children('td:eq(3)').text();
        
        var arr_ced    = cedula_completa.split('-');
        var arr_tel    = telefono.split('-');
        $btn_nac.prop('disabled', true);
        $hnac.val(arr_ced[0]);
        $hcod_telefono.val(arr_tel[0]);
        $cedula_pm.prop('disabled', true);
        $cedula_pm.val(cedula_completa);
        $nombre.val(nombre);
        $apellido.val(apellido);
        $telefono.val(telefono);
        $('#fila').remove();
       
       var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).appendTo('#btnaccion');
        $.post(url, {cedula_pm: cedula_completa, accion: 'BuscarDatos'}, function(respuesta) {
            $especialidad.select2('val',respuesta.especialidad);
            $hcod_telefono.val(respuesta.cod_telefono);
            $direccion.val(respuesta.direccion);
        }, 'json');
        $('#btnaccion').val('Modificar');

    });
    $('#btnlimpiar').on('click', function() {
        limpiar();
    });
});


function limpiar() {

    $('div,select').removeClass('has-error');
    $('input:text,textarea').val('');
    $('#btn_nac,#btn_codlocal').prop('disabled', false).removeClass('btn-danger');
    $('#cedula_pm,#nombre,#apellido,#txt_nac').prop('disabled', false);
    $('#accion').remove();
    $('select').select2('val', 0);
    $('select').select2('val', 0);
    $('select#num_cons').find('option:gt(0)').remove().end();
    $('select#turno').find('option:gt(0)').remove().end();
    $('#btnaccion').val('Agregar');
}

