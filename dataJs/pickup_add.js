"use strict";

var deleted_file_ids = [];

var packagesItems = [
  {
    qty: 1,
    description: "",
    length: 0,
    width: 0,
    height: 0,
    weight: 0,
    declared_value: 0,
    fixed_value: 0,
  },
];


// $(document).ready(function(){
// if ($('#sender_address_id').length == 1) {
// alert($('#sender_address_id').length);
// Bind an event on Select2 open action
//  var vlh = $('#sender_address_id option:first').val();
//  alert(vlh);

// $('#sender_address_id').on('select2:open', function() {

//   var options = $('#sender_address_id').find('option');
//   if(options.length > 0 && !$('#sender_address_id').val()) {
//   // Prevent the default behavior
//   e.preventDefault();
//   // Automatically select the first value from the results
//   ;
//   }
// Timeout to wait for the search results to be displayed
// alert("it is happening");
// setTimeout(function() {
// var options = $('#sender_address_id').find('option');
// if(options.length == 1) {
// // If only one option, set it as selected
// $('#sender_address_id').val(options.val()).trigger('change');
// // Close the Select2 dropdown
// $('#sender_address_id').select2('close');
// }
// }, 1); // Timeout set to 1ms to allow the dropdown to populate
// });
// if ($('#sender_address_id').length  == 1) {
//   // Automatically select the only option
//   alert("true")
//   $("#sender_address_id").val($("#sender_address_id option:first").val()).trigger('change');


// Function to calculate distance between two coordinates and update distance input
function calculateAndDisplayDistance(origin, destination, deliveryType) {
  // AJAX request to calculate distance
  if (!origin) {
    origin = $('#sender_address_id option:selected').text();
    if (!origin) {
      origin = $("#select2-sender_address_id-container").text()
    }
  }
  if (!destination) {
    destination = $('#recipient_address_id option:selected').text();
    if (!destination) {
      destination = $("#select2-recipient_address_id-container").text();
    }
  }
  if (!deliveryType) {
    deliveryType = document.getElementById('deliveryType').value;
  }
  $.ajax({
    type: 'POST',
    url: 'ajax/courier/calculate_distance.php', // Replace with your PHP script for calculating distance
    data: { 'origin': origin, 'destination': destination, 'deliveryType': deliveryType },
    dataType: 'json',
    success: function (data) {
      console.log("All", data);
      // Update distance input with calculated distance
      $('#distance').val(data.distance);
      // $('.fixed_value').val(data.shipmentfee);
      $('.fixed_value').val(data.baseRate);
      localStorage.setItem('baseRate', data.baseRate)
      localStorage.setItem('shipmentfee', data.shipmentfee)
      calculateFinalTotal();

    },
    error: function () {
      // Handle error
      //alert('Error calculating distance.');
    }
  });
}     // }
// Automatically select the first (and only) option
// Get the value of the first option
// var options = $('#sender_address_id').find('option');
// console.log("these are options:",options.length);
// if(options.length == 1) {
// // If only one option, set it as selected
// $('#sender_address_id').val(options.val()).trigger('change');
// }
// $('#sender_address_id').on('select2:open', function() {
//   // Timeout to wait for the search results to be displayed
//   setTimeout(function() {
//   var options = $('#sender_address_id').find('option');
//   if(options.length == 1) {
//   // If only one option, set it as selected
//   $('#sender_address_id').val(options.val()).trigger('change');
//   // Close the Select2 dropdown
//   $('#sender_address_id').select2('close');
//   }
//   }, 1); // Timeout set to 1ms to allow the dropdown to populate
//   });

// $('#sender_address_id').val(singleOption).trigger('change');
// }
// });
$(function () {
  loadPackages();

  $("#order_date").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
  });

  $("#register_customer_to_user").click(function () {
    if ($(this).is(":checked")) {
      $("#show_hide_user_inputs").removeClass("d-none");
    } else {
      $("#show_hide_user_inputs").addClass("d-none");
    }
  });

  cdp_load_countries("_modal_user");
  cdp_load_states("_modal_user");
  cdp_load_cities("_modal_user");

  cdp_load_countries("_modal_recipient");
  cdp_load_states("_modal_recipient");
  cdp_load_cities("_modal_recipient");

  cdp_load_countries("_modal_user_address");
  cdp_load_states("_modal_user_address");
  cdp_load_cities("_modal_user_address");

  cdp_load_countries("_modal_recipient_address");
  cdp_load_states("_modal_recipient_address");
  cdp_load_cities("_modal_recipient_address");

  cdp_select2_init_sender();
  cdp_select2_init_sender_address();
  cdp_select2_init_recipient_address();
  cdp_select2_init_recipient();
});

function cdp_load_countries(modal) {
  $("#country" + modal)
    .select2({
      ajax: {
        url: "ajax/select2_countries.php",
        dataType: "json",

        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
          };
        },
        processResults: function (data) {
          return {
            results: data,
          };
        },
        cache: true,
      },
      placeholder: translate_search_country,
      allowClear: true,
    })
    .on("change", function (e) {
      var country = $("#country" + modal).val();

      $("#state" + modal).attr("disabled", true);
      $("#state" + modal).val(null);

      if (country != null) {
        $("#state" + modal).attr("disabled", false);
      }

      cdp_load_states(modal);
    });
}

function cdp_load_states(modal) {
  var country = $("#country" + modal).val();

  $("#state" + modal)
    .select2({
      ajax: {
        url: "ajax/select2_states.php?id=" + country,
        dataType: "json",

        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
          };
        },
        processResults: function (data) {
          return {
            results: data,
          };
        },
        cache: true,
      },
      placeholder: translate_search_state,
      allowClear: true,
    })
    .on("change", function (e) {
      var state = $("#state" + modal).val();

      $("#city" + modal).attr("disabled", true);
      $("#city" + modal).val(null);

      if (state != null) {
        $("#city" + modal).attr("disabled", false);
      }

      cdp_load_cities(modal);
    });
}

function cdp_load_cities(modal) {
  var state = $("#state" + modal).val();

  $("#city" + modal).select2({
    ajax: {
      url: "ajax/select2_cities.php?id=" + state,
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data,
        };
      },
      cache: true,
    },
    placeholder: translate_search_city,
    allowClear: true,
  });
}

function cdp_preview_images() {
  $("#image_preview").html("");

  var total_file = document.getElementById("filesMultiple").files.length;

  for (var i = 0; i < total_file; i++) {
    var mime_type = event.target.files[i].type.split("/");
    var src = "";
    if (mime_type[0] == "image") {
      src = URL.createObjectURL(event.target.files[i]);
    } else {
      src = "assets/images/no-preview.jpeg";
    }

    $("#image_preview").append(
      '<div class="col-md-3" id="image_' +
      i +
      '">' +
      '<img style="width: 180px; height: 180px;" class="img-thumbnail" src="' +
      src +
      '">' +
      '<div class="row">' +
      '<div class=" col-md-12 mt-2 mb-2">' +
      "<span>" +
      event.target.files[i].name +
      "</span>" +
      "</div>" +
      "</div>" +
      '<div class="row">' +
      '<div class="  mb-2">' +
      '<button type="button" class="btn btn-danger btn-sm pull-left" onclick="cdp_deletePreviewImage(' +
      i +
      ');"><i class="fa fa-trash"></i></button>' +
      "</div>" +
      "</div>" +
      "</div>"
    );
  }
}

function cdp_deletePreviewImage(index) {
  deleted_file_ids.push(index);
  $("#deleted_file_ids").val(deleted_file_ids);
  $("#image_" + index).remove();
  var count_files = $("#total_item_files").val();

  count_files--;

  $("#total_item_files").val(count_files);

  if (count_files > 0) {
    $("#clean_files").removeClass("hide");
  } else {
    $("#clean_files").addClass("hide");
  }

  $("#selectItem").html("attached files (" + count_files + ")");

  var deleted_file = $("#deleted_file_ids").val();
}

