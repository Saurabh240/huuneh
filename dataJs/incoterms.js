"use strict";


$(function () {
	cdp_load(1);

});


//Cargar datos AJAX
function cdp_load(page) {
	var search = $("#search").val();
	var parametros = { "page": page, 'search': search };
	$("#loader").fadeIn('slow');
	$.ajax({
		url: './ajax/tools/incoterms/incoterms_list_ajax.php',
		data: parametros,
		beforeSend: function (objeto) {
		},
		success: function (data) {
			$(".outer_div").html(data).fadeIn('slow');
		}
	})
}


//cdp_eliminar
function cdp_eliminar(id) {

	var parent = $('#item_' + id).parent().parent();
	var name = $(this).attr('data-rel');
	new Messi('<p class="messi-warning"><i class="icon-warning-sign icon-3x pull-left"></i>' + message_delete_confirm + '<br /><strong>' + message_delete_confirm2 + '</strong></p>', {
		title: message_delete_confirm1,
		titleClass: '',
		modal: true,
		closeButton: true,
		buttons: [{
			id: 0,
			label: message_delete_confirm1,
			class: '',
			val: 'Y'
		}],
		callback: function (val) {
			if (val === 'Y') {
				$.ajax({
					type: 'post',
					url: './ajax/tools/incoterms/incoterms_delete_ajax.php',
					data: {
						'id': id,
					},
					beforeSend: function () {
						parent.animate({
							'backgroundColor': '#FFBFBF'
						}, 400);
					},
					success: function (data) {

						$('html, body').animate({
							scrollTop: 0
						}, 600);
						$('#resultados_ajax').html(data);

						cdp_load(1);
					}
				});
			}
		}

		// });
	});
}
//Registro de datos

$("#save_data").on('submit', function (event) {
	var parametros = $(this).serialize();

	$.ajax({
		type: "POST",
		url: "ajax/tools/incoterms/incoterms_add_ajax.php",
		data: parametros,
		beforeSend: function (objeto) {
			$("#resultados_ajax").html("Please wait...");
		},
		success: function (datos) {
			$("#resultados_ajax").html(datos);

			$('html, body').animate({
				scrollTop: 0
			}, 600);


		}
	});
	event.preventDefault();

})



$("#update_data").on('submit', function (event) {
	var parametros = $(this).serialize();

	$.ajax({
		type: "POST",
		url: "ajax/tools/incoterms/incoterms_edit_ajax.php",
		data: parametros,
		beforeSend: function (objeto) {
			$("#resultados_ajax").html("Please wait...");
		},
		success: function (datos) {
			$("#resultados_ajax").html(datos);

			$('html, body').animate({
				scrollTop: 0
			}, 600);


		}
	});
	event.preventDefault();

})


