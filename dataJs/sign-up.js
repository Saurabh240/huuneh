"use strict";

$(function () {
  cdp_load_countries();
  cdp_load_states();
  cdp_load_cities();
});


var autocomplete;
var address_field;
var country_field;
var country_field_label;



function initAutocomplete() {

  address_field = document.querySelector("#address");
  //var country_array = ["AFG","ALB","DZA","AND","AGO","ATG","ARG","ARM","AUS","AUT","AZE","BHS","BHR","BGD","BRB","BLR","BEL","BLZ","BEN","BMU","BTN","BOL","BIH","BWA","BRA","BRN","BGR","BFA","BDI","KHM","CMR","CAN","CPV","CAF","TCD","CHL","CHN","COL","COM","COG","COD","CRI","CIV","HRV","CUB","CYP","CZE","DNK","DJI","DMA","DOM","TLS","ECU","EGY","SLV","GNQ","ERI","EST","ETH","FJI","FIN","FRA","GAB","GMB","GEO","DEU","GHA","GRC","GRD","GTM","GIN","GNB","GUY","HTI","HND","HKG","HUN","ISL","IND","IDN","IRN","IRQ","IRL","ISR","ITA","JAM","JPN","JOR","KAZ","KEN","KIR","PRK","KOR","KWT","KGZ","LAO","LVA","LBN","LSO","LBR","LBY","LIE","LTU","LUX","MKD","MDG","MWI","MYS","MDV","MLI","MLT","MHL","MRT","MUS","MEX","FSM","MDA","MCO","MNG","MNE","MAR","MOZ","MMR","NAM","NRU","NPL","BES","NLD","NZL","NIC","NER","NGA","NOR","OMN","PAK","PLW","PAN","PNG","PRY","PER","PHL","POL","PRT","PRI","QAT","ROU","RUS","RWA","KNA","LCA","VCT","WSM","SMR","STP","SAU","SEN","SRB","SYC","SLE","SGP","SVK","SVN","SLB","SOM","ZAF","SSD","ESP","LKA","SDN","SUR","SWZ","SWE","CHE","SYR","TWN","TJK","TZA","THA","TGO","TON","TTO","TUN","TUR","TKM","TUV","UGA","UKR","ARE","GBR","USA","URY","UZB","VUT","VEN","VNM","VIR","YEM","ZMB","ZWE","XKX","test","HA","ISM","MAR","YU","YU"];
  
   
  // Create the autocomplete object, restricting the search predictions to
  // addresses in the US and Canada.
  autocomplete = new google.maps.places.Autocomplete(address_field, {
   // componentRestrictions: { country: new_country_array },
    fields: ["address_components", "geometry"],
    types: ["address"],
    strictBounds: false,
  });
  address_field.focus();
  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", fillInAddress);

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

function cdp_load_countries() {
  $("#country")
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
      var country = $("#country").val();
      $("#state").attr("disabled", true);
      $("#state").val(null);
      if (country != null) {
        $("#state").attr("disabled", false);
      }
      cdp_load_states();
    });
}

function cdp_load_states() {
  var country = $("#country").val();

  $("#state")
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
      var state = $("#state").val();

      $("#city").attr("disabled", true);
      $("#city").val(null);

      if (state != null) {
        $("#city").attr("disabled", false);
      }

      cdp_load_cities();
    });
}