function cdp_validateZiseFiles() {
  var inputFile = document.getElementById("filesMultiple");
  var file = inputFile.files;

  var size = 0;

  for (var i = 0; i < file.length; i++) {
    var filesSize = file[i].size;

    if (size > 5242880) {
      $(".resultados_file").html(
        "<div class='alert alert-danger'>" +
        "<button type='button' class='close' data-dismiss='alert'>&times;</button>" +
        "<strong>" +
        validation_files_size +
        " </strong>" +
        "</div>"
      );

      $("#filesMultiple").val("");
      $("#clean_files").addClass("hide");
      $("#image_preview").html("");
      deleted_file_ids = [];
    } else {
      $(".resultados_file").html("");
    }

    size += filesSize;
  }

  if (size > 5242880) {
    $(".resultados_file").html(
      "<div class='alert alert-danger'>" +
      "<button type='button' class='close' data-dismiss='alert'>&times;</button>" +
      "<strong>" +
      validation_files_size +
      " </strong>" +
      "</div>"
    );

    $("#filesMultiple").val("");
    $("#clean_files").addClass("hide");
    $("#image_preview").html("");
    deleted_file_ids = [];

    return true;
  } else {
    $(".resultados_file").html("");

    return false;
  }
}

$("#openMultiFile").on("click", function () {
  $("#filesMultiple").click();
});

$("#clean_file_button").on("click", function () {
  $("#filesMultiple").val("");

  $("#selectItem").html("Attach files");

  $("#clean_files").addClass("hide");
  $("#image_preview").html("");
});

$("input[type=file]").on("change", function () {
  deleted_file_ids = [];

  var inputFile = document.getElementById("filesMultiple");
  var file = inputFile.files;
  var contador = 0;
  for (var i = 0; i < file.length; i++) {
    contador++;
  }
  $("#total_item_files").val(contador);

  var count_files = $("#total_item_files").val();

  if (count_files > 0) {
    $("#clean_files").removeClass("hide");
  } else {
    $("#clean_files").addClass("hide");
  }

  $("#selectItem").html("attached files (" + count_files + ")");
});

function loadPackages() {
  $("#data_items").html("");
  packagesItems.forEach(function (item, index) {
    var html_code = "";
    html_code += '<div  class= "card-hover" id="row_id_' + index + '">';
    html_code += "<hr>";
    html_code += '<div class="row"> ';
    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-1">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_quantity +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" onchange="changePackage(this)" value="' +
      item.qty +
      '" onkeypress="return isNumberKey(event, this)"  name="qty" id="qty_' +
      index +
      '" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" title="' +
      translate_quantity +
      '"  value="1"  />' +
      "</div>" +
      "</div>" +
      "</div>";

    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-3">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_description +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" onchange="changePackage(this)" value="' +
      item.description +
      '" name="description" id="description_' +
      index +
      '" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" placeholder=" ' +
      translate_description +
      '" >' +
      "</div>" +
      "</div>" +
      "</div>";

    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-1">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_weight +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" onchange="changePackage(this)" value="' +
      item.weight +
      '" onkeypress="return isNumberKey(event, this)"  name="weight" id="weight_' +
      index +
      '" class="form-control input-sm" style="border: 1px solid red;" data-toggle="tooltip" data-placement="bottom" title="' +
      translate_weight +
      '"/>' +
      "</div>" +
      "</div>" +
      "</div>";

    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-1">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_length +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" onchange="changePackage(this)" value="' +
      item.length +
      '" onkeypress="return isNumberKey(event, this)" name="length" id="length_' +
      index +
      '" class="form-control input-sm text_only" data-toggle="tooltip" data-placement="bottom" title="' +
      translate_length +
      '"/>' +
      "</div>" +
      "</div>" +
      "</div>";
    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-1">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_width +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" onchange="changePackage(this)" value="' +
      item.width +
      '" onkeypress="return isNumberKey(event, this)" name="width" id="width_' +
      index +
      '" class="form-control input-sm text_only" data-toggle="tooltip" data-placement="bottom" title="' +
      translate_width +
      '"/>' +
      "</div>" +
      "</div>" +
      "</div>";

    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-1">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_height +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" onchange="changePackage(this)" value="' +
      item.height +
      '" onkeypress="return isNumberKey(event, this)"  name="height" id="height_' +
      index +
      '" class="form-control input-sm number_only" data-toggle="tooltip" data-placement="bottom" title="' +
      translate_height +
      '" />' +
      "</div>" +
      "</div>" +
      "</div>";

    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-1">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_volweight +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" readonly value="0" onkeypress="return isNumberKey(event, this)"  name="weightVol" id="weightVol_' +
      index +
      '" class="form-control input-sm number_only" data-toggle="tooltip" data-placement="bottom" title="' +
      translate_volweight +
      '" />' +
      "</div>" +
      "</div>" +
      "</div>";

    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-1">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_charge +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" readonly onchange="changePackage(this)" value="' +
      item.fixed_value +
      '" onkeypress="return isNumberKey(event, this)"  name="fixed_value" id="fixedValue_' +
      index +
      '" class="form-control input-sm number_only" data-toggle="tooltip" data-placement="bottom" title="' +
      translate_charge +
      '"/>' +
      "</div>" +
      "</div>" +
      "</div>";

    html_code +=
      '<div class="col-sm-12 col-md-6 col-lg-1">' +
      '<div class="form-group">' +
      '<label for="emailAddress1"> ' +
      translate_declared +
      "</label>" +
      '<div class="input-group">' +
      '<input type="text" onchange="changePackage(this)" value="' +
      item.declared_value +
      '" onkeypress="return isNumberKey(event, this)"  name="declared_value" id="declaredValue_' +
      index +
      '" class="form-control input-sm number_only" data-toggle="tooltip" data-placement="bottom" title="' +
      translate_declared +
      '"/>' +
      "</div>" +
      "</div>" +
      "</div>";

    if (index > 0) {
      html_code +=
        '<div class="col-sm-12 col-md-6 col-lg-1">' +
        '<div class="form-group  mt-4">' +
        '<button type="button"  onclick="deletePackage(' +
        index +
        ')"  name="remove_rows"  class="btn btn-outline-danger "><i class="ti ti-trash"></i>   </button>' +
        "</div>" +
        "</div>";
    }
    html_code += "</div>";

    html_code += "<hr>";

    html_code += "</div>";

    $("#data_items").append(html_code);
  });
}

function addPackage() {
  packagesItems.push({
    qty: 1,
    description: "",
    length: 0,
    width: 0,
    height: 0,
    weight: 0,
    declared_value: 0,
    fixed_value: 0,
  });

  var index = packagesItems.length - 1;

  loadPackages();
  calculateFinalTotal();
  $("#create_invoice").attr("disabled", true);

  $("#row_id_" + index).animate(
    {
      backgroundColor: "#18BC9C",
    },
    400
  );

  $("#add_row").attr("disabled", true);

  setTimeout(function () {
    $("#row_id_" + index).css({ "background-color": "" });
    $("#add_row").attr("disabled", false);
  }, 900);
}

function deletePackage(index) {
  packagesItems = packagesItems.filter((item, i) => index !== i);
  $("#row_id_" + index).animate(
    {
      backgroundColor: "#FFBFBF",
    },
    400
  );

  $("#row_id_" + index).fadeOut(400, function () {
    $("#row_id_" + index).remove();
    loadPackages();
    calculateFinalTotal();
    $("#create_invoice").attr("disabled", true);
  });
}

function changePackage(e) {
  var field = e.id.split("_");
  packagesItems = packagesItems.map(function (item, index) {
    if (index === parseInt(field[1])) {
      item[e.name] = e.value;
    }

    if (field[0] !== "description") {
      if (!e.value) {
        $("#" + e.id).val(0);
        item[e.name] = 0;
      }
    }
    return item;
  });
  calculateFinalTotal();
  $("#create_invoice").attr("disabled", true);
}

function loading_calculation() {
  $("#total_distance").html('0.00');
  $("#total_before_tax").html('0.00');
  $("#tax_13").html('0.00');
  $("#total_after_tax").html('0.00');
  $("#create_invoice").attr("disabled", true);
  $("#calculate_invoice").attr("disabled", true);
}

