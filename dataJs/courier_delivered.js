"use strict";

(function () {
  window.requestAnimFrame = (function (callback) {
    return (
      window.requestAnimationFrame ||
      window.webkitRequestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      window.oRequestAnimationFrame ||
      window.msRequestAnimaitonFrame ||
      function (callback) {
        window.setTimeout(callback, 1000 / 60);
      }
    );
  })();

  var canvas = document.getElementById("sig-canvas");
  var ctx = canvas.getContext("2d");
  ctx.strokeStyle = "#222222";
  ctx.lineWidth = 4;

  var drawing = false;
  var mousePos = {
    x: 0,
    y: 0,
  };
  var lastPos = mousePos;

  canvas.addEventListener(
    "mousedown",
    function (e) {
      drawing = true;
      lastPos = getMousePos(canvas, e);
    },
    false
  );

  canvas.addEventListener(
    "mouseup",
    function (e) {
      drawing = false;
    },
    false
  );

  canvas.addEventListener(
    "mousemove",
    function (e) {
      mousePos = getMousePos(canvas, e);
    },
    false
  );

  // Add touch event support for mobile
  canvas.addEventListener("touchstart", function (e) {}, true);

  canvas.addEventListener(
    "touchmove",
    function (e) {
      var touch = e.touches[0];
      var me = new MouseEvent("mousemove", {
        clientX: touch.clientX,
        clientY: touch.clientY,
      });
      canvas.dispatchEvent(me);
    },
    false
  );

  canvas.addEventListener(
    "touchstart",
    function (e) {
      mousePos = getTouchPos(canvas, e);
      var touch = e.touches[0];
      var me = new MouseEvent("mousedown", {
        clientX: touch.clientX,
        clientY: touch.clientY,
      });
      canvas.dispatchEvent(me);
    },
    false
  );

  canvas.addEventListener(
    "touchend",
    function (e) {
      var me = new MouseEvent("mouseup", {});
      canvas.dispatchEvent(me);
    },
    false
  );

  function getMousePos(canvasDom, mouseEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
      x: mouseEvent.clientX - rect.left,
      y: mouseEvent.clientY - rect.top,
    };
  }

  function getTouchPos(canvasDom, touchEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
      x: touchEvent.touches[0].clientX - rect.left,
      y: touchEvent.touches[0].clientY - rect.top,
    };
  }

  function renderCanvas() {
    if (drawing) {
      ctx.moveTo(lastPos.x, lastPos.y);
      ctx.lineTo(mousePos.x, mousePos.y);
      ctx.stroke();
      lastPos = mousePos;
    }
  }

  // Prevent scrolling when touching the canvas
  document.body.addEventListener(
    "touchstart",
    function (e) {
      if (e.target == canvas) {
        e.preventDefault();
      }
    },
    false
  );
  document.body.addEventListener(
    "touchend",
    function (e) {
      if (e.target == canvas) {
        e.preventDefault();
      }
    },
    false
  );
  document.body.addEventListener(
    "touchmove",
    function (e) {
      if (e.target == canvas) {
        e.preventDefault();
      }
    },
    false
  );

  (function drawLoop() {
    requestAnimFrame(drawLoop);
    renderCanvas();
  })();

  function clearCanvas() {
    canvas.width = canvas.width;
  }

  // Set up the UI
  var sigText = document.getElementById("sig-dataUrl");
  var sigImage = document.getElementById("sig-image");
  var clearBtn = document.getElementById("sig-clearBtn");
  var submitBtn = document.getElementById("sig-submitBtn");

  clearBtn.addEventListener(
    "click",
    function (e) {
      clearCanvas();
      sigText.innerHTML = "controller.php";
      sigImage.setAttribute("src", "");
    },
    false
  );
  submitBtn.addEventListener(
    "click",
    function (e) {
      var dataUrl = canvas.toDataURL();
      sigText.innerHTML = dataUrl;
      sigImage.setAttribute("src", dataUrl);
    },
    false
  );
})();

function mandarFirma() {
  document.getElementById("invoice_form").submit();
}

$(function () {
  $("#t_date").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
  });
});

$("#invoice_form").on("submit", function (event) {
  var shipment_id = $("#shipment_id").val();
  var deliver_date = $("#deliver_date").val();
  var driver_id = $("#driver_id").val();
  var person_receives = $("#person_receives").val();
  var miarchivo = $("#miarchivo").val();

  var notify_whatsapp_sender = $(
    "input:checkbox[name=notify_whatsapp_sender]:checked"
  ).val();

  var notify_whatsapp_receiver = $(
    "input:checkbox[name=notify_whatsapp_receiver]:checked"
  ).val();

  var sigDataUrl = $("#sig-dataUrl").val();

  var miarchivo = document.getElementById("miarchivo");
  var miarchivo_final = miarchivo.files[0];

  var data = new FormData();

  if (shipment_id) {
    data.append("shipment_id", shipment_id);
  }

  if (deliver_date) {
    data.append("deliver_date", deliver_date);
  }

  if (driver_id) {
    data.append("driver_id", driver_id);
  }

  if (person_receives) {
    data.append("person_receives", person_receives);
  }

  if (notify_whatsapp_sender) {
    data.append("notify_whatsapp_sender", notify_whatsapp_sender);
  }

  if (notify_whatsapp_receiver) {
    data.append("notify_whatsapp_receiver", notify_whatsapp_receiver);
  }

  if (miarchivo_final) {
    data.append("miarchivo", miarchivo_final);
  }

  if (sigDataUrl) {
    data.append("sig-dataUrl", sigDataUrl);
  }

  $.ajax({
    type: "POST",
    url: "ajax/courier/add_courier_delivered_ajax.php",
    contentType: false,
    cache: false,
    processData: false,
    data: data,
    dataType: "json",
    beforeSend: function (objeto) {
      Swal.fire({
        title: message_loading,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
    },
    success: function (data) {
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
