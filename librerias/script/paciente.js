
$(document).ready(function() {
    var TPaciente = $('#tabla_paciente').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "8%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmpaciente      = $('form#frmpaciente');
    var $text_nac         = $frmpaciente.find('input:text#text_nac');
    var $hnac             = $frmpaciente.find('input:hidden#hnac');
    var $cedula_p         = $frmpaciente.find('input:text#cedula_p');
    var $nombre           = $frmpaciente.find('input:text#nombre');
    var $fecha_nacimiento = $frmpaciente.find('input:text#fecha_nacimiento');
    var $apellido         = $frmpaciente.find('input:text#apellido');
    var $div_cedula       = $frmpaciente.find('div#div_cedula');
    var $div_nombre       = $frmpaciente.find('div#div_nombre');
    var $div_apellido     = $frmpaciente.find('div#div_apellido');
    var $div_fecha        = $frmpaciente.find('div#div_fecha');
    var $hcod_telefono    = $frmpaciente.find('input:hidden#hcod_telefono');
    var $cod_telefono     = $frmpaciente.find('input:text#cod_telefono');
    var $telefono         = $frmpaciente.find('input:text#telefono');
    var $div_telefono     = $frmpaciente.find('div#div_telefono');
    var $hcod_celular     = $frmpaciente.find('input:hidden#hcod_celular');
    var $cod_celular      = $frmpaciente.find('input:text#cod_celular');
    var $celular          = $frmpaciente.find('input:text#celular');
    var $div_celular      = $frmpaciente.find('div#div_celular');
    var $municipio        = $frmpaciente.find('select#municipio');
    var $sector           = $frmpaciente.find('select#sector');
    var $direccion        = $frmpaciente.find('textarea#direccion');
    var $div_direccion    = $frmpaciente.find('div#div_direccion');
    var $btn_nac          = $frmpaciente.find('button:button#btn_nac');
    var $btn_codlocal     = $frmpaciente.find('button:button#btn_codlocal');
    var $btn_codcel       = $frmpaciente.find('button:button#btn_codcel');
    var $cod_celular      = $frmpaciente.find('input:text#cod_celular');
    var $btnaccion        = $frmpaciente.find('input:button#btnaccion');
    var $btnlimpiar       = $frmpaciente.find('input:button#btnlimpiar');

    $municipio.select2();
    $sector.select2();

    $fecha_nacimiento.datepicker({
        language: "es",
        format: 'dd-mm-yyyy',
        startDate: "-60y",
        endDate: "-9y",
        autoclose: true
    }).on('changeDate', function(ev){
        $div_fecha.removeClass('has-error');
    });

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
        title: '<span class="requerido">Requerido</span><br/>El Tel&eacute;fono no puede estar en <span class="alerta">blanco</span>,<br/>no debe tener menos de <span class="alerta">7 digitos</span> ,<br/>debe seleccionar el C&oacute;digo <span class="alerta">(Cod)</span>'
    });
    
    $('#imgfechanaci').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });
    $('#imgcelular').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>El Celular no puede estar en <span class="alerta">blanco</span>,<br/>no debe tener menos de <span class="alerta">7 digitos</span> ,<br/>debe seleccionar el C&oacute;digo <span class="alerta">(Cod)</span>'
    });
    
    $('#imgdireccion').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>La Direcci&oacute;n no debe estar en <span class="alerta">blanco</span><br/> caracteres permitidos<span class="alerta"> #/º-</span><br/>no debe tener menos de <span class="alerta">10 caracteres</span> '
    });
    
    $('#imgmunicipio').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });
    
    $('#imgsector').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });
    
    // caracteres permitidos para la caja de texto sector cuando escriba
    var val_cedula    = '1234567890';
    var val_letra     = ' abcdefghijklmnopqrstuvwxyzáéíóúñ';
    var val_direccion = ' abcdefghijklmnopqrstuvwxyzáéíóúñ#/º-1234567890';

    // validación de la caja de texto sector mientras este escribiendo
    $cedula_p.validar(val_cedula);
    $nombre.validar(val_letra);
    $apellido.validar(val_letra);
    $telefono.validar(val_cedula);
    $celular.validar(val_cedula);
    $direccion.validar(val_direccion);

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
    
    $("ul#cod_local > li > span").click(function() {
        
        $hcod_telefono.val('');
        $telefono.val('');
        var id = $(this).attr('id');
        var cod_local = $(this).text();

        if (id != 0) {
            $btn_codlocal.removeClass('btn-danger');
            $hcod_telefono.val(id);
            $telefono.attr('maxlength', '12').val(cod_local+'-').focus();
        }
    });
    
    $telefono.keypress(function(e) {
        var carac = $(this).val().length;
        if (e.which == 8 && carac < 6) {
            e.preventDefault();
        } 
    });
    
    $("ul#cod_cel > li > span").click(function() {
        
        $hcod_celular.val('');
        $celular.val('');
        var cod_celular = $(this).text();
        var id = $(this).attr('id');
        if (id != 0) {
            $btn_codcel.removeClass('btn-danger');
            $hcod_celular.val(id);
            $celular.attr('maxlength', '12').val(cod_celular+'-').focus();
        }
    });
    
    $celular.keypress(function(e) {
        var carac = $(this).val().length;
        if (e.which == 8 && carac < 6) {
            e.preventDefault();
        } 
    });
    
    var url = '../../controlador/paciente/paciente.php';
    
    $municipio.on('change', function() {
        var codigo_municipio = $(this).val();
        $sector.select2('val',0);
        $sector.find('option:gt(0)').remove().end();
        if (codigo_municipio > 0) {
            $.post(url, {codigo_municipio: codigo_municipio, accion: 'Sector'}, function(data) {
                var option = "";
                $.each(data, function(i) {
                    option += "<option value=" + data[i].cod_sector + ">" + data[i].sector + "</option>";
                });
                $sector.append(option);
            }, 'json');
        }
    });
 
    $btnaccion.on('click', function() {
        
        if ($hnac.val() == '') {
            $btn_nac.addClass('btn-danger');
            $btn_nac.focus();
        } else if ($cedula_p.val().length === 2) {
            $div_cedula.addClass('has-error');
            $cedula_p.focus();
        } else if ($nombre.val() === null || $nombre.val().length === 0 || /^\s+$/.test($nombre.val())) {
            $div_nombre.addClass('has-error');
            $nombre.focus();
        } else if ($apellido.val() === null || $apellido.val().length === 0 || /^\s+$/.test($apellido.val())) {
            $div_apellido.addClass('has-error');
            $apellido.focus();
        } else if ($hcod_telefono.val() == '') {
            $btn_codlocal.addClass('btn-danger');
        } else if ( $telefono.val().length === 5) {
            $div_telefono.addClass('has-error');
            $telefono.focus();
        } else if ($fecha_nacimiento.val() === null || $fecha_nacimiento.val().length === 0 || /^\s+$/.test($fecha_nacimiento.val())) {
            $div_fecha.addClass('has-error');
            $fecha_nacimiento.focus();
        } else if ($hcod_telefono.val() == '') {
            $btn_codcel.addClass('btn-danger');
        } else if ($celular.val() === null || $celular.val().length === 0 || /^\s+$/.test($celular.val())) {
            $div_celular.addClass('has-error');
            $celular.focus();
        } else if ($municipio.val() == 0) {
            $municipio.addClass("has-error");
        } else if ($sector.val() == 0) {
            $sector.addClass("has-error");
        } else if ($direccion.val() === null || $direccion.val().length === 0 || /^\s+$/.test($direccion.val())) {
            $div_direccion.addClass('has-error');
            $direccion.focus();
        } else {

            $('#accion').remove();
            var accion = $(this).val();
            var $accion = ' <input type="hidden" id="accion" name="accion" value="' + accion + '" />';
            $($accion).prependTo($(this));
            if (accion == 'Agregar') {
                $.post(url, $frmpaciente.serialize(), function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg == 21) {
                        var modificar = '<img class="modificar" title="Modificar"         style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                        TPaciente.fnAddData([$cedula_p.val(),$nombre.val() + ' ' + $apellido.val(),$fecha_nacimiento.val(), $telefono.val(),modificar]);
                        limpiar();
                    }
                }, 'json');
            } else {
                window.parent.apprise('&iquest;Desea Modificar los datos del registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $.post(url, $frmpaciente.serialize(), function(data) {
                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
                            var tipo = data.tipo_error;

                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});

                            var fila = $('#fila').val();
                            if (cod_msg == 22) {

                                $("#tabla_paciente tbody tr:eq(" + fila + ")").find("td:eq(2)").text($fecha_nacimiento.val());
                                $("#tabla_paciente tbody tr:eq(" + fila + ")").find("td:eq(3)").text($cod_telefono.val() + '' + $telefono.val());
                                limpiar();
                            }
                        }, 'json');
                    }
                });
            }
        }

    });


    $('table#tabla_paciente').on('click', 'img.modificar', function() {
        
        limpiar();
        var padre             = $(this).closest('tr');
        var fila              = padre.index();
        var cedula_completa   = padre.children('td:eq(0)').text().trim();
        var fecha_nacimiento  = padre.children('td:eq(2)').text().trim();
        var telefono_completo = padre.children('td:eq(3)').text().trim();
        
        var datos = cedula_completa.split('-');
        
        var nacionalidad = datos[0];

        var cedula_p = datos[1];
        
        $btn_nac.prop('disabled', true);
        $text_nac.prop('disabled', true);
        $text_nac.val(nacionalidad + '-');

        $('#hcedula_p').val(cedula_completa);
        $hnac.val(nacionalidad);

        $cedula_p.prop('disabled', true);
        $cedula_p.val(cedula_p);

        var datos_tele = telefono_completo.split('-');
       
        var vali_cod  = datos_tele[0].substring(1,2);
        if(vali_cod== 2){
            $cod_telefono.val(datos_tele[0]+'-');
            $telefono.val(datos_tele[1]);
        }else{
            $cod_celular.val(datos_tele[0]+'-');
            $celular.val(datos_tele[1]);
        }
        $fecha_nacimiento.val(fecha_nacimiento);
        $('#fila').remove();

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).appendTo('#btnaccion');

        $('#accion').remove();

        $.post(url, {cedula_p: cedula_p, accion: 'BuscarDatos'}, function(data) {
    
            var codigo_municipio = parseInt(data.cod_municipio);
            var codigo_sector    = parseInt(data.cod_sector);

            $nombre.val(data.nombre).prop('disabled',true);
            $apellido.val(data.apellido).prop('disabled',true);
            $hcod_telefono.val(data.cod_telefono);
            $cod_telefono.val('0'+data.codigot+'-');
            $telefono.val(data.telefono);
            $hcod_celular.val(data.cod_celular);
            $cod_celular.val('0'+data.codigoc+'-');
            $celular.val(data.celular);
            $municipio.select2('val',codigo_municipio);
            $direccion.val(data.direccion);
            $sector.find('option:gt(0)').remove().end();
            $.post(url, {codigo_municipio: codigo_municipio, accion: 'Sector'}, function(data) {
                var option = "";
                $.each(data, function(i) {
                    option += "<option value=" + data[i].cod_sector + ">" + data[i].sector + "</option>";
                });
                $sector.append(option);
                $sector.select2('val',codigo_sector);
            }, 'json');
            
        }, 'json');
        $btnaccion.val('Modificar');
    });

    $btnlimpiar.on('click', function() {
        limpiar();
    });

});

function limpiar() 
{
    $('#btn_nac,#btn_codlocal,#btn_codcel').removeClass('btn-danger');
    $('#btn_nac,#text_nac,#cedula_p').prop('disabled', false);
    $('#hcedula_p').val('');
    $("form#frmpaciente")[0].reset();
    $('#accion').remove();
    $('#fila').remove();
    $('input:text').prop('disabled',false);
    $('div').removeClass('has-error');
    $('form#frmpaciente select').select2('val', 0);
    $('form#frmpaciente select#sector').find('option:gt(0)').remove().end();    
    $('#btnaccion').val('Agregar');
}