function calculateFinalTotal(element = null) {
  if (element) {
    if (!element.value) {
      $(element).val(0);
    }
  }

  var sumador_total = 0;
  var sumador_valor_declarado = 0;
  var max_fixed_charge = 0;
  var sumador_libras = 0;
  var sumador_volumetric = 0;

  var precio_total = 0;
  var total_impuesto = 0;
  var total_descuento = 0;
  var total_seguro = 0;
  var total_peso = 0;
  var total_impuesto_aduanero = 0;
  var total_valor_declarado = 0;

  var tariffs_value = $("#tariffs_value").val();
  var declared_value_tax = $("#declared_value_tax").val();
  var insurance_value = $("#insurance_value").val();
  var tax_value = $("#tax_value").val();
  var discount_value = $("#discount_value").val();
  var reexpedicion_value = $("#reexpedicion_value").val();
  var price_lb = $("#price_lb").val();
  var insured_value = $("#insured_value").val();

  var core_meter = $("#core_meter").val();
  var core_min_cost_tax = $("#core_min_cost_tax").val();
  var core_min_cost_declared_tax = $("#core_min_cost_declared_tax").val();

  reexpedicion_value = parseFloat(reexpedicion_value);
  insured_value = parseFloat(insured_value);
  price_lb = parseFloat(price_lb);

  packagesItems.forEach(function (item, i) {
    var quantity = parseFloat(item.qty);
    var description = parseFloat(item.description);
    var weight = parseFloat(item.weight);
    var length = parseFloat(item.length);
    var width = parseFloat(item.width);
    var height = parseFloat(item.height);
    // var baseRate = localStorage.getItem('baseRate');
    var fixed_value = parseFloat(item.fixed_value);
    // var fixed_value = parseFloat(baseRate);
    var declared_value = parseFloat(item.declared_value);

    var total_metric = (length * width * height) / core_meter;
    total_metric = parseFloat(total_metric);

    $("#weightVol_" + i).val(total_metric.toFixed(2));

    if (weight > total_metric) {
      var calculate_weight = weight;
    } else {
      var calculate_weight = total_metric;
    }

    sumador_libras += weight;
    sumador_volumetric += total_metric;

    sumador_valor_declarado += declared_value;
    max_fixed_charge += fixed_value;
  });

  // precio_total = calculate_weight * price_lb;
  sumador_total += price_lb;

  if (sumador_total > core_min_cost_tax) {
    total_impuesto = (sumador_total * tax_value) / 100;
  }

  if (sumador_valor_declarado > core_min_cost_declared_tax) {
    total_valor_declarado =
      (sumador_valor_declarado * declared_value_tax) / 100;
  }

  total_descuento = (sumador_total * discount_value) / 100;
  total_peso = sumador_libras + sumador_volumetric;
  total_seguro = (insured_value * insurance_value) / 100;
  total_impuesto_aduanero = total_peso * tariffs_value;
  var total_envio =
    sumador_total -
    total_descuento +
    total_seguro +
    total_impuesto +
    total_impuesto_aduanero +
    total_valor_declarado +
    max_fixed_charge +
    reexpedicion_value;

  if (total_descuento > sumador_total) {
    alert(validation_discount_1);
    $("#discount_value").val(0);
    return false;
  } else if (discount_value < 0) {
    alert(validation_discount_2);
    $("#discount_value").val(0);
    return false;
  }
  var shipmentfee = localStorage.getItem('shipmentfee');

  $("#subtotal").html(shipmentfee);
  $("#discount").html(total_descuento.toFixed(2));
  $("#impuesto").html(total_impuesto.toFixed(2));
  $("#declared_value_label").html(total_valor_declarado.toFixed(2));
  var baseRate = localStorage.getItem('baseRate');

  // $("#fixed_value_label").html(max_fixed_charge.toFixed(2));
  $("#fixed_value_label").html(baseRate);
  $("#fixed_value_ajax").val(baseRate);
  var distanceHtml = parseFloat($('#distance').val()).toFixed(2)
  $("#total_distance").html(distanceHtml);
  //$("#insurance").html(total_seguro.toFixed(2));
  //$("#total_impuesto_aduanero").html(total_impuesto_aduanero.toFixed(2));
  var shipmentfee = localStorage.getItem('shipmentfee');
  $("#total_before_tax").html(Number(shipmentfee).toFixed(2));
  var total_tax_value = parseFloat(parseFloat(shipmentfee) + (parseFloat(shipmentfee) * (13 / 100)));
  $("#total_after_tax").html(total_tax_value.toFixed(2));
  var tax = 0.00;
  tax = parseFloat(total_tax_value) - parseFloat(shipmentfee);
  $("#tax_13").html(tax.toFixed(2));
  // alert(parseFloat(shipmentfee));
  // alert(parseFloat(total_envio.toFixed(2)));
  // parseInt(shipmentfee.toFixed(2))
  // var subTotal = parseFloat(shipmentfee) + parseFloat(total_envio.toFixed(2));
  //$("#total_envio").html(shipmentfee);
  $("#total_envio_ajax").val(shipmentfee);

  // $("#total_weight").html(sumador_libras.toFixed(2));
  //$("#total_vol_weight").html(sumador_volumetric.toFixed(2));
  $("#total_fixed").html(max_fixed_charge.toFixed(2));
  //$("#total_declared").html(shipmentfee);
  $("#create_invoice").attr("disabled", false);
}

