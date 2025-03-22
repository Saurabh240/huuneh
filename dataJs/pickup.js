"use strict";


$(function () {
	var start = moment().startOf('month');
	var end = moment().endOf('month');

	$('#daterange').daterangepicker({
		startDate: start,
		endDate: end,
		locale: {
			'format': 'Y/M/D',
			"separator": " - ",
			"applyLabel": range_calendar_text17,
			"cancelLabel": range_calendar_text16,
			"fromLabel": range_calendar_text14,
			"toLabel": range_calendar_text15,
			"customRangeLabel": range_calendar_text13,
			"daysOfWeek": [
				range_calendar_text24,
				range_calendar_text25,
				range_calendar_text26,
				range_calendar_text27,
				range_calendar_text28,
				range_calendar_text29,
				range_calendar_text30,
			],
			"monthNames": [
				range_calendar_text1,
				range_calendar_text2,
				range_calendar_text3,
				range_calendar_text4,
				range_calendar_text5,
				range_calendar_text6,
				range_calendar_text7,
				range_calendar_text8,
				range_calendar_text9,
				range_calendar_text10,
				range_calendar_text11,
				range_calendar_text12,
			],
			"firstDay": 1
		},
		ranges: {
			[range_calendar_text18]: [moment(), moment()],
			[range_calendar_text19]: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			[range_calendar_text20]: [moment().subtract(6, 'days'), moment()],
			[range_calendar_text21]: [moment().subtract(29, 'days'), moment()],
			[range_calendar_text22]: [moment().startOf('month'), moment().endOf('month')],
			[range_calendar_text23]: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		}
	}).on('change', function (e) {
		cdp_load(1);
	});
    cdp_load(1);
    cdp_load_search_client(1)
});



//Cargar datos AJAX
function cdp_load(page) {
    var search = $("#search").val();
   var status_courier = $("#status_courier").val();
	var daterange = $("#daterange").val();
    var parametros = { "page": page, 'search': search, 'daterange': daterange, 'status_courier': status_courier };
    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/pickup/pickup_list_ajax.php',
        data: parametros,
        beforeSend: function (objeto) {
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
        }
    })
}


function cdp_load_search_client(page) {
    var search = $("#search_client").val();

    var parametros = { "page": page, 'search': search };
    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/pickup/search_client_ajax.php',
        data: parametros,
        beforeSend: function (objeto) {
        },
        success: function (data) {
            $(".search_client_outer_div").html(data).fadeIn('slow');
        }
    })
}


