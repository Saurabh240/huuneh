"use strict";
var deleted_file_ids = [];

$(function () {
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

  cdp_load(1);

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

//Cargar datos AJAX
function cdp_load(page) {
  var search = $("#search").val();
  var status_courier = $("#status_courier").val();
  var filterby = $("#filterby").val();
  var parametros = {
    page: page,
    search: search,
    status_courier: status_courier,
    filterby: filterby,
  };
  $("#loader").fadeIn("slow");
  $.ajax({
    url: "./ajax/consolidate_packages/courier_list_add_ajax.php",
    data: parametros,
    beforeSend: function (objeto) {},
    success: function (data) {
      $(".outer_div").html(data).fadeIn("slow");
    },
  });
}

$("#save_data").on("submit", function (event) {
  var parametros = $(this).serialize();

  $.ajax({
    type: "POST",
    url: "ajax/tools/category/category_add_ajax.php",
    data: parametros,
    beforeSend: function (objeto) {
      $("#resultados_ajax").html("Please wait...");
    },
    success: function (datos) {
      $("#resultados_ajax").html(datos);

      $("html, body").animate(
        {
          scrollTop: 0,
        },
        600
      );
    },
  });
  event.preventDefault();
});

// buscador de prefijo y iso en select 2
$(document).ready(function () {
    // Inicializar Select2 en el elemento
    $('#code_prefix_select').select2();

    // Al cargar la página, ocultar el select por defecto
    $('#code_prefix_select').hide();

    // Obtener el ancho del input por defecto
    var inputWidth = $("#code_prefix_input").outerWidth();

    // Aplicar el mismo ancho al contenedor del Select2
    $('#code_prefix_select').next('.select2-container').css('width', inputWidth);

    // Manejar el cambio en el checkbox
    $("#prefix_check").on("change", function (event) {
        if ($("#prefix_check").is(":checked")) {
            // Ocultar el input y deshabilitarlo
            $("#code_prefix_input").hide().prop("disabled", true);

            // Cambiar el valor del checkbox y mostrar el select
            $("#prefix_check").val(1);
            $("#code_prefix_select").show().prop("disabled", false);

            // Habilitar la validación requerida para el select
            $("#code_prefix_select").prop("required", true);

            // Si Select2 se está utilizando, actualizar su visibilidad y ancho
            $('#code_prefix_select').next('.select2-container').show().css('width', inputWidth);
        } else {
            // Cambiar el valor del checkbox y ocultar el select
            $("#prefix_check").val(0);
            $("#code_prefix_select").hide().prop("disabled", true);

            // Mostrar el input y habilitarlo
            $("#code_prefix_input").show().prop("disabled", false);

            // Deshabilitar la validación requerida para el select
            $("#code_prefix_select").prop("required", false);

            // Si Select2 se está utilizando, ocultarlo
            $('#code_prefix_select').next('.select2-container').hide();
        }
    });

    // Al cargar la página, verificar el estado inicial del checkbox
    if ($("#prefix_check").is(":checked")) {
        $("#code_prefix_input").hide().prop("disabled", true);
        $("#code_prefix_select").show().prop("disabled", false);
        $('#code_prefix_select').next('.select2-container').css('width', inputWidth).show();
    } else {
        $("#code_prefix_input").show().prop("disabled", false);
        $("#code_prefix_select").hide().prop("disabled", true);
        $('#code_prefix_select').next('.select2-container').hide();
    }
});

var selected = [];

$(document).ready(function () {
  var count = 0;

  $(document).on("click", ".remove_row", function () {
    var row_id = $(this).attr("id");
    var parent = $("#row_id_" + row_id);

    var index = selected.indexOf(row_id);

    selected.splice(index, 1);

    parent.animate(
      {
        backgroundColor: "#FFBFBF",
      },
      400
    );

    count--;
    parent.fadeOut(400, function () {
      $("#row_id_" + row_id).remove();
      cdp_cal_final_total();
    });
    $("#total_item").val(selected.length);
  });

  $("#create_invoice").on("click", function () {
      if ($.trim($("#total_item").val()) <= 1) {
        Swal.fire({
            type: 'warning',
            title: 'Oops...',
            text: message_error_form85,
            iconHtml: '<i class="fas fa-exclamation-triangle" style="font-size: 48px; color: orange;"></i>',
            confirmButtonColor: '#336aea'
        });
        return false;
      }

      if ($.trim($("#seals").val()).length == 0) {
        Swal.fire({
            title: 'Oops...',
            text: message_error_form91,
            icon: 'error',
            confirmButtonColor: '#336aea'
        });
        $("#seals").css("border-color", "red"); // Aplica el borde rojo al input
        $("#seals").focus();
        return false;
      }

      if ($.trim($("#recipient_id").val()).length == 0) {
        Swal.fire({
            title: 'Oops...',
            text: message_error_form86,
            icon: 'error',
            confirmButtonColor: '#336aea'
        });
        return false;
      }

      if ($.trim($("#recipient_address_id").val()).length == 0) {
        Swal.fire({
            title: 'Oops...',
            text: message_error_form87,
            icon: 'error',
            confirmButtonColor: '#336aea'
        });
        return false;
      }

      //data sender

      if ($.trim($("#sender_id").val()).length == 0) {
        Swal.fire({
            title: 'Oops...',
            text: message_error_form88,
            icon: 'error',
            confirmButtonColor: '#336aea'
        });
        return false;
      }

      if ($.trim($("#sender_address_id").val()).length == 0) {
        Swal.fire({
            title: 'Oops...',
            text: message_error_form89,
            icon: 'error',
            confirmButtonColor: '#336aea'
        });
        return false;
      }


      if ($.trim($("#driver_id").val()) == 0) {
        Swal.fire({
            title: 'Oops...',
            text: message_error_form90,
            icon: 'error',
            confirmButtonColor: '#336aea'
        });
        return false;
      }

      $("#invoice_form").submit();
  });
});

function cdp_cal_final_total() {
  var count = $("#total_item").val();
  console.log(count);

  var sumador_total = 0;
  var sumador_libras = 0;
  var sumador_volumetric = 0;

  var precio_total = 0;
  var total_impuesto = 0;
  var total_descuento = 0;
  var total_seguro = 0;
  var total_peso = 0;
  var total_impuesto_aduanero = 0;

  var core_meter = $("#core_meter").val();
  var core_min_cost_tax = $("#core_min_cost_tax").val();

  var tariffs_value = $("#tariffs_value").val();
  var insurance_value = $("#insurance_value").val();

  var tax_value = $("#tax_value").val();
  var discount_value = $("#discount_value").val();

  var reexpedicion_value = $("#reexpedicion_value").val();

  reexpedicion_value = parseFloat(reexpedicion_value);

  var insured_value = $("#insured_value").val();

  insured_value = parseFloat(insured_value);

  var price_lb = $("#price_lb").val();

  price_lb = parseFloat(price_lb);

  console.log(selected);

  selected.forEach(function (valor, indice, array) {
    console.log("En el índice " + indice + " hay este valor: " + valor);

    var weight = $("#weight_" + valor).val();
    weight = parseFloat(weight);

    console.log(weight + " weight");

    var height = $("#height_" + valor).val();
    height = parseFloat(height);

    console.log(height + " height");

    var length = $("#length_" + valor).val();
    length = parseFloat(length);

    console.log(length + " length");

    var width = $("#width_" + valor).val();
    width = parseFloat(width);

    console.log(width + " width");

    var total_vol = $("#total_vol_" + valor).val();
    total_vol = parseFloat(total_vol);

    // calculate weight columetric box size
    var total_metric = (length * width * height) / core_meter;

    // calculate weight x price
    if (weight > total_metric) {
      var calculate_weight = weight;
      sumador_libras += weight; //Sumador
    } else {
      var calculate_weight = total_metric;
      sumador_volumetric += total_metric; //Sumador
    }

    precio_total = calculate_weight * price_lb;
    sumador_total += precio_total;

    if (sumador_total > core_min_cost_tax) {
      total_impuesto = (sumador_total * tax_value) / 100;
    }

    total_descuento = (sumador_total * discount_value) / 100;

    total_peso = sumador_libras + sumador_volumetric;

    total_seguro = (insured_value * insurance_value) / 100;

    total_impuesto_aduanero = total_peso * tariffs_value;
  });

  var total_envio =
    sumador_total -
    total_descuento +
    total_seguro +
    total_impuesto +
    total_impuesto_aduanero +
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

  $("#subtotal").html(sumador_total.toFixed(2));

  $("#discount").html(total_descuento.toFixed(2));
  $("#discount_input").val(total_descuento.toFixed(2));

  $("#subtotal_input").val(sumador_total.toFixed(2));

  $("#impuesto").html(total_impuesto.toFixed(2));
  $("#impuesto_input").val(total_impuesto.toFixed(2));

  $("#insurance").html(total_seguro.toFixed(2));
  $("#insurance_input").val(total_seguro.toFixed(2));

  $("#total_libras").html(sumador_libras.toFixed(2));

  $("#total_volumetrico").html(sumador_volumetric.toFixed(2));

  $("#total_peso").html(total_peso.toFixed(2));
  $("#total_weight_input").val(total_peso.toFixed(2));

  $("#total_impuesto_aduanero").html(total_impuesto_aduanero.toFixed(2));
  $("#total_impuesto_aduanero_input").val(total_impuesto_aduanero.toFixed(2));

  $("#total_envio").html(total_envio.toFixed(2));
  $("#total_envio_input").val(total_envio.toFixed(2));
}

function cdp_add_item(
  id,
  total_vol,
  weight,
  length,
  width,
  height,
  tracking,
  order_no,
  order_prefix
) {
  if (selected.includes(id)) {
    Swal.fire({
        title: 'Error!',
        text: message_error_consolidate_add_packages,
        icon: 'error',
        confirmButtonColor: '#336aea'
    });

  } else {
    count++;

    $("#modal_consolidate").html("");
    selected.push(id);
    $("#total_item").val(selected.length);
    var parent = $("#row_id_" + id);

    var html_code = "";
    html_code += '<tr class="card-hover " id="row_id_' + id + '">';

    html_code += '<td class="" colspan="3"> <b>' + tracking + " </b></td>";
    html_code += '<td class="text-center"  colspan="2">' + weight + "</td>";
    html_code += '<td class="text-center"></td>';
    html_code += '<td class="text-center">' + total_vol + "</td>";

    html_code +=
      '<input type="hidden"  id="total_vol_' +
      id +
      '"  value="' +
      total_vol +
      '" name="weight_vol[]">';
    html_code +=
      '<input type="hidden"   value="' + order_prefix + '" name="prefix[]">';
    html_code +=
      '<input type="hidden"   value="' + order_no + '" name="order_no_item[]">';
    html_code +=
      '<input type="hidden" id="weight_' +
      id +
      '"   value="' +
      weight +
      '" name="weight[]">';

    html_code +=
      '<input type="hidden" id="length_' +
      id +
      '"   value="' +
      length +
      '" name="length[]">';
    html_code +=
      '<input type="hidden" id="height_' +
      id +
      '"   value="' +
      height +
      '" name="height[]">';
    html_code +=
      '<input type="hidden" id="width_' +
      id +
      '"   value="' +
      width +
      '" name="width[]">';
    html_code +=
      '<input type="hidden" id="order_id_' +
      id +
      '"   value="' +
      id +
      '" name="order_id[]">';

    html_code +=
      '<td class="text-center"><button type="button" name="remove_row" id="' +
      id +
      '" class="btn btn-danger btn-xs remove_row mt-2"><i class="fa fa-trash"></i></button></td>';

    html_code += "</tr>";

    $("#invoice-item-table").append(html_code);

    // Marcar la fila correspondiente con un color rojo suave
    $("#row_id_" + id).addClass("selected-row marked-row");

    cdp_cal_final_total();

    $("#add_row").attr("disabled", true);

    setTimeout(function () {
      $("#row_id_" + id).css({ "background-color": "" });
      $("#add_row").attr("disabled", false);
    }, 900);
  }
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
      $("#table-totals").addClass("d-none");

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
  $("#sender_address_id").select2({
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
  });
}

function cdp_formatAdress(item) {
  if (item.loading) return item.text;
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
      $("#table-totals").addClass("d-none");

      if (recipient_id != null) {
        $("#recipient_address_id").attr("disabled", false);
        $("#add_address_recipient").attr("disabled", false);
      }
      cdp_select2_init_recipient_address();
    });
}