$("#invoice_form").on("submit", function (event) {
  
  event.preventDefault();
  // if (cdp_validateZiseFiles() == true) {
  //   alert("error files");
  //   return false;
  // }


  var prefix_check = $("#prefix_check").val();
  var code_prefix = $("#code_prefix").val();
  var code_prefix2 = $("#code_prefix2").val();
  var notify_whatsapp_sender = $(
    "input:checkbox[name=notify_whatsapp_sender]:checked"
  ).val();

  var notify_whatsapp_receiver = $(
    "input:checkbox[name=notify_whatsapp_receiver]:checked"
  ).val();
  var order_no = $("#order_no").val();
  var agency = $("#agency").val();
  var origin_off = $("#origin_off").val();
  var sender_id = $("#sender_id_temp").val();
  var sender_address_id = $("#sender_address_id").val();

  var recipient_id = $("#recipient_id").val();
  var recipient_address_id = $("#recipient_address_id").val();
  var order_date = $("#order_date").val();
  var status_courier = $("#status_courier").val();

  var driver_id = $("#driver_id").val();

  var price_lb = $("#price_lb").val();
  var insured_value = $("#insured_value").val();
  var insurance_value = $("#insurance_value").val();
  var reexpedicion_value = $("#reexpedicion_value ").val();
  var discount_value = $("#discount_value").val();
  var tax_value = $("#tax_value").val();
  var declared_value_tax = $("#declared_value_tax").val();
  var tariffs_value = $("#tariffs_value").val();
  var baseRate = localStorage.getItem('baseRate');
  var shipmentfee = localStorage.getItem('shipmentfee');
  var delivery_notes = $("#delivery_notes").val();
  
  var deleted_file_ids = $("#deleted_file_ids").val();

  
  var data = new FormData();

  // Initialize variables
  var tags = [];
  var charge = "";
  var no_of_rx = "";
  var notes_for_driver = "";

  // Get business type
  var business_type = $("#businessType").val();

  if (business_type && business_type === "pharmacy") {
    // Collect checked checkbox values
    $('input[name="tags[]"]:checked').each(function() {
      tags.push($(this).val());
    });

    // Collect other form inputs
    charge = $("#charge").val();
    no_of_rx = $("#rxNumber").val();
    notes_for_driver = $("#notesForDriver").val();
  }

  // Append tags as individual entries
  tags.forEach(function(tag, index) {
    data.append("tags[]", tag);
  });

  // Append other form fields
  data.append("charge", charge);
  data.append("no_of_rx", no_of_rx);
  data.append("notes_for_driver", notes_for_driver);



  if (delivery_notes) {
    data.append("notes", delivery_notes);
  }
  if (prefix_check) {
    data.append("prefix_check", prefix_check);
  }
  if (baseRate) {
    data.append("fixed_rate", baseRate);
  }
  if (shipmentfee) {
    data.append("pickuptotal", shipmentfee);
  }
  if (code_prefix) {
    data.append("code_prefix", code_prefix);
  }
  if (code_prefix2) {
    data.append("code_prefix2", code_prefix2);
  }
  if (order_no) {
    data.append("order_no", order_no);
  }
  if (agency) {
    data.append("agency", agency);
  }
  if (origin_off) {
    data.append("origin_off", origin_off);
  }
  if (sender_id) {
    data.append("sender_id", sender_id);
  }
  if (sender_address_id) {
    data.append("sender_address_id", sender_address_id);
  }
  if (recipient_id) {
    data.append("recipient_id", recipient_id);
  }
  if (!recipient_address_id) {
    recipient_address_id = $("#recipient_address_id").val();
  }
  if (recipient_address_id) {
    data.append("recipient_address_id", recipient_address_id);
  }
  if (order_date) {
    data.append("order_date", order_date);
  }

  if (status_courier) {
    data.append("status_courier", status_courier);
  }
  if (driver_id) {
    data.append("driver_id", driver_id);
  }
  if (price_lb) {
    data.append("price_lb", price_lb);
  }
  if (insured_value) {
    data.append("insured_value", insured_value);
  }
  if (reexpedicion_value) {
    data.append("reexpedicion_value", reexpedicion_value);
  }
  if (discount_value) {
    data.append("discount_value", discount_value);
  }
  if (tax_value) {
    data.append("tax_value", tax_value);
  }
  if (declared_value_tax) {
    data.append("declared_value_tax", declared_value_tax);
  }
  if (tariffs_value) {
    data.append("tariffs_value", tariffs_value);
  }
  if (insurance_value) {
    data.append("insurance_value", insurance_value);
  }

  if (notify_whatsapp_sender) {
    data.append("notify_whatsapp_sender", notify_whatsapp_sender);
  }

  if (notify_whatsapp_receiver) {
    data.append("notify_whatsapp_receiver", notify_whatsapp_receiver);
  }

  if (deleted_file_ids) {
    data.append("deleted_file_ids", deleted_file_ids);
  }

  var total_order = $("#total_after_tax").text();
  data.append('total_order', total_order);

  var delivery_type = $("#deliveryType").val();
  data.append("delivery_type", delivery_type);

  var distance = $("#distance").val();
  data.append("distance", distance);

  var sub_total = $("#total_before_tax").text();
  data.append("sub_total", sub_total);

  var total_file = document.getElementById("filesMultiple").files.length;

  for (var i = 0; i < total_file; i++) {
    data.append(
      "filesMultiple[]",
      document.getElementById("filesMultiple").files[i]
    );
  }

  $.ajax({
    type: "POST",
    url: "ajax/pickup/add_pickup_client_ajax.php",
    data: data,
    contentType: false,
    dataType: "json",
    cache: false, // To unable request pages to be cached
    processData: false,
    beforeSend: function (objeto) {
      $("#create_invoice").attr("disabled", true);
      Swal.fire({
        title: message_loading,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
    },
    success: function (data) {
      $("#create_invoice").attr("disabled", false);
      if (data.success) {
        cdp_showSuccess(data.messages, data.shipment_id);
      } else {
        cdp_showError(data.errors);
      }
    },
  });

  event.preventDefault();
});

$(function () {
  $("#code_prefix2").hide();
  $("#prefix_check").on("change", function (event) {
    if ($("#prefix_check").is(":checked")) {
      $("#code_prefix").hide();
      $("#code_prefix").attr("disabled", true);
      $("#prefix_check").val(1);
      $("#code_prefix2").show();
      $("#code_prefix2").attr("disabled", false);
      $("#code_prefix2").attr("required", "true");
    } else {
      $("#prefix_check").val(0);
      $("#code_prefix2").hide();
      $("#code_prefix2").attr("disabled", true);
      $("#code_prefix").show();
      $("#code_prefix").attr("disabled", false);
      $("#code_prefix2").attr("required", "false");
    }
  });
});

function isNumberKey(evt, element) {
  var charCode = evt.which ? evt.which : event.keyCode;
  if (
    charCode > 31 &&
    (charCode < 48 || charCode > 57) &&
    !(charCode == 46 || charCode == 8)
  )
    return false;
  else {
    var len = $(element).val().length;
    var index = $(element).val().indexOf(".");
    if (index > 0 && charCode == 46) {
      return false;
    }
    if (index > 0) {
      var CharAfterdot = len + 1 - index;
      if (CharAfterdot > 4) {
        return false;
      }
    }
  }
  return true;
}

function cdp_select2_init_sender() {
  $("#sender_id")
    .select2({
      ajax: {
        url: "ajax/select2_sender.php",
        dataType: "json",

        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
          };
        },
        processResults: function (data) {
          return {
            results: data,
          };
        },
        cache: true,
      },

      minimumInputLength: 2,
      placeholder: search_sender,
      allowClear: true,
    })
    .on("change", function (e) {
      var sender_id = $("#sender_id").val();
      $("#sender_address_id").attr("disabled", true);
      $("#recipient_id").attr("disabled", true);

      $("#recipient_address_id").attr("disabled", true);
      $("#add_address_sender").attr("disabled", true);
      $("#add_recipient").attr("disabled", true);
      $("#add_address_recipient").attr("disabled", true);

      $("#recipient_id").val(null);
      $("#sender_address_id").val(null);
      $("#recipient_address_id").val(null);
      // $("#table-totals").addClass("d-none");

      if (sender_id != null) {
        $("#add_address_sender").attr("disabled", false);
        $("#sender_address_id").attr("disabled", false);
        $("#recipient_id").attr("disabled", false);
        $("#add_recipient").attr("disabled", false);
      }
      cdp_select2_init_sender_address();
      cdp_select2_init_recipient_address();
      cdp_select2_init_recipient();
    });
}

function cdp_select2_init_sender_address() {
  var sender_id = $("#sender_id").val();
  $("#sender_address_id")
    .select2({
      ajax: {
        url: "ajax/select2_sender_addresses.php?id=" + sender_id,
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
          };
        },
        processResults: function (data) {
          return {
            results: data,
          };
        },
        cache: true,
      },

      escapeMarkup: function (markup) {
        return markup;
      }, // let our custom formatter work
      // minimumInputLength: 1,
      templateResult: cdp_formatAdress, // omitted for brevity, see the source of this page
      templateSelection: cdp_formatAdressSelection, // omitted for brevity, see the source of this page
      // minimumInputLength: 2,
      placeholder: search_sender_address,
      allowClear: true,
    })
    .on("change", function (e) {
      var sender_address_id = $("#sender_address_id").val();
      var recipient_address_id = $("#recipient_address_id").val();
      if (!recipient_address_id || !sender_address_id) {
        $("#table-totals").addClass("d-none");
      }
    });
}

function cdp_formatAdress(item) {
  if (item.loading) return item.text;
  console.log("Items:", item.text);
  var markup = "<div class='select2-result-repository clearfix'>";

  markup +=
    "<div class='select2-result-repository__statistics'>" +
    "<div class='select2-result-repository__forks'><i class='la la-code-fork mr-0'></i> <b> " +
    translate_search_address_address +
    ": </b> " +
    item.text +
    " | <b>" +
    translate_search_address_country +
    ": </b>" +
    item.country +
    " | <b>" +
    translate_search_address_state +
    ": </b>" +
    item.state +
    " | <b>" +
    translate_search_address_city +
    ": </b>" +
    item.city +
    " | <b>" +
    translate_search_address_zip +
    ": </b>" +
    item.zip_code +
    " </div>" +
    "</div>" +
    "</div></div>";

  return markup;
}

function cdp_formatAdressSelection(repo) {
  return repo.text;
}

function cdp_select2_init_recipient() {
  var sender_id = $("#sender_id").val();

  $("#recipient_id")
    .select2({
      ajax: {
        url: "ajax/select2_recipient.php?id=" + sender_id,
        dataType: "json",

        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
          };
        },
        processResults: function (data) {
          return {
            results: data,
          };
        },
        cache: true,
      },
      // minimumInputLength: 2,
      placeholder: search_recipient,
      allowClear: true,
    })
    .on("change", function (e) {
      var recipient_id = $("#recipient_id").val();
      $("#add_address_recipient").attr("disabled", true);
      $("#recipient_address_id").attr("disabled", true);
      $("#recipient_address_id").val(null);
      // $("#table-totals").addClass("d-none");

      if (recipient_id != null) {
        $("#recipient_address_id").attr("disabled", false);
        $("#add_address_recipient").attr("disabled", false);
      }
      cdp_select2_init_recipient_address();
    });
}

function cdp_select2_init_recipient_address() {
  var recipient_id = $("#recipient_id").val();
  
  $("#recipient_address_id")
    .select2({
      ajax: {
        url: "ajax/select2_recipient_addresses.php?id=" + recipient_id,
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
          };
        },
        processResults: function (data) {
          return {
            results: data,
          };
        },
        cache: true,
      },

      escapeMarkup: function (markup) {
        return markup;
      }, // let our custom formatter work
      // minimumInputLength: 1,
      templateResult: cdp_formatAdress, // omitted for brevity, see the source of this page
      templateSelection: cdp_formatAdressSelection, // omitted for brevity, see the source of this page
      // minimumInputLength: 2,
      placeholder: search_recipient_address,
      allowClear: true,
    });
}