function cdp_load_cities() {
  var state = $("#state").val();

  $("#city").select2({
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

function cdp_showError(errors) {
  Swal.fire({
    title: message_error,
    text: errors,
    icon: "error",
    allowOutsideClick: false,
    confirmButtonText: "Ok",
    confirmButtonColor: '#f27474',
  });
}

function cdp_showSuccess(messages) {
  Swal.fire({
    title: messages,
    icon: "success",
    allowOutsideClick: false,
    confirmButtonText: "Ok",
    confirmButtonColor: '#336aea',
    type: "success",
  }).then((okay) => {
    if (okay) {
      window.location.href = "index.php";
    }
  });
}

var errorMsg = document.querySelector("#error-msg");
var validMsg = document.querySelector("#valid-msg");

// here, the index maps to the error code returned from getValidationError - see readme
var errorMap = [
  "Invalid number",
  "Invalid country code",
  "Too short",
  "Too long",
  "Invalid number",
];

var input = document.querySelector("#phone_custom");
var iti = window.intlTelInput(input, {
  geoIpLookup: function (callback) {
    $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
      var countryCode = resp && resp.country ? resp.country : "";
      callback(countryCode);
    });
  },
  onlyCountries: ["ca"], // This restricts the country list to Canada
  initialCountry: "ca", // Sets the default country to Canada
  nationalMode: true,
  separateDialCode: true,
  utilsScript: "assets/template/assets/libs/intlTelInput/utils.js",
});

var reset = function () {
  input.classList.remove("is-invalid");
  errorMsg.innerHTML = "";
  errorMsg.classList.add("hide");
  validMsg.classList.add("hide");
};

// on blur: validate
input.addEventListener("blur", function () {
  reset();
  if (input.value.trim()) {
    if (iti.isValidNumber()) {
      $("#phone").val(iti.getNumber());
      validMsg.classList.remove("hide");
    } else {
      input.classList.add("is-invalid");
      var errorCode = iti.getValidationError();
      errorMsg.innerHTML = errorMap[errorCode];
      errorMsg.classList.remove("hide");
    }
  }
});

// on keyup / change flag: reset
input.addEventListener("change", reset);
input.addEventListener("keyup", reset);


// Validación de fortaleza de la contraseña en tiempo real
  $("#pass").on("input", function () {
    var password = $(this).val();
    var strength = calculatePasswordStrength(password);
    var meter = $("#password-strength-meter");

    var text = "";
    var colorClass = "";
    if (password.length < 9) {
      text = message_error_form94;
      colorClass = "weak";
    } else if (strength < 20) {
      text = message_error_form95;
      colorClass = "weaks";
    } else if (strength < 40) {
      text = message_error_form96;
      colorClass = "medium";
    } else if (strength < 60) {
      text = message_error_form97;
      colorClass = "good";
    } else if (strength < 80) {
      text = message_error_form98;
      colorClass = "strong";
    } else {
      text = message_error_form99;
      colorClass = "very-strong";
    }

    meter.removeClass().addClass(colorClass).text(text + " (" + password.length + " "+ message_error_form100 +")");
  });

  // Validación de coincidencia de contraseñas
  $("#pass2").on("input", function () {
    var password = $("#pass").val();
    var password2 = $(this).val();
    if (password !== password2) {
      $("#passwordMatch").text(message_error_form101);
    } else {
      $("#passwordMatch").text("");
    }
  });

  // Función para calcular la fortaleza de la contraseña
  function calculatePasswordStrength(password) {
    var score = 0;
    if (password.length > 7) {
      score++;
    }
    if ((/[a-z]/.test(password)) && (/[A-Z]/.test(password))) {
      score++;
    }
    if (/[0-9]/.test(password)) {
      score++;
    }
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
      score++;
    }
    return score * 20;
  }
  
  

$("#new_register").on("submit", function (event) {


  // Validación de la contraseña
  var password = $("#pass").val();
  var strength = calculatePasswordStrength(password);
  if (strength < 40) {
    Swal.fire({
      title: message_error_form102,
      text: message_error_form103,
      icon: 'error',
      allowOutsideClick: false,
      confirmButtonColor: "#f27474",
      confirmButtonText: "Ok",
    });
    event.preventDefault(); // Detener el envío del formulario si la contraseña es débil
    return;
  }

  // Resto del código para enviar el formulario mediante AJAX
  if (iti.isValidNumber()) {
    var parametros = $(this).serialize();
    $.ajax({
      type: "POST",
      url: "./ajax/sign_up_ajax.php",
      data: parametros,
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
  } else {
    input.classList.add("is-invalid");
    var errorCode = iti.getValidationError();
    errorMsg.innerHTML = errorMap[errorCode];
    errorMsg.classList.remove("hide");
    $("#phone_custom").focus();
  }
  event.preventDefault();
});

