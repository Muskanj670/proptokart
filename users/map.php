<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<!-- <body> -->
<!-- <main>  -->
<div id="map"></div>
<!-- </main> -->

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script>
    // Function to get coordinates from Nominatim
    function getCoordinates(address, callback) {
        var url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var lat = data[0].lat;
                    var lon = data[0].lon;
                    callback([lat, lon]);
                } else {
                    alert('Address not found');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching address');
            });
    }

    // Initialize the map
    var map = L.map('map').setView([27.2046, 77.4977], 13); // Center on Agra, for example

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // PHP variables to JavaScript
    var destinationAddress = "<?php
                                echo $address . ', ' . $postal_code . ', ' . $city . ', ' . $state;
                                ?>";
    //   var destinationAddress = "Amar Hotel, 282001, Agra, Uttar Pradesh";

    // Get the destination coordinates and add to map
    getCoordinates(destinationAddress, function(destinationLatLng) {
        var destinationMarker = L.marker(destinationLatLng).addTo(map)
            .bindPopup('Destination')
            .openPopup();

        destinationMarker.on('click', function() {
            var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${destinationLatLng[0]},${destinationLatLng[1]}`;
            window.open(googleMapsUrl, '_blank');
        });

        var routingControl;

        // Function to handle Geolocation success
        function onLocationFound(e) {
            var currentLocation = e.latlng;

            // Add a marker for the current location
            var currentLocationMarker = L.marker(currentLocation).addTo(map)
                .bindPopup('Your Location')
                .openPopup();

            currentLocationMarker.on('click', function() {
                var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${currentLocation.lat},${currentLocation.lng}&destination=${destinationLatLng[0]},${destinationLatLng[1]}`;
                window.open(googleMapsUrl, '_blank');
            });

            // Fit the map bounds to show both markers
            var bounds = L.latLngBounds([currentLocation, destinationLatLng]);
            map.fitBounds(bounds);

            if (routingControl) {
                map.removeControl(routingControl);
            }

            // Add routing control
            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(currentLocation.lat, currentLocation.lng),
                    L.latLng(destinationLatLng[0], destinationLatLng[1])
                ],
                createMarker: function(i, waypoint, n) {
                    if (i === 0) {
                        return L.marker(waypoint.latLng).bindPopup('Your Location').openPopup();
                    } else if (i === n - 1) {
                        return L.marker(waypoint.latLng).bindPopup('Destination').openPopup();
                    }
                },
                routeWhileDragging: true,
                showAlternatives: true,
                lineOptions: {
                    styles: [{
                        color: 'blue',
                        opacity: 0.6,
                        weight: 4
                    }]
                }
            }).addTo(map);

            // Add click event to the map to open Google Maps with destination set
            map.on('click', function(ev) {
                var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${destinationLatLng[0]},${destinationLatLng[1]}`;
                window.open(googleMapsUrl, '_blank');
            });
        }

        // Function to handle Geolocation error
        function onLocationError(e) {
            alert(e.message);
        }

        // Request the user's current location
        map.locate({
            setView: true,
            maxZoom: 16
        });

        // Event listeners for location found and error
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
    });
</script>
<!-- </body> -->

</html>