// modal guardar cliente remitente formulario de envo, si activas el check adicionas contraseña al cliente

$("#add_user_from_modal_shipments").on("submit", function (event) {
  event.preventDefault(); // Evitar el envío del formulario por defecto

  if ($.trim($("#full_name").val()).length == 0) {
    Swal.fire({

      type: 'Error!',
      title: 'Oops...',
      text: "Full Name is required",
      icon: 'error',
      confirmButtonColor: '#336aea'

    });
    $("#full_name").focus();
    return false;
  }

  // if ($.trim($("#lname").val()).length == 0) {
  //   Swal.fire({

  //     type: 'Error!',
  //     title: 'Oops...',
  //     text: message_error_form82,
  //     icon: 'error',
  //     confirmButtonColor: '#336aea'

  //   });
  //   $("#lname").focus();
  //   return false;
  // }

  // Validación del correo electrónico en el lado del cliente
  var email = $.trim($("#email").val());
  // if (email.length == 0) {
  //   Swal.fire({
  //     type: 'error',
  //     title: 'Oops...',
  //     text: message_error_form83,
  //     icon: 'error',
  //     confirmButtonColor: '#336aea'
  //   });
  //   $("#email").focus();
  //   return false;
  // } else 
  if (email && !isValidEmailAddress(email)) { // Función para validar el formato del correo electrónico
    Swal.fire({
      type: 'warning',
      title: 'Oops...',
      text: message_error_form84,
      icon: 'warning',
      confirmButtonColor: '#336aea'
    });
    $("#email").focus();
    return false;
  }

  // Función para validar el formato del correo electrónico
  function isValidEmailAddress(email) {
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
  }

  if ($.trim($("#country_modal_user").val()).length == 0) {
    Swal.fire({

      type: 'Error!',
      title: 'Oops...',
      text: validation_country,
      icon: 'error',
      confirmButtonColor: '#336aea'

    });
    $("#country_modal_user").focus();
    return false;
  }

  if ($.trim($("#state_modal_user").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_state,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#state_modal_user").focus();
    return false;
  }

  if ($.trim($("#city_modal_user").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_city,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#city_modal_user").focus();
    return false;
  }

  if ($.trim($("#postal_modal_user").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_zip,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#postal_modal_user").focus();
    return false;
  }

  if ($.trim($("#address_modal_user").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_address,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#address_modal_user").focus();
    return false;
  }

  // if (iti_sender.isValidNumber()) {
    var sender_id = $("#sender_id").val();
    $("#save_data_user").attr("disabled", true);
    var parametros = $(this).serialize();

    $.ajax({
      type: "POST",
      url: "ajax/courier/add_users_ajax.php?sender=" + sender_id,
      data: parametros,
      success: function (response) {
        if (response.status === 'success') {
          Swal.fire({
            type: 'success',
            title: message_error_form80,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            cdp_select2_init_sender();
            $(".resultados_ajax_add_user_modal_sender").html(response.data);
            $("#save_data_user").attr("disabled", false);
            $("#myModalAddUser").modal("hide");

            // Obtener la información del cliente y la dirección del cliente de la respuesta
            var data = {
              id: response.customer_data.id,
              text: response.customer_data.fname + ' ' + response.customer_data.lname
            };

            var newOption = new Option(data.text, data.id, false, false);

            $('#sender_id').append(newOption).trigger('change');
            $('#sender_id').val(data.id).trigger('change');

            var data_address = {
              id: response.customer_address.id_addresses,
              text: response.customer_address.address
            };

            var newOptionAddress = new Option(data_address.text, data_address.id, false, false);

            $('#sender_address_id').append(newOptionAddress).trigger('change');
            $('#sender_address_id').val(data_address.id).trigger('change');

            $("#recipient_address_id").attr("disabled", true);
            $("#add_address_recipient").attr("disabled", true);
            $("#recipient_id").val(null).trigger('change');
            $("#recipient_address_id").val(null).trigger('change');

            window.setTimeout(function () {
              $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
              });
            }, 5000);
          });
        } else {
          Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: response.message,
            icon: 'error',
            confirmButtonColor: '#336aea'
          });
          $("#save_data_user").attr("disabled", false);
        }
      },
      error: function () {
        Swal.fire({
          type: 'error',
          title: 'Oops...',
          text: message_error_form19,
          icon: 'error',
          confirmButtonColor: '#336aea'
        });
        $("#save_data_user").attr("disabled", false);
      }
    });
  // } else {
  //   input_sender.classList.add("error");
  //   var errorCode = iti_sender.getValidationError();
  //   errorMsgSender.innerHTML = errorMap[errorCode];
  //   errorMsgSender.classList.remove("hide");
  // }
});




// modal guardar cliente destinatario formulario de envios

$("#add_recipient_from_modal_shipments").on("submit", function (event) {
  event.preventDefault(); // Evitar el envío del formulario por defecto

  if ($.trim($("#fullname_recipient").val()).length == 0) {
    Swal.fire({

      type: 'Error!',
      title: 'Oops...',
      text: "Recipient Full Name is required",
      icon: 'error',
      confirmButtonColor: '#336aea'

    });
    $("#fullname_recipient").focus();
    return false;
  }

  // if ($.trim($("#lname_recipient").val()).length == 0) {
  //   Swal.fire({

  //     type: 'Error!',
  //     title: 'Oops...',
  //     text: translate_label_lastname,
  //     icon: 'error',
  //     confirmButtonColor: '#336aea'

  //   });
  //   $("#lname_recipient").focus();
  //   return false;
  // }

  // Validación del correo electrónico en el lado del cliente
  var email = $.trim($("#email_recipient").val());
  // if (email.length == 0) {
  //   Swal.fire({
  //     type: 'error',
  //     title: 'Oops...',
  //     text: translate_label_email,
  //     icon: 'error',
  //     confirmButtonColor: '#336aea'
  //   });
  //   $("#email_recipient").focus();
  //   return false;
  // } else 
  if (email && !isValidEmailAddress(email)) { // Función para validar el formato del correo electrónico
    Swal.fire({
      type: 'warning',
      title: 'Oops...',
      text: message_error_form84,
      icon: 'warning',
      confirmButtonColor: '#336aea'
    });
    $("#email_recipient").focus();
    return false;
  }

  // Función para validar el formato del correo electrónico
  function isValidEmailAddress(email) {
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
  }

  if ($.trim($("#country_modal_recipient").val()).length == 0) {
    Swal.fire({

      type: 'Error!',
      title: 'Oops...',
      text: validation_country,
      icon: 'error',
      confirmButtonColor: '#336aea'

    });
    $("#country_modal_recipient").focus();
    return false;
  }

  if ($.trim($("#state_modal_recipient").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_state,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#state_modal_recipient").focus();
    return false;
  }

  if ($.trim($("#city_modal_recipient").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_city,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#city_modal_recipient").focus();
    return false;
  }

  if ($.trim($("#postal_modal_recipient").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_zip,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#postal_modal_recipient").focus();
    return false;
  }

  if ($.trim($("#address_modal_recipient").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_address,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#address_modal_recipient").focus();
    return false;
  }

  // if (iti_recipient.isValidNumber()) {
    var sender_id = $("#sender_id").val();
    $("#save_data_recipient").attr("disabled", true);
    var parametros = $(this).serialize();

    $.ajax({
      type: "POST",
      url: "ajax/courier/add_recipients_ajax.php?sender=" + sender_id,
      data: parametros,
      success: function (response) {
        if (response.status === 'success') {
          Swal.fire({
            type: 'success',
            title: message_error_form80,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            // Acciones después de un éxito
            cdp_select2_init_sender();
            $(".resultados_ajax_add_user_modal_recipient").html(response.data);
            $("#save_data_recipient").attr("disabled", false);
            $("#myModalAddRecipient").modal("hide");

            // Actualizar campos de select
            var data = {
              id: response.customer_data.id,
              text: response.customer_data.fname + ' ' + response.customer_data.lname
            };

            var newOption = new Option(data.text, data.id, false, false);

            $('#recipient_id').append(newOption).trigger('change');
            $('#recipient_id').val(data.id).trigger('change');

            var data_address = {
              id: response.customer_address.id_addresses,
              text: response.customer_address.address
            };

            var newOption = new Option(data_address.text, data_address.id, false, false);

            $('#recipient_address_id').append(newOption).trigger('change');
            $('#recipient_address_id').val(data_address.id).trigger('change');

            // Ocultar mensaje de alerta
            window.setTimeout(function () {
              $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
              });
            }, 5000);
          });
        } else {
          // Manejo de errores si la respuesta no es exitosa
          Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: response.message,
            icon: 'error',
            confirmButtonColor: '#336aea'
          });
          $("#save_data_recipient").attr("disabled", false);
        }
      },
      error: function () {
        // Manejo de errores si la solicitud falla
        Swal.fire({
          type: 'error',
          title: 'Oops...',
          text: message_error_form19,
          icon: 'error',
          confirmButtonColor: '#336aea'
        });
        $("#save_data_recipient").attr("disabled", false);
      }
    });

  // } else {
  //   input_recipient.classList.add("error");
  //   var errorCode = iti_recipient.getValidationError();
  //   errorMsgRecipient.innerHTML = errorMap[errorCode];
  //   errorMsgRecipient.classList.remove("hide");
  // }
});







