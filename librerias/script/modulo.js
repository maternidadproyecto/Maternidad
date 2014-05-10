$(document).ready(function() {

    $('input[type="text"], textarea').on({
        keypress: function() {
            $(this).parent('div').removeClass('has-error');
        }
    });

    var TModulo = $('#tabla_modulo').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "40%"},
            {"sClass": "registro center", "sWidth": "15%"},
            {"sClass": "registro center", "sWidth": "8%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $formulario      = $('form#frmmodulo');
    var $div_modulo      = $formulario.find('div#div_modulo');
    var $modulo          = $formulario.find('input:text#modulo');
    var $div_modposicion = $formulario.find('div#div_modposicion');
    var $mod_posicion    = $formulario.find('input:text#mod_posicion');
    var $btnaccion       = $formulario.find('input:button#btnaccion');
    var $btnlimpiar      = $formulario.find('input:button#btnlimpiar');
    var $btnlistar       = $formulario.find('input:button#btnlistar');
    var $tabla_modulo    = $('#tabla_modulo');

    $('#img_modulo').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>El Modulo no debe estar en <span class="alerta">blanco</span>'
    });

    $('#img_posicion').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>La Posici&oacute;n no debe estar en <span class="alerta">blanco</span> deber ser <span class="alerta">N&uacute;merico</span>'
    });

    $('.modificar').tooltip({
        html: true,
        placement: 'bottom',
        title: 'Modificar'
    });

    $('.eliminar').tooltip({
        html: true,
        placement: 'bottom',
        title: 'Eliminar'
    });

    $('input:radio[name="mod_estatus"]').change(function() {
        var valor = $(this).val();

        if (valor == 1) {
            $('#l_mod_activo').addClass('btn-success');
            $('#l_mod_inactivo').removeClass('btn-danger active').addClass('btn-default');
        } else if (valor == 0) {
            $('#l_mod_inactivo').addClass('btn-danger');
            $('#l_mod_activo').removeClass('btn-success active').addClass('btn-default');
        }
    });

    var letras = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]{2,20}$/;
    var letra  = ' abcdefghijklmnñopqrstuvwxyzáéíóú';
    var entero = '1234567890';

    $modulo.validar(letra);
    $mod_posicion.validar(entero);
    var urlmod = '../../controlador/seguridad/modulo.php';

    $btnaccion.on("click", function() {

        $('#accion').remove();

        if ($modulo.val() === null || $modulo.val().length === 0 || /^\s+$/.test($modulo.val())) {
            $div_modulo.addClass('has-error');
            $modulo.focus();
        } else if ($mod_posicion.val() === null || $mod_posicion.val().length === 0 || /^\s+$/.test($mod_posicion.val())) {
            $div_modposicion.addClass('has-error');
            $mod_posicion.focus();
        } else {
            $div_modulo.removeClass('has-error');
            var accion = $(this).val();
            var $accion = '<input type="hidden" id="accion"  value="' + accion + '" name="accion">';
            $($accion).appendTo($formulario);

            if ($(this).val() == 'Guardar') {
                $('#cod_modulo').remove();
                // obtener el ultimo codigo del estado
                var ToltalRow = TModulo.fnGetData().length;
                var lastRow   = TModulo.fnGetData(ToltalRow - 1);
                var codigo    = parseInt(lastRow[0]) + 1;

                var $cod_modulo = '<input type="hidden" id="cod_modulo"  value="' + codigo + '" name="cod_modulo">';
                $($cod_modulo).prependTo($formulario);

                $.post(urlmod, $formulario.serialize(), function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;

                    var accion = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    accion += '&nbsp;&nbsp;<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

                    if (cod_msg === 21) {
                        /*var estatus = 'Activo';
                         var val_est = $("input[name='mod_estatus']:checked").val(); 
                         if(val_est == 0){
                         estatus = 'Inactivo';
                         }*/
                        var estatus = $("input[name='mod_estatus']:checked")[0].nextSibling.nodeValue;
                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                            TModulo.fnAddData([codigo, $modulo.val(), $mod_posicion.val(), estatus, accion]);
                            var option = "<option value=" + codigo + ">" + $modulo.val() + "</option>";
                            $('select#nommodulo').append(option);
                            limpiar($formulario);
                        });

                    } else if (cod_msg === 15) {
                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                            $div_modulo.addClass('has-error');
                            $modulo.focus();
                        });

                    } else if (cod_msg === 16) {
                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                            $div_modposicion.addClass('has-error');
                            $mod_posicion.focus();
                        });

                    }
                }, 'json');

            } else {

                window.parent.apprise('<div class="msj-danger">&iquest;Desea Modificar el Registro?</div>', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {

                        $.post(urlmod, $formulario.serialize(), function(data) {
                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                                if (cod_msg === 22) {
                                    /*var estatus = 'Activo';
                                     var val_est = $("input[name='mod_estatus']:checked").val(); 
                                     if(val_est == 0){
                                     estatus = 'Inactivo';
                                     }*/

                                    var estatus = $("input[name='mod_estatus']:checked")[0].nextSibling.nodeValue;
                                    var fila = $("#fila").val();

                                    var tr_fila = $("#tabla_modulo tbody").children('tr').eq(fila).children('td');
                                    tr_fila.eq(1).html($modulo.val());
                                    tr_fila.eq(2).html($mod_posicion.val());
                                    tr_fila.eq(3).html(estatus);
                                    var cod_modulo = $('#cod_modulo').val();
                                    $("select#nommodulo option[value='" + cod_modulo + "']").text($modulo.val());
                                    /*$("#tabla_modulo tbody").children('tr').eq(fila).children('td').eq(1).html($modulo.val());
                                     $("#tabla_modulo tbody tr:eq("+fila+")").find("td:eq(2)").html($mod_posicion.val());
                                     $("#tabla_modulo tbody tr:eq("+fila+")").find("td").eq(3).html(estatus);*/
                                    $('input:radio').attr('checked', false);
                                    $('input:radio#mod_activo').prop('checked', true);
                                    $('#l_mod_activo').addClass('btn-success active');
                                    $('#l_mod_inactivo').removeClass('btn-danger active').addClass('btn-default');
                                    $('#btnaccion').val('Guardar');
                                    limpiar($formulario);
                                    window.parent.apprise('<span style="color:#FF0000;fotn-wight:bold">La Aplicaci&oacute;n se va refrescar para realizar los cambios</span>', {'textOk': 'Aceptar'}, function() {
                                        setTimeout(window.parent.location.reload(),1000);
                                    });
                                }
                            });

                        }, 'json');
                    } else {
                        limpiar($formulario);
                    }
                });
            }

        }

    });

    // Cuando presiona la imagen modificar en modulos
    $tabla_modulo.on('click', 'img.modificar', function() {


        $btnlistar.fadeOut(1000, function() {
            $btnaccion.fadeIn(1000);
        });
        var valor_lim = $btnlimpiar.val();
        if (valor_lim == 'Restablecer') {
            $btnlimpiar.val('Limpiar');
        }
        limpiar($formulario);
        $('#fila').remove();
        $('#cod_modulo').remove();
        $('#hmodulo').remove();
        $('#cod_submodulo').remove();
        var padre = $(this).closest('tr');
        var fila = padre.index();
        var id = padre.attr('id');
        var cod_modulo = padre.children('td:eq(0)').html();
        var modulo = padre.children('td').eq(1).html();
        var posicion = padre.children('td').eq(2).html();
        var estatus = padre.children('td').eq(3).html();

        var $codigo = '<input type="hidden" id="cod_modulo"  value="' + cod_modulo + '" name="cod_modulo">';
        var $fila = '<input type="text" id="fila"  value="' + fila + '" name="fila">';

        $('input:radio').attr('checked', false);
        if (estatus == 'Inactivo') {
            $('input:radio#mod_inactivo').prop('checked', true);
            $('#l_mod_activo').removeClass('btn-success active').addClass('btn-default');
            $('#l_mod_inactivo').addClass('btn-danger active');
        } else {
            $('input:radio#mod_activo').prop('checked', true);
            $('#l_mod_activo').addClass('btn-success active');
            $('#l_mod_inactivo').removeClass('btn-danger active').addClass('btn-default');
        }
        $($codigo).appendTo($btnaccion);
        $($fila).appendTo($btnaccion);
        $('#cod_modulo').val(cod_modulo);
        $modulo.val(modulo);
        $mod_posicion.val(posicion);
        $btnaccion.val('Modificar');

    });

    // Cuando presiona la imagen eliminar en modulos
    $tabla_modulo.on('click', 'img.eliminar', function() {

        var padre = $(this).closest('tr');
        var cod_modulo = padre.children('td:eq(0)').html();
        var nRow = padre[0];
        window.parent.apprise('<div class="msj-danger">&iquest;Desea Eliminar el Registro?</div>', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
            if (r) {
                $.post(urlmod, {cod_modulo: cod_modulo, accion: 'Eliminar'}, function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;

                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 23) {
                        TModulo.fnDeleteRow(nRow);
                    }
                }, 'json');
            } else {
                limpiar($formulario);
            }
        });
    });

    $tabla_modulo.find('tr').on('click', 'td:lt(4)', function() {
        if ($btnlistar.is(':hidden')) {
            $btnaccion.fadeOut(700, function() {
                $btnlistar.fadeIn(700);
            });
        }


        var padre = $(this).closest('tr');
        var cod_modulo = padre.children('td:eq(0)').html();
        var modulo = padre.children('td').eq(1).html();

        var $codigo = '<input type="hidden" id="cod_modulo"  value="' + cod_modulo + '" name="cod_modulo">';
        var $hmodulo = '<input type="hidden" id="hmodulo"  value="' + modulo + '" name="hmodulo">';

        $($codigo).appendTo('input:button#btnaccionsub');
        $($hmodulo).appendTo('input:button#btnaccionsub');

        $('#cod_modulo').val(cod_modulo);
        $mod_posicion.val('');
        $('#hmodulo').val(modulo);
        $btnlimpiar.val('Restablecer');
        $modulo.val(modulo).prop('disabled', true);
        $mod_posicion.prop('disabled', true);
    });

    $btnlimpiar.on("click", function() {
        $btnlistar.fadeOut(1000, function() {
            $btnaccion.fadeIn(1000);
        });
        var este = $(this);
        var valor = este.val();
        if (valor == 'Restablecer') {
            este.val('Limpiar');
        }
        $btnaccion.val('Guardar');
        limpiar($formulario);
    });

    /*
     * Fin de Modulo
     */

    /*
     * Sub Modulos
     */

    // Tabla SubModulo
    var TSubModulo = $('#tabla_submodulo').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center", "sWidth": "10%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmsubmodulo = $('form#frmsubmodulo');
    var $div_submodulo = $frmsubmodulo.find('div#div_submodulo');
    var $nommodulo = $frmsubmodulo.find('select#nommodulo');
    var $sub_modulo = $frmsubmodulo.find('input:text#submodulo');
    var $sbmposicion = $frmsubmodulo.find('input:text#sbm_posicion');
    var $ruta = $frmsubmodulo.find('input:text#ruta');
    var $btnaccionsub = $frmsubmodulo.find('input:button#btnaccionsub');
    var $btnlimpiarsub = $frmsubmodulo.find('input:button#btnlimpiarsub');
    var $btnrestablecer = $frmsubmodulo.find('input:button#btnrestablecer');
    var $div_ruta = $frmsubmodulo.find('div#div_ruta');

    $nommodulo.select2();

    var rutas = /^[a-zA-Z\.0-9/\-_]{4,50}$/;
    var ruta = 'abcdefghijklmnopqrstuvwxyz/._-1234567890';

    $ruta.validar(ruta);

    $('#img_nommodulo').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Debe Seleccionar un <span class="alerta">modulo</span>'
    });

    $('#img_submodulo').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>El Subm&oacute;dulo no debe estar <span class="alerta">blanco</span>'
    });

    $('#img_ruta').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>la Ruta no debe estar en <span class="alerta">blanco</span>,<br/>solo acepta (<span class="alerta">/_-.</span>)'
    });

    $sub_modulo.validar(letra);
    // Evento para buscar todos los SubModulos
    var urlsub = '../../controlador/seguridad/submodulo.php';


    $('input:radio[name="sbmod_estatus"]').change(function() {
        var valor = $(this).val();

        if (valor == 1) {
            $('#sbmod_activo').attr('checked', true);
            $('#sbmod_inactivo').attr('checked', false);
            $('#l_sbmod_activo').addClass('btn-success');
            $('#l_sbmod_inactivo').removeClass('btn-danger active').addClass('btn-default');
        } else if (valor == 0) {
            $('#sbmod_activo').attr('checked', false);
            $('#sbmod_inactivo').attr('checked', true);
            $('#l_sbmod_inactivo').addClass('btn-danger');
            $('#l_sbmod_activo').removeClass('btn-success active').addClass('btn-default');
        }
    });

    $btnlistar.on('click', function() {

        var cod_modulo = $('#cod_modulo').val();
        var nommodulo = $('#hmodulo').val();

        $nommodulo.select2("val", cod_modulo);
        $nommodulo.select2("enable", false);
        $('div#divmodulo').slideUp(1500);
        $('div#divsubmodulo').slideDown(1500);

        var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
        var eliminar = '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

        TSubModulo.fnClearTable();

        $('#hcod_submodulo').remove();
        $.post(urlsub, {cod_modulo: cod_modulo, accion: 'BuscarSubModulos'}, function(respuesta) {
            var datos = respuesta.split('/');
            var cod_submodulo = datos[0];
            if (datos.length == 2) {


                var count_submodulos = datos[1].split(',');
                for (var i = 0; i < count_submodulos.length; i++) {
                    var eli = eliminar;
                    var inf_submodulos = count_submodulos[i].split(';');
                    var estatus = 'Activo';
                    if (inf_submodulos[3] == 0) {
                        estatus = 'Inactivo';
                    }
                    if (inf_submodulos[0] <= 5) {
                        eli = '';
                    }
                    var accion = modificar + eli;
                    TSubModulo.fnAddData([inf_submodulos[0], nommodulo, inf_submodulos[1], inf_submodulos[2], estatus, accion]);
                }
            } else {
                cod_submodulo = respuesta;
            }

            var $ult_submodulo = '<input type="hidden" id="cod_submodulo"  value="' + cod_submodulo + '" name="cod_submodulo">';
            $($ult_submodulo).appendTo($frmsubmodulo);
        });
    });

    $btnaccionsub.on("click", function() {

        $('#accion').remove();
        if ($sub_modulo.val() === null || $sub_modulo.val().length === 0 || /^\s+$/.test($sub_modulo.val())) {
            $div_submodulo.addClass('has-error');
            $sub_modulo.focus();
        } else if (!letras.test($sub_modulo.val())) {
            $div_submodulo.addClass('has-error');
            $sub_modulo.focus();
        } else if ($ruta.val() === null || $ruta.val().length === 0 || /^\s+$/.test($ruta.val())) {
            $div_ruta.addClass('has-error');
            $ruta.focus();
        } else if (!rutas.test($ruta.val())) {
            $div_ruta.addClass('has-error');
            $ruta.focus();
        } else {
            $div_modulo.removeClass('has-error');
            $div_ruta.removeClass('has-error');

            var nommodulo = $('#hmodulo').val();

            var accion = $(this).val();
            var $accion = '<input type="hidden" id="accion"  value="' + accion + '" name="accion">';

            $($accion).appendTo($frmsubmodulo);
            if ($(this).val() == 'Guardar') {

                var codigo = 0;
                codigo = $('#cod_submodulo').val();
  
                $.post(urlsub, $frmsubmodulo.serialize(), function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;

                    var accion = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    accion += '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg == 21) {
                        var estatus = 'Activo';
                        var check_estatus = $('input:radio[name="sbmod_estatus"]:checked').val();
                        if (check_estatus == 0) {
                            estatus = 'Inactivo';
                        }

                        var posicion = $('#sbm_posicion').val();
                        
                        
                        TSubModulo.fnAddData([codigo, nommodulo, $sub_modulo.val(), posicion, estatus, accion]);
                        $('input:text#sbm_posicion,input:text#submodulo,input:text#ruta').val('');
                        TSubModulo.fnDraw();
                        $('input:radio').attr('checked', false);
                        $('input:radio#sbmod_activo').prop('checked', true);
                        $('#l_sbmod_activo').addClass('btn-success active');
                        $('#l_sbmod_inactivo').removeClass('btn-danger active').addClass('btn-default');
                        codigo = $('#cod_submodulo').val();
                        codigo = parseInt(codigo)+1;
                        $('#cod_submodulo').val(codigo);
                        //$('input:radio[name="sbmod_estatus",vale="1"]').attr('checked', true);
                        $('#accion').remove();
                    }


                }, 'json');
            } else {
                window.parent.apprise('&iquest;Desea Modificar el Registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $('#cod_modulo').remove();
                        $.post(urlsub, $frmsubmodulo.serialize(), function(data) {

                            var cod_modulo_new = $nommodulo.find('option').filter(':selected').val();
                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;

                            var cod_modulo_old = $('#cod_modulo_old').val();
                            var estatus = 'Activo';
                            var check_estatus = $('input:radio[name="sbmod_estatus"]:checked').val();
                            if (check_estatus == 0) {
                                estatus = 'Inactivo';
                            }
                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                            if (cod_msg === 22) {
                                var fila = parseInt($("#fila").val());
                                if (cod_modulo_new != cod_modulo_old) {
                                    TSubModulo.fnDeleteRow(nRow);
                                } else {
                                    $("#tabla_submodulo tbody").children('tr').eq(fila).children('td').eq(2).html($sub_modulo.val());
                                    $("#tabla_submodulo tbody").children('tr').eq(fila).children('td').eq(3).html($sbmposicion.val());
                                    $("#tabla_submodulo tbody").children('tr').eq(fila).children('td').eq(4).html(estatus);
                                }

                                $('input:text').val('');

                                $('input:radio').attr('checked', false);
                                $('input:radio#sbmod_activo').prop('checked', true);
                                $('#l_sbmod_activo').addClass('btn-success active');
                                $('#l_sbmod_inactivo').removeClass('btn-danger active').addClass('btn-default');

                                //$('input:radio[name="sbmod_estatus",vale="1"]').attr('checked', true);
                                $('#accion').remove();
                                $nommodulo.select2("val", cod_modulo_old);
                                $nommodulo.select2("enable", false);
                            }
                        }, 'json');
                    }
                });
            }
        }
    });

    var nRow = '';
    // Cuando presiona la imagen modificar en submodulos
    $('#tabla_submodulo').on('click', 'img.modificar', function() {

        $('#fila').remove();
        $('#cod_submodulo').remove();
        $('#nrow').remove();
        $('#hcod_modulo').remove();

        $nommodulo.select2("enable", true);
        var padre = $(this).closest('tr');
        var fila = padre.index();
        var cod_submodulo = padre.children('td:eq(0)').html();
        var modulo = padre.children('td:eq(1)').html();
        var sub_modulo = padre.children('td').eq(2).html();
        var posicion = padre.children('td').eq(3).html();
        var sub_estatus = padre.children('td').eq(4).html();


        $('input:radio[name="sbmod_estatus"]').attr('checked', false);
        if (sub_estatus == 'Inactivo') {
            $('input:radio#sbmod_inactivo').prop('checked', true);
            $('#l_sbmod_activo').removeClass('btn-success active').addClass('btn-default');
            $('#l_sbmod_inactivo').addClass('btn-danger active');
        } else {
            $('input:radio#sbmod_activo').prop('checked', true);
            $('#l_sbmod_activo').addClass('btn-success active');
            $('#l_sbmod_inactivo').removeClass('btn-danger active').addClass('btn-default');
        }

        nRow = padre[0];

        var cod_modulo_old = $('#cod_modulo').val();
        var $cod_submodulo = '<input type="hidden" id="cod_submodulo"  value="' + cod_submodulo + '" name="cod_submodulo">';

        $($cod_submodulo).appendTo($btnaccionsub);

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).appendTo($btnaccionsub);

        var $modulo = '<input type="hidden" id="hcod_modulo"  value="' + modulo + '" name="hcod_modulo">';
        $($modulo).appendTo($btnaccionsub);

        var $cod_modulo_old = '<input type="hidden" id="cod_modulo_old"  value="' + cod_modulo_old + '" name="cod_modulo_old">';
        $($cod_modulo_old).appendTo($btnaccionsub);

        $.post(urlsub, {cod_submodulo: cod_submodulo, accion: 'BuscarRuta'}, function(data) {
            $ruta.val(data);
        });


        $('#cod_submodulo').val(cod_submodulo);
        $sub_modulo.val(sub_modulo);
        $sbmposicion.val(posicion);

        $btnaccionsub.val('Modificar');
    });

    // Cuando presiona la imagen eliminar en submodulos
    $('#tabla_submodulo').on('click', 'img.eliminar', function() {

        var padre = $(this).closest('tr');
        var cod_submodulo = padre.children('td:eq(0)').html();
        var nRow = padre[0];
        $('input:text').val('');
        $btnaccionsub.val('Guardar');
        window.parent.apprise('<div class="msg-danger">&iquest;Desea Eliminar el Registro?</div>', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
            if (r) {

                $.post(urlsub, {cod_submodulo: cod_submodulo, accion: 'Eliminar'}, function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;

                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 23) {
                        TSubModulo.fnDeleteRow(nRow);
                    }
                }, 'json');
            } else {
                limpiar($formulario);
            }
        });
    });

    $btnlimpiarsub.on("click", function() {
        var cod_modulo = $('#cod_modulo').val();
        $('input:text#submodulo,input:text#sbm_posicion,input:text#ruta').val('');
        $btnaccionsub.val("Guardar");
        $nommodulo.select2("val", cod_modulo);
        $nommodulo.select2("enable", false);
        $('#cod_modulo_old').remove();
    });

    $btnrestablecer.on("click", function() {

        TSubModulo.fnDraw();
        TSubModulo.fnClearTable();
        $('div#divmodulo').slideDown(3000);
        $('div#divsubmodulo').slideUp(3000, function() {
            TSubModulo.fnClearTable();
        });
        $sub_modulo.val('');
        $btnaccionsub.val('Guardar');
        $('#cod_modulo_old').remove();

    });

});

/***
 *
 * @Modulo
 */

function limpiar(formulario) {
    $("div").removeClass('has-error');
    $('input:radio').attr('checked', false);
    $('input:radio#inactivo').prop('checked', true);
    $('#l_mod_activo').addClass('btn-success active');
    $('#l_mod_inactivo').removeClass('btn-danger active').addClass('btn-default');
    ;
    $('input:radio#mod_activo').prop('checked', true);

    formulario.find('input:text').val('').prop('disabled', false);
    //formulario.find('input:button#btnaccion').val('Guardar').prop('disabled', false);
    //formulario.find('input:button#btnlistar').fadeOut(1000);
    //formulario.find('input:button#btnlimpiar').val('Limpiar');
    $('#accion').remove();
    $('#fila').remove();
    $('#cod_modulo').remove();
}