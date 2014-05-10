$(document).ready(function() {

    var TPefil = $('#tabla_perfil.dataTable').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "agregar center", "sWidth": "10%"},
            {"sClass": "center"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmperfil    = $('#frmperfil');
    var $div_perfil   = $frmperfil.find('div#div_perfil');
    var $perfil       = $frmperfil.find($('input:text#perfil'));
    var $btnaccion    = $frmperfil.find($('input:button#btnaccion'));
    var $btnlimpiar   = $frmperfil.find($('input:button#btnlimpiar'));
    var $btnlistar    = $frmperfil.find($('input:button#btnlistar'));
    var $tabla_perfil = $('#tabla_perfil');

    $('#img_perfil').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>El Perfil no debe estar en <span class="alerta">blanco</span>'
    });

    var letra = ' abcdefghijklmnñopqrstuvwxyzáéíóú';

    $perfil.validar(letra);

    var urlperfil = '../../controlador/seguridad/perfil.php';

    // Aciones Agregar/Modificar
    $btnaccion.on('click', function() {

        $('#accion').remove();
        $('#codigo_perfil').remove();
        if ($perfil.val() === null || $perfil.val().length === 0 || /^\s+$/.test($perfil.val())) {
            $div_perfil.addClass('has-error');
            $perfil.focus();
        } else {
            var accion = $(this).val();
            var $accion = '<input type="hidden" id="accion"  value="' + accion + '" name="accion">';
            $($accion).appendTo($frmperfil);
            if ($(this).val() === 'Guardar') {

                var accion = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                accion += '&nbsp;&nbsp;<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';
                
                var codigo = 1;
                var TotalRow = TPefil.fnGetData().length;
                if(TotalRow > 0){
                    var lastRow   = TPefil.fnGetData(TotalRow-1);
                    var codigo    = parseInt(lastRow[0])+1;
                }
                var $codigo_perfil = '<input type="hidden" id="codigo_perfil"  value="' + codigo + '" name="codigo_perfil">';
                $($codigo_perfil).prependTo($frmperfil);
                
                $.post(urlperfil, $frmperfil.serialize(), function(respuesta) {
                    var cod_msg = parseInt(respuesta.error_codmensaje);
                    var mensaje = respuesta.error_mensaje;
                    if (cod_msg === 15) {
                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                            $div_perfil.addClass('has-error');
                            $perfil.focus();
                        });

                    } else if (cod_msg === 21) {
                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                            var codigo = $("#tabla_perfil tr:last").find('td:eq(0)').text();
                            codigo = parseInt(codigo) + 1;
                            TPefil.fnAddData([codigo, MaysPrimera($perfil.val()), accion]);
                            $('input[type="text"]').val('');
                        });

                    }
                }, 'json');

            } else {
                window.parent.apprise('<div style="margin:auto">&iquest;Desea Modificar el Registro?</div>', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $.post(urlperfil, $frmperfil.serialize(), function(respuesta) {
                            var cod_msg = parseInt(respuesta.error_codmensaje);
                            var mensaje = respuesta.error_mensaje;
                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                            if (cod_msg === 22) {

                                var fila = $("#fila").val();

                                var tr_fila = $("#tabla_perfil tbody").children('tr').eq(fila).children('td');
                                tr_fila.eq(1).html($perfil.val());
                                $('input[type="text"]').val('');
                                $btnaccion.val('Guardar');
                            }
                        }, 'json');
                    }
                });

            }
        }
    });
    // fin btn accion

    // Click en la imagen modificar
    $tabla_perfil.on('click', 'img.modificar', function() {
        $('span.error_val').fadeOut();
        $('#accion').remove();
        $('#codigo_perfil').remove();
        var $padre        = $(this).parents('tr');
        var fila          = $padre.index();
        var codigo_perfil = $padre.children('td:eq(0)').text();
        var perfil        = $padre.children('td:eq(1)').text();

        var $codigo_perfil = ' <input type="hidden" id="codigo_perfil" name="codigo_perfil" value="' + codigo_perfil + '" />';
        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';

        $($fila).prependTo($btnaccion);
        $($codigo_perfil).prependTo($frmperfil);
        $perfil.val(perfil);
        $btnaccion.val('Modificar');
    });
    // Fin


    $tabla_perfil.on('click', 'img.eliminar', function() {
        $('input[type="text"]').val('');
        $btnaccion.val('Guardar');
        var padre = $(this).closest('tr');
        var codigo_perfil = padre.children('td:eq(0)').html();
        var nRow = padre[0];
        window.parent.apprise('&iquest;Desea Eliminar el Registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
            if (r) {
                $.post(urlperfil, {codigo_perfil: codigo_perfil, accion: 'Eliminar'}, function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;

                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 23) {
                        TPefil.fnDeleteRow(nRow);
                    }
                }, 'json');
            } else {
            }
        });

    });

    // Click en la imagen las primeras columnas
    $tabla_perfil.find('tr').on('click', 'td:lt(2)', function() {

        $('#cod').remove();
        $('#hperfil').remove();
        var fila        = $(this).index();
        var padre       = $(this).closest('tr');
        var cod_perfil  = padre.children('td:eq(0)').text();
        var perfil      = padre.children('td:eq(1)').text();
        var $cod_perfil = ' <input type="hidden" id="cod_perfil" name="cod_perfil" value="' + cod_perfil + '" />';
        $($cod_perfil).prependTo($frmperfil);

        $btnlistar.removeAttr("disabled");
        $perfil.attr('disabled', 'disabled').val(perfil);
        $btnaccion.attr('disabled', 'disabled').val('Guardar');
        $btnlimpiar.val('Restablecer');

        var mod = '../../imagenes/datatable/modificar.png';
        var eli = '../../imagenes/datatable/eliminar.png';

        var mod_dis = '../../imagenes/datatable/modificar_disabled.png';
        var eli_dis = '../../imagenes/datatable/eliminar_disabled.png';

        $('table#tabla_perfil tbody tr td img.modificar_disabled').attr({'src': mod, 'title': 'Modificar'}).css({'cursor': 'pointer'}).addClass('modificar');
        $('table#tabla_perfil tbody tr td img.eliminar_disabled').attr({'src': eli, 'title': 'Eliminar'}).css({'cursor': 'pointer'}).addClass('eliminar');
        padre.children('td:eq(2)').find('img.modificar').attr({'src':mod_dis}).removeAttr('class style title').addClass('modificar_disabled');
        padre.children('td:eq(2)').find('img.eliminar').attr({'src':eli_dis}).removeAttr('class style title').addClass('eliminar_disabled');

    });

    $btnlimpiar.click(function() {
        limpia_perfil();
    });

    $btnlimpiar.on('click', function() {

        if ($(this).val() == 'Restablecer') {

            $(this).val('Limpiar');

            var mod = '../../imagenes/datatable/modificar.png';
            var eli = '../../imagenes/datatable/eliminar.png';

            $('img.modificar_disabled').attr({'src': mod, 'title': 'Modificar'}).css({'cursor': 'pointer'}).addClass('modificar');
            $('img.eliminar_disabled').attr({'src': eli, 'title': 'Eliminar'}).css({'cursor': 'pointer'}).addClass('eliminar');
        }
    });

    var $frmfrmprivilegio = $('#frmprivilegio');
    var $nom_perfil       = $frmfrmprivilegio.find('span#nom_perfil');
    var $modulo           = $frmfrmprivilegio.find('select#modulo');
    var $sub_modulo       = $frmfrmprivilegio.find('select#sub_modulo');
    var $btnaccpriv       = $frmfrmprivilegio.find($('input:button#btnaccpriv'));
    var $btnlimpriv       = $frmfrmprivilegio.find($('input:button#btnlimpriv'));
    var $btnrestablecer   = $frmfrmprivilegio.find($('input:button#btnrestablecer'));

    var $tabla_privilegios = $('#tabla_privilegios');

    $modulo.select2();
    $sub_modulo.select2();

    var TPrivilegios = $('table#tabla_privilegios.dataTable').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sWidth": "15%","sClass": "center"},
            {"sWidth": "30%","sClass": "center"},
            {"sWidth": "3%","bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%","bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%","bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%","bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%","bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%","bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });


    $btnlistar.on('click', function() {
        $('div#divperfil').slideUp(3000);
        $('div#privilegios').slideDown(3000).css('display', 'block');
        $('table#tabla_privilegios.dataTable tr th:eq(0)').css('width','30% !important');
        $('table#tabla_privilegios.dataTable tr th:eq(1)').css('width','70% !important');
        var cod_perfil = $('#cod_perfil').val();

        $nom_perfil.text($perfil.val());
        
        var nodos = TPrivilegios.fnGetNodes();
        $("div.btn-group > label", nodos).removeClass('btn-success active');
        $("div.btn-group > label", nodos).addClass('btn-primary');
        $('div.btn-group > label > span', nodos).text('NO');
        $("div.btn-group > label:not([id^='lbl_ac'])", nodos).addClass('btn-primary disabled');
        $("div.btn-group > label > input:checkbox",nodos).prop('checked', false);

        $.post(urlperfil, {cod_perfil: cod_perfil, accion: 'BuscarPrivilegios'}, function(respuesta) {
            var datos = respuesta.split(",");
            

            for (var i= 0;i < datos.length;i++){ 
                var activos = datos[i].split(';');
                
                var class_acdi = 'active';
                if(cod_perfil == 1 && activos[0] <= 5 ){
                    class_acdi = 'disabled';
                }else if(cod_perfil > 1){
                    $('#lbl_ac_1,#lbl_ac_2,#lbl_ac_3,#lbl_ac_4,#lbl_ac_5', nodos).addClass('disabled');
                }
                
                $('label#lbl_ac_' + activos[0], nodos).addClass('btn-success ' + class_acdi);
                $('span#sp_ac_' + activos[0], nodos).text('SI');
                $('label#lbl_add_' + activos[0], nodos).removeClass('disabled');
                $('label#lbl_up_' + activos[0], nodos).removeClass('disabled');
                $('label#lbl_del_' + activos[0], nodos).removeClass('disabled');
                $('label#lbl_cons_' + activos[0], nodos).removeClass('disabled');
                $('label#lbl_imp_' + activos[0], nodos).removeClass('disabled');
                $("input:checkbox#ac_" + activos[0],nodos).prop('checked', true);
                       
                
                if(activos[1]==1){
                    $('label#lbl_add_' + activos[0], nodos).removeClass('btn-primary').addClass('btn-success '+ class_acdi);
                    $('span#sp_add_' + activos[0], nodos).text('SI');
                    $("input:checkbox#add_"+ activos[0],nodos).prop('checked', true);
                }
                if(activos[2] == 1){
                    $('label#lbl_up_' + activos[0], nodos).removeClass('btn-primary').addClass('btn-success '+ class_acdi);
                    $('span#sp_up_' + activos[0], nodos).text('SI');
                    $("input:checkbox#up_"+ activos[0],nodos).prop('checked', true);
                }
                if(activos[3] == 1){
                    $('label#lbl_del_' + activos[0], nodos).removeClass('btn-primary').addClass('btn-success '+ class_acdi);
                    $('span#sp_del_' + activos[0], nodos).text('SI');
                    $("input:checkbox#del_"+ activos[0],nodos).prop('checked', true);
                }
                if(activos[4] == 1){
                    $('label#lbl_cons_' + activos[0], nodos).removeClass('btn-primary').addClass('btn-success '+ class_acdi);
                    $('span#sp_con_' + activos[0], nodos).text('SI');
                    $("input:checkbox#con_"+ activos[0],nodos).prop('checked', true);
                }
                if(activos[5] == 1){
                    $('label#lbl_imp_' + activos[0], nodos).removeClass('btn-primary').addClass('btn-success '+ class_acdi);
                    $('span#sp_imp_' + activos[0], nodos).text('SI');
                    $("input:checkbox#imp_"+ activos[0],nodos).prop('checked', true);
                }
            }
        });
    });


    $modulo.on('change', function() {

        var codigo = $(this).val();
        if (codigo > 0) {
            $sub_modulo.find('option:gt(0)').remove().end();
            $.post(urlperfil, {codigo: codigo, accion: 'BuscarSub'}, function(data) {
                var option = "";
                if (data != 0) {
                    $.each(data, function(i, obj) {
                        option += "<option value=" + obj.codigo + ">" + obj.descripcion + "</option>";
                    });
                    $sub_modulo.append(option);
                } else {
                    $sub_modulo.find('option:gt(0)').remove().end();
                }
            }, 'json');

        } else {
            $sub_modulo.find('option:gt(0)').remove().end();
        }
    });



    $('input:checkbox').change(function() {
        
        var padre1 = $(this).closest('tr');
        var index = $(this).closest('td').index();
        var padre = $(this).closest('label');
        var $span = $(this).next('span');
        var clase = $(this).attr('class');
        var nodos = TPrivilegios.fnGetNodes();
        var padr = padre1.find("td:gt(2)",nodos);
        if ($(this).is(':checked') == true) {
            if (clase == 'activar') {
                padr.find('label').removeClass('disabled active');
            }
            padre.removeClass('btn-primary');
            padre.addClass('btn-success');
            $span.html('SI');
        } else {
            if (clase == 'activar') {
                padr.find('label').removeClass('btn-success active');
                padr.find('label').addClass('btn-primary disabled');
                padr.find('span').text('NO');
                padr.find("input:checkbox").prop('checked', false);
            }
            padre.removeClass('btn-success');
            padre.addClass('btn-primary');
            $span.text('NO');
        }
    });


    $btnaccpriv.click(function() {
        
        $('#activados').remove();
        var cod_perfil = $('#cod_perfil').val();

        var $accion = '<input type="hidden" id="accion"  value="AgregarPrivilegios" name="accion">';
        var $cod_perfil = '<input type="hidden" id="cod_perfil"  value="' + cod_perfil + '" name="cod_perfil">';


        $($accion).prependTo($(this));
        $($cod_perfil).prependTo($(this));

        /*****************************************/

        var priv_activar = '';
        var nodos = TPrivilegios.fnGetNodes();
        var count = $("input:checkbox[name='activar[]']:checked", nodos).length;

        var rowactivar = $("input:checkbox[name='activar[]']:checked", nodos);
        if (count > 0) {
            rowactivar.each(function() {
                /*var checkbox_value = $(elem).val();
                 priv_activar.push(checkbox_value);*/
                var $chkbox    = $(this);
                var $actualrow = $chkbox.closest('tr');
                var $clonedRow = $actualrow.find('td');
                var insertar   = $clonedRow.find("input:checkbox[name='agregar[]']:checked", nodos).val();
                var modificar  = $clonedRow.find("input:checkbox[name='modificar[]']:checked", nodos).val();
                var eliminar   = $clonedRow.find("input:checkbox[name='eliminar[]']:checked", nodos).val();
                var consultar  = $clonedRow.find("input:checkbox[name='consultar[]']:checked", nodos).val();
                var imprimir   = $clonedRow.find("input:checkbox[name='imprimir[]']:checked", nodos).val();
                if (insertar == undefined) {
                    insertar = 0;
                }
                if (modificar == undefined) {
                    modificar = 0;
                }
                if (eliminar == undefined) {
                    eliminar = 0;
                }
                if (consultar == undefined) {
                    consultar = 0;
                }
                if (imprimir == undefined) {
                    imprimir = 0;
                }
                priv_activar += $chkbox.val() + ';' + insertar + ';' + modificar + ';' + eliminar + ';' + consultar + ';' + imprimir + ',';

            });
            priv_activar = priv_activar.substring(0, priv_activar.length - 1);
        }
        /*****************************************/

         var $activados = '<input type="hidden" id="activados"  value="' + priv_activar + '" name="activados">';
         $($activados).appendTo($(this));

        $.post(urlperfil, $frmfrmprivilegio.serialize(), function(data) {
            var cod_msg = parseInt(data.error_codmensaje);
            var mensaje = data.error_mensaje;
            if(cod_msg == 21){
                window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                    $("div.btn-group > label", nodos).removeClass('btn-success active');
                    $("div.btn-group > label", nodos).addClass('btn-primary');
                    $('div.btn-group > label > span', nodos).text('NO');
                    $("div.btn-group > label:not([id^='lbl_ac'])", nodos).addClass('btn-primary disabled');
                    $('#activados').remove();
                    priv_activar = '';
                    $('#activados').remove();
                    window.parent.apprise('<span style="color:#FF0000;fotn-wight:bold">La Aplicaci&oacute;n se va refrescar para realizar los cambios</span>', {'textOk': 'Aceptar'}, function() {
                        setTimeout(function(){window.parent.location.reload()},1000);
                    });
                   
                });
                
            }
        }, 'json');
    });

    $tabla_privilegios.on('click', 'img.modificar', function() {

        //$('span.error_val').fadeOut();
        $('#accion').remove();

        var $padre = $(this).parents('tr');
        var fila = $padre.index();
        var perfil = $padre.children('td:eq(0)').text();
        var modulo = $padre.children('td:eq(1)').text();
        var sub_modulo = $padre.children('td:eq(2)').text();
        var cod_sub = $padre.children('td:eq(3)').attr('id');
        /*var $cod  = ' <input type="hidden" id="cod" name="cod" value="' + cod + '" />';
         var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
         $($fila).prependTo($btnaccion);
         $($cod).prependTo($btnaccion);
         $perfil.val(perfil);
         $btnaccion.val('Modificar');*/
    });

    $btnlimpriv.click(function() {
        limpiar_privilegio();

    });
    
    $btnrestablecer.click(function() {
        var nodos = TPrivilegios.fnGetNodes();
        $('div#divperfil').slideDown(3000);
        $('div#privilegios').slideUp(3000);
        $('#activados').remove();
        $('#cod_perfil').val('');
        $("div.btn-group > label", nodos).removeClass('btn-success active disabled');
        $("div.btn-group > label", nodos).addClass('btn-primary');
        $('div.btn-group > label > span', nodos).text('NO');
        $("div.btn-group > label:not([id^='lbl_ac'])", nodos).addClass('btn-primary disabled');
    });
});

function limpia_perfil() {
    $('#accion').remove();
    $('#fila').remove();
    $('#hperfil').remove();
    $('input:text#perfil').val('').prop('disabled', false);
    $('input:button#btnaccion').val('Agregar').prop('disabled', false);
    $('input:button#btnlimpiar').val('Limpiar');
    $('input:button#btnlistar').prop('disabled', true);
    var mod = '../../imagenes/datatable/modificar.png';
    var eli = '../../imagenes/datatable/eliminar.png';
    $('img.modificar_disabled').attr({'src': mod, 'title': 'Modificar'}).css({'cursor': 'pointer'}).addClass('modificar');
    $('img.eliminar_disabled').attr({'src': eli, 'title': 'Eliminar'}).css({'cursor': 'pointer'}).addClass('eliminar');
}
function limpiar_privilegio() {
    $('select#modulo').select2('val', 0);
    $('select#sub_modulo').select2('val', 0);
    $('select#sub_modulo').find('option:gt(0)').remove().end();
    $('input:button#btnaccpriv').val('Agregar');
    $("input:checkbox").parent('div').removeClass('red');
    $("input:checkbox + a").removeClass('checked');
}