function cdp_select2_init_recipient_address() {
  var recipient_id = $("#recipient_id").val();

  $("#recipient_address_id").select2({
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

    if ($.trim($("#fname").val()).length == 0) {
        Swal.fire({

              type: 'Error!',
              title: 'Oops...',
              text: message_error_form81,
              icon: 'error',
              confirmButtonColor: '#336aea'
              
        });
        $("#fname").focus();
        return false;
    }

    if ($.trim($("#lname").val()).length == 0) {
        Swal.fire({

              type: 'Error!',
              title: 'Oops...',
              text: message_error_form82,
              icon: 'error',
              confirmButtonColor: '#336aea'
              
        });
        $("#lname").focus();
        return false;
    }

    // Validación del correo electrónico en el lado del cliente
    var email = $.trim($("#email").val());
    if (email.length == 0) {
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: message_error_form83,
            icon: 'error',
            confirmButtonColor: '#336aea'
        });
        $("#email").focus();
        return false;
    } else if (!isValidEmailAddress(email)) { // Función para validar el formato del correo electrónico
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

    if (iti.isValidNumber()) {
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
     } else {
      input.classList.add("error");
      var errorCode = iti.getValidationError();
      errorMsgSender.innerHTML = errorMap[errorCode];
      errorMsgSender.classList.remove("hide");
    }
});




