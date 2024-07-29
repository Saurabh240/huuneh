
function loadRecipientStates(selectedCountryId, stateInput, cityInput, modelId)
{
      // Select state
      if (stateInput) {
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
              return state.text == stateInput;
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
            loadRecipientCities(selectedState.id, cityInput, modelId); // Assuming cdp_load_cities(modal) function exists
          }
        });
      }
    
}

function loadRecipientCities(selectedStateId, cityInput, modelId)
{

      // Select city
      if (cityInput) {
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
              return city.text == cityInput;
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

function loadRecipientCountries(fullAddress, modelId)
{
  if (!fullAddress) return;

    var countryInput = fullAddress.country;
    var stateInput = fullAddress.state;
    var cityInput = fullAddress.city;
    var selectedZip = fullAddress.zip_code;
    var $countrySelect;
    modelId ? $("#postal_modal_recipient" + modelId).val(selectedZip) : $("#postal_modal_recipient").val(selectedZip);
    // Select country
    if (countryInput) {
      $countrySelect = modelId ? $countrySelect = $("#country_modal_recipient" + modelId) : $("#country_modal_recipient");
      $.ajax({
        url: "ajax/select2_countries.php", // Your data source URL for countries
        dataType: "json",
        success: function (countriesData) {
          var selectedCountry = countriesData.find(function (country) {
            return country.text == countryInput;
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
          loadRecipientStates(selectedCountry.id, stateInput, cityInput, modelId); 
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
    url: 'ajax/courier/address_details_api.php',
    data: { 'address_modal': recipientAddress },
    dataType: 'json',
    success: function (response) {
      if(response.status){
        var fullAddress = response.fullAddress;
        loadRecipientCountries(fullAddress, modelId);
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


// For pickup location autocomplete.

function loadSenderCities(selectedStateId, cityInput, modelId)
{

      // Select city
      if (cityInput) {
        var $citySelect = modelId ? $("#city_modal_user" + modelId) : $("#city_modal_user");
        $.ajax({
          url: "ajax/select2_cities.php?id=" + selectedStateId, // Your data source URL for cities
          dataType: "json",
          data: {
            state_id: selectedStateId
          },
          success: function (citiesData) {
            // Find the selected city's text
            var selectedCity = citiesData.find(function (city) {
              return city.text == cityInput;
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

function loadSenderStates(selectedCountryId, stateInput, cityInput, modelId)
{
      // Select state
      if (stateInput) {
        var $stateSelect = modelId ? $("#state_modal_user" + modelId) : $("#state_modal_user");
        $.ajax({
          url: "ajax/select2_states.php?id=" + selectedCountryId, // Your data source URL for states
          dataType: "json",
          data: {
            country_id: selectedCountryId
          },
          success: function (statesData) {
            // Find the selected state's text
            var selectedState = statesData.find(function (state) {
              return state.text == stateInput;
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
            loadSenderCities(selectedState.id, cityInput, modelId); // Assuming cdp_load_cities(modal) function exists
          }
        });
      }
    
}

function loadSenderCountries(fullAddress, modelId)
{
  if (!fullAddress) return;

    var countryInput = fullAddress.country;
    var stateInput = fullAddress.state;
    var cityInput = fullAddress.city;
    var selectedZip = fullAddress.zip_code;
    var $countrySelect;
    modelId ? $("#postal_modal_user" + modelId).val(selectedZip) : $("#postal_modal_user").val(selectedZip);
    // Select country
    if (countryInput) {
      $countrySelect = modelId ? $countrySelect = $("#country_modal_user" + modelId) : $("#country_modal_user");
      $.ajax({
        url: "ajax/select2_countries.php", // Your data source URL for countries
        dataType: "json",
        success: function (countriesData) {
          var selectedCountry = countriesData.find(function (country) {
            return country.text == countryInput;
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
          loadSenderStates(selectedCountry.id, stateInput, cityInput, modelId); 
        }
      });
    }
    

}



function getSenderFullAddress(inputAddress, modelId)
{
  var userAddress = $("#" + inputAddress).val();
  console.log(userAddress);

  $.ajax({
    type: 'POST',
    url: 'ajax/courier/address_details_api.php',
    data: { 'address_modal': userAddress },
    dataType: 'json',
    success: function (response) {
      if(response.status){
        var fullAddress = response.fullAddress;
        loadSenderCountries(fullAddress, modelId);
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



$("#address_modal_user").on("change", function(){
    getSenderFullAddress("address_modal_user", "");
});
  
  
$("#address_modal_user_address").on("change", function(){
    getSenderFullAddress("address_modal_user_address", "_address");
});