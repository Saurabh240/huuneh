"use strict";

$(function () {
    cdp_load(1);
    cdp_load_new(1);
});


//Cargar datos AJAX
function cdp_load(page) {

    var parametros = { "page": page };
    $("#loader").fadeIn('slow');
    var user = $('#userid').val();
    $.ajax({
        url: './ajax/dashboard/shipments/load_my_pickup.php',
        data: parametros,
        beforeSend: function (objeto) {
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
        }
    })
}

//Cargar datos AJAX
function cdp_load_new(page) {

    var parametros = { "page": page };
    $("#loader").fadeIn('slow');
    var user = $('#userid').val();
    $.ajax({
        url: './ajax/dashboard/shipments/load_my_drop_off.php',
        data: parametros,
        beforeSend: function (objeto) {
        },
        success: function (data) {
            $(".outer_div_drop_off").html(data).fadeIn('slow');
        }
    })
}


