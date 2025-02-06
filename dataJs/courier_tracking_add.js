"use strict";

$(function () {
  $("#t_date").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
  });
});

function cdp_preview_images() {
  $("#image_preview").html("");
  var flag=0;
  var total_file = document.getElementById("filesMultiple").files.length;

  for (var i = 0; i < total_file; i++) {
	var filetype = event.target.files[i].type;
	if (filetype == 'image/jpeg' || filetype == 'image/jpg'  || filetype == 'image/png' || filetype == 'image/gif') {		
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
	}else{
		flag=1;
	
	}
  }
  if(flag==1){
	  	
	Swal.fire({
		  type: 'warning',
		  title: 'opps..',
		  text: 'Only jpeg, jpg, png, gif image format allows.',
		  icon: 'warning',
		  confirmButtonColor: '#336aea'
		});
		
	
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
    var filetype = file[i].type;
	if (filetype == 'image/jpeg' || filetype == 'image/jpg'  || filetype == 'image/png' || filetype == 'image/gif') {
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


$('#status_courier').on('change', function() {
      const selectedText = $('option:selected', this).text();
      if (selectedText === 'Delivered with Pending Return') {
          $('#div_image').show();
      } else {
          $('#div_image').hide();
      }
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
	   var filetype = file[i].type;
	if (filetype == 'image/jpeg' || filetype == 'image/jpg'  || filetype == 'image/png' || filetype == 'image/gif') {
		contador++;
	}
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


$("#invoice_form").on("submit", function (event) {
	 var data = new FormData();
  //var data = $(this).serialize();
  var status_courier = $("#status_courier").val();
  var deleted_file_ids = $("#deleted_file_ids").val();
  var shipment_id = $("#shipment_id").val();
  var t_date = $("#t_date").val();
  var msg = $("#message-text").val();
 var notify_whatsapp_sender = $(
    "input:checkbox[name=notify_whatsapp_sender]:checked"
  ).val();

  var notify_whatsapp_receiver = $(
    "input:checkbox[name=notify_whatsapp_receiver]:checked"
  ).val();
  
  if (status_courier) {
    data.append("status_courier", status_courier);
  }
  if (t_date) {
    data.append("t_date", t_date);
  }
  if (msg) {
    data.append("comments", msg);
  }
  if (notify_whatsapp_sender) {
    data.append("notify_whatsapp_sender", notify_whatsapp_sender);
  }
  if (notify_whatsapp_receiver) {
    data.append("notify_whatsapp_receiver", notify_whatsapp_receiver);
  } 
  if (shipment_id) {
    data.append("shipment_id", shipment_id);
  }
   if (deleted_file_ids) {
    data.append("deleted_file_ids", deleted_file_ids);
  }
   var total_file = document.getElementById("filesMultiple").files.length;
   
  for (var i = 0; i < total_file; i++) {
    data.append(
      "filesMultiple[]",
      document.getElementById("filesMultiple").files[i]
    );
  }
 
  
  $.ajax({
    type: "POST",
    url: "ajax/courier/add_courier_tracking.php",
	contentType: false,
    cache: false,
    processData: false,
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
