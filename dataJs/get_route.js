"use strict";


$(function () {
    cdp_load();
});


//Route datos AJAX
function cdp_load() {
	var id = $("#route_id").val();
    var parametros = { "route_id": id};
    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/pickup/get_route_ajax.php',
        data: parametros,
        beforeSend: function (objeto) {
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
			 $.ajax({
				url: './ajax/get_route_address_ajax.php',
				data: parametros,
				beforeSend: function (obje) {
				},
				success: function (data_address) {
					var addresses = JSON.parse(data_address);
					initMap(addresses);
				}
			})
			
        }
    })
}

function initMap(addresses) {
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 6,
                center: { lat: 56.1304, lng: 106.3468 } // Center of Canada
            });

            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);
            calculateAndDisplayRoute(directionsService, directionsRenderer, addresses);
}

function calculateAndDisplayRoute(directionsService, directionsRenderer, addresses) {
            if (addresses.length < 2) {
                alert("At least two addresses are required.");
                return;
            }

            const waypoints = addresses.slice(1, -1).map(address => ({ location: address, stopover: true }));

            directionsService.route({
                origin: addresses[0], // First address as start
                destination: addresses[addresses.length - 1], // Last address as end
                waypoints: waypoints, // Middle addresses as waypoints
                travelMode: google.maps.TravelMode.DRIVING
            }, (response, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(response);
                } else {
                    alert("Directions request failed due to " + status);
                }
            });
}
 