//cdp_eliminar
function cdp_eliminar(id) {

    var parent = $('#item_' + id).parent().parent();
    var name = $(this).attr('data-rel');
    new Messi('<p class="messi-warning"><i class="icon-warning-sign icon-3x pull-left"></i>' + message_delete_confirm + '<br /><strong>' + message_delete_confirm2 + '</strong></p>', {
        title: 'Cancel pick up',
        titleClass: '',
        modal: true,
        closeButton: true,
        buttons: [{
            id: 0,
            label: 'Cancel',
            class: '',
            val: 'Y'
        }],
        callback: function (val) {
            if (val === 'Y') {
                $.ajax({
                    type: 'post',
                    url: './ajax/pickup/pickup_cancel_ajax.php',
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

    });
}



function refusePickup(id) {

    var parent = $('#item_' + id).parent().parent();
    var name = $(this).attr('data-rel');
    new Messi('<p class="messi-warning"><i class="icon-warning-sign icon-3x pull-left"></i>Are you sure you want to refuse this record?<br /></p>', {
        title: 'Refuse pick up',
        titleClass: '',
        modal: true,
        closeButton: true,
        buttons: [{
            id: 0,
            label: 'Refuse',
            class: '',
            val: 'Y'
        }],
        callback: function (val) {
            if (val === 'Y') {
                $.ajax({
                    type: 'post',
                    url: './ajax/pickup/pickup_refuse_ajax.php',
                    data: {
                        'id': id,
                    },
                    beforeSend: function () {
                        parent.animate({
                            'backgroundColor': '#ffbc34'
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

    });
}


$("#driver_update").on('submit', function (event) {
    var parametros = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "ajax/courier/courier_update_driver_ajax.php",
        data: parametros,
        beforeSend: function (objeto) {
            $("#resultados_ajax").html("<img src='assets/images/loader.gif'/><br/>Wait a moment please...");
        },
        success: function (datos) {
            $("#resultados_ajax").html(datos);

            $('html, body').animate({
                scrollTop: 0
            }, 600);

            $('#modalDriver').modal('hide');

            cdp_load(1);


        }
    });
    event.preventDefault();

})


$('#modalDriver').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var id_shipment = button.data('id_shipment') // Extract info from data-* attributes
    var id_sender = button.data('id_sender') // Extract info from data-* attributes
    var modal = $(this)
    $('#id_shipment').val(id_shipment)
    $('#id_senderclient_driver_update').val(id_sender)



})



$("#send_email").on('submit', function (event) {

    $('#guardar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "GET",
        url: "send_email_pdf.php",
        data: parametros,
        beforeSend: function (objeto) {
            $(".resultados_ajax_mail").html("<img src='assets/images/loader.gif'/><br/>Wait a moment please...");
        },
        success: function (datos) {
            $(".resultados_ajax_mail").html(datos);
            $('#guardar_datos').attr("disabled", false);

        }
    });
    event.preventDefault();

})

$('#myModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var order = button.data('order') // Extract info from data-* attributes
    var id = button.data('id') // Extract info from data-* attributes
    var email = button.data('email') // Extract info from data-* attributes
    var modal = $(this)
    $('#subject').val("#" + order)
    $('#id').val(id)
    $('#sendto').val(email)
})




$("#cancel_pickup_form").on('submit', function (event) {

    $('#guardar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "ajax/pickup/pickup_cancel_ajax.php",
        data: parametros,
        beforeSend: function (objeto) {
            $("#resultados_ajax").html("<img src='assets/images/loader.gif'/><br/>Wait a moment please...");
        },
        success: function (datos) {
            $("#resultados_ajax").html(datos);
            $('#guardar_datos').attr("disabled", false);

            $('#myModalCancel').modal('hide');
            cdp_load(1);

        }
    });
    event.preventDefault();

})


$('#myModalCancel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal

    var id = button.data('id') // Extract info from data-* attributes

    var modal = $(this)
    $('#id_cancel').val(id)
})





$('#charges_list').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var id = button.data('id') // Extract info from data-* attributes
    $('#order_id').val(id);

    $(".resultados_ajax_charges_add_results").html('');

    cdp_load_charges(order_id); //Cargas los pagos 

})

function cdp_load_charges() {

    var id = $('#order_id').val();
    var parametros = {
        "id": id
    };
    $.ajax({

        url: 'ajax/accounts_receivable/charges_list_ajax.php',
        data: parametros,
        success: function (data) {
            $(".resultados_ajax_charges_list").html(data).fadeIn('slow');
        }
    });
}


$('#charges_add').on('show.bs.modal', function (event) {

    var id = $('#order_id').val();
    var parametros = {
        "id": id
    };

    $.ajax({
        url: 'ajax/accounts_receivable/modal_add_charges.php',
        data: parametros,
        success: function (data) {
            $(".resultados_ajax_add_modal").html(data).fadeIn('slow');
        }
    });
})





$("#add_charges").on('submit', function (event) {
    var parametros = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "ajax/accounts_receivable/add_charges_ajax.php",
        data: parametros,
        beforeSend: function (objeto) {
            $(".resultados_ajax").html("<img src='assets/images/loader.gif'/><br/>Wait a moment please...");
        },
        success: function (datos) {
            $(".resultados_ajax_charges_add_results").html(datos);

            $('#charges_add').modal('hide');
            cdp_load_charges();
            cdp_load(1);


        }
    });
    event.preventDefault();

})



$('#charges_edit').on('show.bs.modal', function (event) {

    var id = $('#order_id').val();

    var button = $(event.relatedTarget) // Button that triggered the modal
    var id_charge = button.data('id_charge')

    var parametros = {
        "id": id,
        'id_charge': id_charge
    };

    $.ajax({
        url: 'ajax/accounts_receivable/modal_edit_charges.php',
        data: parametros,
        success: function (data) {
            $(".resultados_ajax_add_modal_edit").html(data).fadeIn('slow');
        }
    });
})