// modal guardar direccion cliente remitente 

$("#add_address_users_from_modal_shipments").on("submit", function (event) {
  event.preventDefault(); // Evitar el envío del formulario por defecto

  if ($.trim($("#country_modal_user_address").val()).length == 0) {
    Swal.fire({

      type: 'Error!',
      title: 'Oops...',
      text: validation_country,
      icon: 'error',
      confirmButtonColor: '#336aea'

    });
    $("#country_modal_user_address").focus();
    return false;
  }

  if ($.trim($("#state_modal_user_address").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_state,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#state_modal_user_address").focus();
    return false;
  }

  if ($.trim($("#city_modal_user_address").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_city,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#city_modal_user_address").focus();
    return false;
  }

  if ($.trim($("#postal_modal_user_address").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_zip,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#postal_modal_user_address").focus();
    return false;
  }

  if ($.trim($("#address_modal_user_address").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_address,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#address_modal_user_address").focus();
    return false;
  }


  var sender_id = $("#sender_id").val();
  $("#save_data_address_users").attr("disabled", true);
  var parametros = $(this).serialize();

  $.ajax({
    type: "POST",
    url: "ajax/courier/add_address_users_ajax.php?sender=" + sender_id,
    data: parametros,
    success: function (response) {
      if (response.status === 'success') {
        Swal.fire({
          type: 'success',
          title: message_error_form80,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
        }).then(() => {

          $("#save_data_address_users").attr("disabled", false);
          $(".resultados_ajax_add_user_modal_sender").html(response.data);
          $("#myModalAddUserAddresses").modal("hide");


          var data_address = {
            id: response.customer_address.id_addresses,
            text: response.customer_address.address
          };

          var newOptionAddress = new Option(data_address.text, data_address.id, false, false);

          $('#sender_address_id').append(newOptionAddress).trigger('change');
          $('#sender_address_id').val(data_address.id).trigger('change');

          window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
              $(this).remove();
            });
          }, 5000);
        });
      } else {
        Swal.fire({
          type: 'error',
          title: 'Oops...',
          text: response.message,
          icon: 'error',
          confirmButtonColor: '#336aea'
        });
        $("#save_data_address_users").attr("disabled", false);
      }
    },
    error: function () {
      Swal.fire({
        type: 'error',
        title: 'Oops...',
        text: message_error_form19,
        icon: 'error',
        confirmButtonColor: '#336aea'
      });
      $("#save_data_address_users").attr("disabled", false);
    }
  });

});
var autocomplete;
var address_field;
var country_field;
var country_field_label;



function initAutocomplete() {

  const address_fields = [document.querySelector("#address_modal_recipient_address"), document.querySelector("#address_modal_user_address"), document.querySelector("#address_modal_recipient")];
  //var country_array = ["AFG","ALB","DZA","AND","AGO","ATG","ARG","ARM","AUS","AUT","AZE","BHS","BHR","BGD","BRB","BLR","BEL","BLZ","BEN","BMU","BTN","BOL","BIH","BWA","BRA","BRN","BGR","BFA","BDI","KHM","CMR","CAN","CPV","CAF","TCD","CHL","CHN","COL","COM","COG","COD","CRI","CIV","HRV","CUB","CYP","CZE","DNK","DJI","DMA","DOM","TLS","ECU","EGY","SLV","GNQ","ERI","EST","ETH","FJI","FIN","FRA","GAB","GMB","GEO","DEU","GHA","GRC","GRD","GTM","GIN","GNB","GUY","HTI","HND","HKG","HUN","ISL","IND","IDN","IRN","IRQ","IRL","ISR","ITA","JAM","JPN","JOR","KAZ","KEN","KIR","PRK","KOR","KWT","KGZ","LAO","LVA","LBN","LSO","LBR","LBY","LIE","LTU","LUX","MKD","MDG","MWI","MYS","MDV","MLI","MLT","MHL","MRT","MUS","MEX","FSM","MDA","MCO","MNG","MNE","MAR","MOZ","MMR","NAM","NRU","NPL","BES","NLD","NZL","NIC","NER","NGA","NOR","OMN","PAK","PLW","PAN","PNG","PRY","PER","PHL","POL","PRT","PRI","QAT","ROU","RUS","RWA","KNA","LCA","VCT","WSM","SMR","STP","SAU","SEN","SRB","SYC","SLE","SGP","SVK","SVN","SLB","SOM","ZAF","SSD","ESP","LKA","SDN","SUR","SWZ","SWE","CHE","SYR","TWN","TJK","TZA","THA","TGO","TON","TTO","TUN","TUR","TKM","TUV","UGA","UKR","ARE","GBR","USA","URY","UZB","VUT","VEN","VNM","VIR","YEM","ZMB","ZWE","XKX","test","HA","ISM","MAR","YU","YU"];

  address_fields.forEach(address => {
    let autocomplete = new google.maps.places.Autocomplete(address, {
      // componentRestrictions: { country: new_country_array },
      fields: ["address_components", "geometry"],
      types: ["address"],
      strictBounds: false,
    });
    address.focus();
    // When the user selects an address from the drop-down, populate the
    // address fields in the form.
    autocomplete.addListener("place_changed", fillInAddress);
  })
  // Create the autocomplete object, restricting the search predictions to
  // addresses in the US and Canada.
  // autocomplete = new google.maps.places.Autocomplete(address_field, {
  //   // componentRestrictions: { country: new_country_array },
  //   fields: ["address_components", "geometry"],
  //   types: ["address"],
  //   strictBounds: false,
  // });
  // address_field.focus();
  // // When the user selects an address from the drop-down, populate the
  // // address fields in the form.
  // autocomplete.addListener("place_changed", fillInAddress);

}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  const place = autocomplete.getPlace();
  let address1 = "";
  let postcode = "";

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  // place.address_components are google.maps.GeocoderAddressComponent objects
  // which are documented at http://goo.gle/3l5i5Mr
  for (const component of place.address_components) {
    // @ts-ignore remove once typings fixed
    const componentType = component.types[0];

    switch (componentType) {
      case "street_number": {
        address1 = `${component.long_name} ${address1}`;
        break;
      }

      case "route": {
        address1 += component.short_name;
        break;
      }

    }

    address_field.value = address1;
  }
}

window.initAutocomplete = initAutocomplete;


// modal guardar direccion cliente destinatario 

