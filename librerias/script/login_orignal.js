$(document).ready(function() {

    $('input[type="text"],input[type="password"]').on({
        keypress: function() {
            $(this).css({"color": "#000000", "border": "1px solid #C2BBBB"});
            $(this).siblings('form span.error_val').fadeOut();
        },
        blur: function() {
            $(this).css({"color": "#000000", "border": "1px solid #C2BBBB"});

        },
        focus: function() {
            $(this).css({"color": "#000000", "border": "1px solid #00A1FF"});
        }
    });


    $('form#frmlogin #ingresar').on('click', function() {

        if ($('#usuario').val() === null || $('#usuario').val().length === 0 || /^\s+$/.test($('#usuario').val())) {
            $('#usuario').focus().css({'border': '1px solid #FF0000'});
            return  false;
        } else if ($('#clave').val() === null || $('#clave').val().length === 0 || /^\s+$/.test($('#clave').val())) {
            $('#clave').focus().css({'border': '1px solid #FF0000'});
            return  false;
        } else {
            $.post('controlador/Usuario.php', $('#frmlogin').serialize(), function(data) {
                var cod_msg = parseInt(data);

                $('#usuario').val('');
                $('#clave').val('');
                if (cod_msg === 14) {
                    $('span#error')
                            .css({'color': '#FF0000'})
                            .text('Usuario o Clave incorrecta')
                            .fadeIn(1000).delay(2000).fadeOut(1000);
                } else if (cod_msg === 12) {
                    $('span#error')
                            .css({'color': '#FF0000'})
                            .text('Usuario Inactivo')
                            .fadeIn(1000).delay(2000).fadeOut(1000);
                } else if (cod_msg === 13) {
                    $('span#error')
                            .css({'color': '#FF0000'})
                            .text('Ocurrio un Error')
                            .fadeIn(1000).delay(2000).fadeOut(1000);
                } else if (cod_msg === 11) {
                    $('span#error')
                            .css({'color': '#FF0000'})
                            .text('El Usuario ya inicio Session')
                            .fadeIn(1000).delay(2000).fadeOut(1000);
                } else if (cod_msg === 21) {
                    window.location = 'controlador/seguridad/acceso.php';
                }

            });
        }
    });
});