$("#edit_charges").on('submit', function (event) {
    var parametros = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "ajax/accounts_receivable/update_charges_ajax.php",
        data: parametros,
        beforeSend: function (objeto) {
            $(".resultados_ajax_charges_add_results").html("<img src='assets/images/loader.gif'/><br/>Wait a moment please...");
        },
        success: function (datos) {
            $(".resultados_ajax_charges_add_results").html(datos);

            $('#charges_edit').modal('hide');
            cdp_load_charges();
            cdp_load(1);


        }
    });
    event.preventDefault();

})



//cdp_eliminar
function cdp_delete_charge(id) {


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
                    url: './ajax/accounts_receivable/charge_delete_ajax.php',
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
                        $('.resultados_ajax_charges_add_results').html(data);
                        cdp_load_charges();

                        cdp_load(1);
                    }
                });
            }
        }

    });
}

var autocomplete;
var address_field;
var country_field;
var country_field_label;
var autocompleteInstances = [];
var full_address;
var address_fields = []; // Declare in outer scope
function initAutocomplete() {

  address_fields = [document.querySelector("#starting_address")];
  	
	 address_fields.forEach((address, index) => {
    let autocomplete = new google.maps.places.Autocomplete(address, {
      fields: ["address_components", "geometry","formatted_address"],
      types: ["address"],
      strictBounds: false,
	  componentRestrictions: { country: "CA" } // Restrict to Canada
    });
    address.focus();
   
    autocompleteInstances[index] = autocomplete;

    autocomplete.addListener("place_changed", function () {
      fillInAddress(index);
    });
  });
  
}

function fillInAddress(index) {
  // Get the place details from the autocomplete object.
  const autocomplete = autocompleteInstances[index];
  const place = autocomplete.getPlace();
  full_address = place.formatted_address;

  let address1 = "";
  let postcode = "";

  /*for (const component of place.address_components) {
    const componentType = component.types[0];
	switch (componentType) {
      case "street_number":
        address1 = `${component.long_name} ${address1}`;
        break;

      case "route":
        address1 += component.long_name+", "; // Use long_name for full street name
        break;

      case "locality": // City
        address1 += component.long_name+", ";
        break;

      case "administrative_area_level_1": // State
        address1 += component.short_name+", ";
        break;

      case "country":
        address1 += component.long_name+", ";
        break;

      case "postal_code":
        address1 += component.long_name;
        break;
    }
  }*/
  
 
  const address_field = address_fields[index];
	  if (address_field) {
		//address_field.value = address1; // Set the formatted address value
		address_field.value = full_address; // Set the formatted address value
		const event = new Event('change', { bubbles: true, cancelable: true });
		address_field.dispatchEvent(event);
	  }
}


// get route
function get_route() {
	  let selected = $('input[name="accepted_order_check[]"]:checked'); // Get checked checkboxes

      if (selected.length > 0) {
        let values = selected.map(function () {
          return $(this).val();
        }).get(); // Convert jQuery object to an array

       // alert("Selected hobbies: " + values.join(", "));
		$('#get_route_modal').modal("show");
		initAutocomplete();
      } else {
        alert("Please select approved orders first.");
      }
}

function find_route(){
	var parametros = $("#order_form").serialize();
	var starting_address = $("#starting_address").val();
	if(starting_address!=''){
	parametros += "&starting_address=" + encodeURIComponent(starting_address);
		$.ajax({
			type: "POST",
			url: "ajax/pickup/fastest_route.php",
			data: parametros,
			beforeSend: function (objeto) {
				$(".resultados_ajax_charges_add_results").html("<img src='assets/images/loader.gif'/><br/>Wait a moment please...");
			},
			success: function (datos) {
			
				
					if(Number(datos)==0){
						alert("Please enter starting point.");
					}else if(Number(datos)>0){
						$('#get_route_modal').modal('hide');
						window.location.href="get_route.php?route_id="+datos;
					}else{
						alert("Error: Please try again.");
				}
			}
		});
		
	}else{
		alert("Please enter starting point.");
	}
	
}