$("#add_address_recipients_from_modal_shipments").on("submit", function (event) {
  event.preventDefault(); // Evitar el envío del formulario por defecto

  if ($.trim($("#country_modal_recipient_address").val()).length == 0) {
    Swal.fire({

      type: 'Error!',
      title: 'Oops...',
      text: validation_country,
      icon: 'error',
      confirmButtonColor: '#336aea'

    });
    $("#country_modal_recipient_address").focus();
    return false;
  }

  if ($.trim($("#state_modal_recipient_address").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_state,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#state_modal_recipient_address").focus();
    return false;
  }

  if ($.trim($("#city_modal_recipient_address").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_city,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#city_modal_recipient_address").focus();
    return false;
  }

  if ($.trim($("#postal_modal_recipient_address").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_zip,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#postal_modal_recipient_address").focus();
    return false;
  }

  if ($.trim($("#address_modal_recipient_address").val()).length == 0) {
    Swal.fire({
      type: 'error',
      title: 'Oops...',
      text: validation_address,
      icon: 'error',
      confirmButtonColor: '#336aea'
    });
    $("#address_modal_recipient_address").focus();
    return false;
  }

  var recipient_id = $("#recipient_id").val();
  $("#save_data_address_recipients").attr("disabled", true);
  var parametros = $(this).serialize();

  $.ajax({
    type: "POST",
    url: "ajax/courier/add_address_recipients_ajax.php?recipient=" + recipient_id,
    data: parametros,
    success: function (response) {
      if (response.status === 'success') {
        Swal.fire({
          type: 'success',
          title: message_error_form80,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
        }).then(() => {

          $("#save_data_address_recipients").attr("disabled", false);
          $(".resultados_ajax_add_user_modal_recipient").html(response.data);
          $("#myModalAddRecipientAddresses").modal("hide");


          var data_address = {
            id: response.customer_address.id_addresses,
            text: response.customer_address.address
          };

          var newOptionAddress = new Option(data_address.text, data_address.id, false, false);

          $('#recipient_address_id').append(newOptionAddress).trigger('change');
          $('#recipient_address_id').val(data_address.id).trigger('change');

          window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
              $(this).remove();
            });
          }, 5000);
        });
      } else {
        Swal.fire({
          type: 'error',
          title: 'Oops...',
          text: response.message,
          icon: 'error',
          confirmButtonColor: '#336aea'
        });
        $("#save_data_address_recipients").attr("disabled", false);
      }
    },
    error: function () {
      Swal.fire({
        type: 'error',
        title: 'Oops...',
        text: message_error_form19,
        icon: 'error',
        confirmButtonColor: '#336aea'
      });
      $("#save_data_address_recipients").attr("disabled", false);
    }
  });

});

var errorMsg = document.querySelector("#error-msg-sender");
var validMsg = document.querySelector("#valid-msg-sender");

var errorMsgRecipient = document.querySelector("#error-msg-recipient");
var validMsgRecipient = document.querySelector("#valid-msg-recipient");

// here, the index maps to the error code returned from getValidationError - see readme
var errorMap = [
  "Invalid number",
  "Invalid country code",
  "Mobile number too short",
  "Mobile number too long",
  "Invalid mobile number",
];

var input = document.querySelector("#phone_custom");
var iti = window.intlTelInput(input, {
  geoIpLookup: function (callback) {
    $.get("http://ipinfo.io", function () { }, "jsonp").always(function (resp) {
      var countryCode = resp && resp.country ? resp.country : "";
      callback(countryCode);
    });
  },
  initialCountry: "auto",
  nationalMode: true,

  separateDialCode: true,
  utilsScript: "assets/template/assets/libs/intlTelInput/utils.js",
});

var reset = function () {
  input.classList.remove("error");
  input_recipient.classList.remove("error");

  errorMsg.innerHTML = "";
  errorMsg.classList.add("hide");
  validMsg.classList.add("hide");


  errorMsgRecipient.innerHTML = "";
  errorMsgRecipient.classList.add("hide");
  validMsgRecipient.classList.add("hide");
};

// on blur: validate
input.addEventListener("blur", function () {
  reset();
  if (input.value.trim()) {
    if (iti.isValidNumber()) {
      $("#phone").val(iti.getNumber());

      validMsg.classList.remove("hide");
    } else {
      input.classList.add("error");
      var errorCode = iti.getValidationError();
      errorMsg.innerHTML = errorMap[errorCode];
      errorMsg.classList.remove("hide");
    }
  }
});

// on keyup / change flag: reset
input.addEventListener("change", reset);
input.addEventListener("keyup", reset);

var input_recipient = document.querySelector("#phone_custom_recipient");
var iti_recipient = window.intlTelInput(input_recipient, {
  geoIpLookup: function (callback) {
    $.get("http://ipinfo.io", function () { }, "jsonp").always(function (resp) {
      var countryCode = resp && resp.country ? resp.country : "";
      callback(countryCode);
    });
  },
  initialCountry: "auto",
  nationalMode: true,

  separateDialCode: true,
  utilsScript: "assets/template/assets/libs/intlTelInput/utils.js",
});

// on blur: validate
input_recipient.addEventListener("blur", function () {
  reset();
  if (input_recipient.value.trim()) {
    if (iti_recipient.isValidNumber()) {
      $("#phone_recipient").val(iti_recipient.getNumber());

      validMsg.classList.remove("hide");
    } else {
      input_recipient.classList.add("error");
      var errorCode = iti_recipient.getValidationError();
      errorMsgRecipient.innerHTML = errorMap[errorCode];
      errorMsgRecipient.classList.remove("hide");
    }
  }
});

// on keyup / change flag: reset
input_recipient.addEventListener("change", reset);
input_recipient.addEventListener("keyup", reset);

function cdp_showError(errors) {
  var html_code = "";
  html_code += '<ul class="error" > ';

  for (var error in errors) {
    html_code += '<li class="text-left">';
    html_code += '<i class="icon-double-angle-right"></i>';
    html_code += errors[error];
    html_code += "</li>";
  }
  ("</ul >");

  Swal.fire({
    title: message_error,
    html: html_code,
    icon: "error",
    allowOutsideClick: false,
    confirmButtonText: "Ok",
  });
}

function cdp_showSuccess(messages, shipment_id) {
  Swal.fire({
    title: messages,
    icon: "success",
    allowOutsideClick: false,
    confirmButtonText: "Ok",
  }).then((result) => {
    if (result.isConfirmed) {
      setTimeout(function () {
        window.location = "courier_view.php?id=" + shipment_id;
      }, 2000);
    }
  });
}

function getTariffs() {

  var recipient_id = $("#recipient_id").val();
  var recipient_address_id = $("#recipient_address_id").val();

  var sender_id = $("#sender_id").val();
  var sender_address_id = $("#sender_address_id").val();

  var deliveryType = $("#deliveryType").val();
  // var packages = JSON.stringify(packagesItems);
  if (!recipient_id || !recipient_address_id || !sender_id || !sender_address_id || !deliveryType) {
    Swal.fire({
      title: "Error!",
      text: "Please enter required fields",
      icon: "error",
      confirmButtonText: "Ok",
    });
    return;
  }
  // var data = {
  //   packages: packages,
  //   sender_id: sender_id,
  //   sender_address: sender_address_id,
  //   recipient_address: recipient_address_id,
  //   recipient_id: recipient_id,
  // };

  // $.ajax({
  //   type: "POST",
  //   data: data,
  //   url: "ajax/courier/get_price_range_weight_tariffs_ajax.php",
  //   dataType: "json",
  //   beforeSend: function (objeto) {
  //     $(".resultados_ajax").html("Loading...");
  //   },
  //   success: function (data) {
  //     if (data.success) {
  $("#table-totals").removeClass("d-none");

  loading_calculation();
  // $("#price_lb").val(data.data.price);
  // $("#price_lb_label").html(data.data.price);
  calculateAndDisplayDistance(null, null, null);

  //       } else {
  //         $("#table-totals").addClass("d-none");
  //         $("#create_invoice").attr("disabled", true);
  //         Swal.fire({
  //           title: "Error!",
  //           text: data.error,
  //           icon: "error",
  //           confirmButtonText: "Ok",
  //         });
  //       }
  //     },
  //   });
}


