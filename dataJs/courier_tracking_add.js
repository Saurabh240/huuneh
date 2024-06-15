"use strict";

$(function () {
  $("#t_date").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
  });
});

$("#invoice_form").on("submit", function (event) {
  var data = $(this).serialize();
  $.ajax({
    type: "POST",
    url: "ajax/courier/add_courier_tracking.php",
    data: data,
    dataType: "json",
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
        cdp_showSuccess(data.messages);
      } else {
        cdp_showError(data.errors);
      }
    },
  });

  event.preventDefault();
});

function cdp_showError(errors) {
  var html_code = "";
  html_code += '<ul class="error" > ';

  for (var error in errors) {
    html_code += '<li class="text-left">';
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

function cdp_showSuccess(messages) {
  Swal.fire({
    title: messages,
    icon: "success",
    allowOutsideClick: false,
    confirmButtonText: "Ok",
  }).then((result) => {
    if (result.isConfirmed) {
      $("#invoice_form").trigger("reset");
    }
  });
}