// modal guardar cliente destinatario formulario de envios

$("#add_recipient_from_modal_shipments").on("submit", function (event) {
    event.preventDefault(); // Evitar el envío del formulario por defecto

    if ($.trim($("#fname_recipient").val()).length == 0) {
        Swal.fire({

              type: 'Error!',
              title: 'Oops...',
              text: translate_label_firstname,
              icon: 'error',
              confirmButtonColor: '#336aea'
              
        });
        $("#fname_recipient").focus();
        return false;
    }

    if ($.trim($("#lname_recipient").val()).length == 0) {
        Swal.fire({

              type: 'Error!',
              title: 'Oops...',
              text: translate_label_lastname,
              icon: 'error',
              confirmButtonColor: '#336aea'
              
        });
        $("#lname_recipient").focus();
        return false;
    }

    // Validación del correo electrónico en el lado del cliente
    var email = $.trim($("#email_recipient").val());
    if (email.length == 0) {
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: translate_label_email,
            icon: 'error',
            confirmButtonColor: '#336aea'
        });
        $("#email_recipient").focus();
        return false;
    } else if (!isValidEmailAddress(email)) { // Función para validar el formato del correo electrónico
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

    if (iti_recipient.isValidNumber()) {
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

    } else {
      input_recipient.classList.add("error");
      var errorCode = iti_recipient.getValidationError();
      errorMsgRecipient.innerHTML = errorMap[errorCode];
      errorMsgRecipient.classList.remove("hide");
    }
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
          url: "ajax/courier/add_address_recipients_ajax.php?recipient=" +recipient_id,
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
    $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
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

function cdp_validateTrackNumber(value, trackDigits) {
  cdp_convertStrPad(value, trackDigits);

  $.ajax({
    type: "POST",
    dataType: "json",
    url: "./ajax/validate_track_number_consolidate.php?track=" + value,
    success: function (data) {
      var main = $("#order_no_main").val();

      if (data) {
        alert(message_error_exist_tracking);
        $("#order_no").val(main);
      }
    },
  });
}

function cdp_convertStrPad(value, dbDigits) {
  var pad = value.padStart(dbDigits, "0");

  $("#order_no").val(pad);
}

var input = document.getElementById("order_no");

input.addEventListener("keypress", function (event) {
  if (event.charCode < 48 || event.charCode > 57) {
    event.preventDefault();
  }
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

var input_recipient = document.querySelector("#phone_custom_recipient");
var iti_recipient = window.intlTelInput(input_recipient, {
  geoIpLookup: function (callback) {
    $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
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