$(document).ready(function () {

  // $("#sender_address_id")
  //   .select2({
  //     ajax: {
  //       url: "ajax/select2_sender_addresses.php?id=" + sender_id,
  //       dataType: "json",
  //       delay: 250,
  //       data: function (params) {
  //         return {
  //           q: params.term, // search term
  //         };
  //       },
  //       dataType: 'json',
  //       success: function(data) {
  //         console.log("sender data:",data);
  //       }
  //     }
  //   });
  var sender_id = $("#sender_id").val();
  $.ajax({
    type: 'POST',
    url: "ajax/select2_sender_addresses.php?id=" + sender_id, // Replace with your PHP script for calculating distance
    // data: { 'origin': origin, 'destination': destination, 'deliveryType':deliveryType },
    data: function (params) {
      return {
        q: params.term, // search term
      };
    },
    dataType: 'json',
    success: function (data) {
      // console.log("Data length:",$('#sender_address_id').length);
      // console.log('sender detail:',data);
      if (data.length == 1) {
        // alert("True");
        // Automatically select the first (and only) option
        // $('#select2-sender_address_id-container').text(data[0].text);
        // $("#sender_address_id").val(data[0].id);
        $('#sender_address_id').empty().append('<option value="' + data[0].id + '">' + data[0].text + '</option>').trigger('change');
      }

    },
    error: function () {
      // Handle error
      alert('Error calculating distance.');
    }


  });

  document.querySelector('#deliveryType').addEventListener("change", function () {
    getTariffs();
  });

  $(document).on('change', '#recipient_id', function () {
    var recipient_id = $(this).val();
    // alert(recipient_id);
    $.ajax({
      type: 'POST',
      url: "ajax/select2_recipient_addresses.php?id=" + recipient_id, // Replace with your PHP script for calculating distance
      // data: { 'origin': origin, 'destination': destination, 'deliveryType':deliveryType },
      data: function (params) {
        return {
          q: params.term, // search term
        };
      },
      dataType: 'json',
      success: function (data) {
        // console.log("Data length:",$('#sender_address_id').length);
        // console.log('receiver detail:',data);
        if (data.length == 1) {
          // alert("True");
          // Automatically select the first (and only) option
          // $('#select2-recipient_address_id-container').text(data[0].text);
          // $("#recipient_address_id").val(data[0].id)
          $('#recipient_address_id').empty().append('<option value="' + data[0].id + '">' + data[0].text + '</option>');

        }
        //  console.log('sender detail:',data);        

      },
      error: function () {
        // Handle error
        alert('Error calculating distance.');
      }
    });
  });

  $(document).on('change', '#recipient_address_id', function () {
    // var recipient_id = $(this).val();
    var recipient_id = $("#recipient_id option:selected").val();
    // alert(recipient_id);
    $.ajax({
      type: 'POST',
      url: "ajax/select2_recipient_addresses.php?id=" + recipient_id, // Replace with your PHP script for calculating distance
      // data: { 'origin': origin, 'destination': destination, 'deliveryType':deliveryType },
      data: function (params) {
        return {
          q: params.term, // search term
        };
      },
      dataType: 'json',
      success: function (data) {
        // console.log("Data length:",$('#sender_address_id').length);
        // console.log('receiver detail:',data);
        if (data.length == 1) {
          // alert("True");
          // Automatically select the first (and only) option
          // $('#select2-recipient_address_id-container').text(data[0].text);
          // $("#recipient_address_id").val(data[0].id)
          $('#recipient_address_id').empty().append('<option value="' + data[0].id + '">' + data[0].text + '</option>');

        }
        //  console.log('sender detail:',data);        

      },
      error: function () {
        // Handle error
        alert('Error calculating distance.');
      }
    });
  });
  // $('#sender_address_id select').trigger('change.select2');
  // alert(recipient_id);


  var senderadd = "";
  var receiveradd = "";
  var deliveryType = "";

  // if ($('#recipient_address_id option').length == 1) {
  //   // Automatically select the first (and only) option
  //   var singleOption = $('#recipient_address_id option').first().val();
  //   $('#recipient_address_id').val(singleOption).trigger('change');
  // }



  $('#sender_address_id').on('select2:select', function (e) {
    // Get the selected data
    var selectedData = e.params.data;

    // Get the selected value and text
    // var selectedValue = selectedData.id;
    senderadd = selectedData.text;

    // Display the selected value and text


    calculateAndDisplayDistance(senderadd, receiveradd, deliveryType);
  });

  $('#recipient_address_id').on('select2:select', function (e) {
    // Get the selected data
    var selectedData = e.params.data;

    // Get the selected value and text
    // var selectedValue = selectedData.id;
    receiveradd = selectedData.text;

    console.log("Selected receiver value:", receiveradd);
    // Display the selected value and text
    calculateAndDisplayDistance(senderadd, receiveradd, deliveryType);

  });

  $('#id_of_your_checkboxreceiver').click(function () {
    if ($(this).is(':checked')) {
      $('#add_address_recipient').removeAttr('disabled');
    } else {
      $('#add_address_recipient').attr('disabled', 'disabled');
    }
  });
  $('#id_of_your_checkboxsender').click(function () {
    if ($(this).is(':checked')) {
      $('#add_address_sender').removeAttr('disabled');
    } else {
      $('#add_address_sender').attr('disabled', 'disabled');
    }
  });
});

function loadStates(selectedCountryId, selectedStateId, selectedCityId, modelId)
{
      // Select state
      if (selectedStateId) {
        var $stateSelect = modelId ? $("#state_modal_recipient" + modelId) : $("#state_modal_recipient");
        $.ajax({
          url: "ajax/select2_states.php?id=" + selectedCountryId, // Your data source URL for states
          dataType: "json",
          data: {
            country_id: selectedCountryId
          },
          success: function (statesData) {
            // Find the selected state's text
            var selectedState = statesData.find(function (state) {
              return state.id == selectedStateId;
            });
    
            // Create a new option element
            var newStateOption = new Option(selectedState.text, selectedState.id, true, true);
    
            // Append it to the select
            $stateSelect.append(newStateOption).trigger('change');
    
            // Manually trigger the change event to update Select2
            $stateSelect.trigger({
              type: 'select2:select',
              params: {
                data: selectedState
              }
            });
    
            // After state is selected, load cities
            loadCities(selectedState.id, selectedCityId, modelId); // Assuming cdp_load_cities(modal) function exists
          }
        });
      }
    
}

function loadCities(selectedStateId, selectedCityId, modelId)
{

      // Select city
      if (selectedCityId) {
        var $citySelect = modelId ? $("#city_modal_recipient" + modelId) : $("#city_modal_recipient");
        $.ajax({
          url: "ajax/select2_cities.php?id=" + selectedStateId, // Your data source URL for cities
          dataType: "json",
          data: {
            state_id: selectedStateId
          },
          success: function (citiesData) {
            // Find the selected city's text
            var selectedCity = citiesData.find(function (city) {
              return city.id == selectedCityId;
            });
    
            // Create a new option element
            var newCityOption = new Option(selectedCity.text, selectedCity.id, true, true);
    
            // Append it to the select
            $citySelect.append(newCityOption).trigger('change');
    
            // Manually trigger the change event to update Select2
            $citySelect.trigger({
              type: 'select2:select',
              params: {
                data: selectedCity
              }
            });
          }
        });
      }
}

function loadCountries(fullAddress, modelId)
{
  if (!fullAddress) return;

    var selectedCountryId = fullAddress.country;
    var selectedStateId = fullAddress.state;
    var selectedCityId = fullAddress.city;
    var selectedZip = fullAddress.zip_code;
    var $countrySelect;
    modelId ? $("#postal_modal_recipient" + modelId).val(selectedZip) : $("#postal_modal_recipient").val(selectedZip);
    // Select country
    if (selectedCountryId) {
      $countrySelect = modelId ? $countrySelect = $("#country_modal_recipient" + modelId) : $("#country_modal_recipient");
      $.ajax({
        url: "ajax/select2_countries.php", // Your data source URL for countries
        dataType: "json",
        success: function (countriesData) {
          // Find the selected country's text
          var selectedCountry = countriesData.find(function (country) {
            return country.id == selectedCountryId;
          });
  
          // Create a new option element
          var newCountryOption = new Option(selectedCountry.text, selectedCountry.id, true, true);
  
          // Append it to the select
          $countrySelect.append(newCountryOption).trigger('change');
  
          // Manually trigger the change event to update Select2
          $countrySelect.trigger({
            type: 'select2:select',
            params: {
              data: selectedCountry
            }
          });

          // After country is selected, load states
          loadStates(selectedCountry.id, selectedStateId, selectedCityId, modelId); 
        }
      });
    }
    

}

function getRecipientFullAddress(inputAddress, modelId)
{
  var recipientAddress = $("#" + inputAddress).val();
  console.log(recipientAddress);

  $.ajax({
    type: 'POST',
    url: 'ajax/recipients/get_recipient_full_address.php',
    data: { 'address_modal_recipient': recipientAddress },
    dataType: 'json',
    success: function (response) {
      if(response.status){
        var fullAddress = response.fullAddress;
        loadCountries(fullAddress, modelId);
      }else{
        // alert(response.message);
      }

    },
    error: function () {
      // Handle error
      alert('Error: Something Went Wrong!.');
    }
  });
  
}

$("#address_modal_recipient").on("change", function(){
  getRecipientFullAddress("address_modal_recipient", "");
});


$("#address_modal_recipient_address").on("change", function(){
  getRecipientFullAddress("address_modal_recipient_address", "_address");
});