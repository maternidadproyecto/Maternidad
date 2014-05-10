$(document).ready(function() {
    $('input[type="text"], textarea').on({
        keypress: function() {
            $(this).parent('div').removeClass('has-error');
        }
    });
    $('select').on({
        change: function() {
            $(this).removeClass('has-error');
        }
    });
});

function MaysPrimera(string){ 
    return string.charAt(0).toUpperCase() + string.slice(1); 
} 