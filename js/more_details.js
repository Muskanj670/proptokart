let items = document.querySelectorAll('.gallery .list .item');
let next = document.getElementById('next');
let prev = document.getElementById('prev');
let thumbnails = document.querySelectorAll('.thumbnail .item');

// config param
let countItem = items.length;
let itemActive = 0;
// event next click
next.onclick = function () {
    itemActive = itemActive + 1;
    if (itemActive >= countItem) {
        itemActive = 0;
    }
    showgallery();
}
//event prev click
prev.onclick = function () {
    itemActive = itemActive - 1;
    if (itemActive < 0) {
        itemActive = countItem - 1;
    }
    showgallery();
}
// gallery
let refreshInterval = setInterval(() => {
    next.click();
}, 5000)
function showgallery() {
    // remove item active old
    let itemActiveOld = document.querySelector('.gallery .list .item.active');
    let thumbnailActiveOld = document.querySelector('.thumbnail .item.active');
    itemActiveOld.classList.remove('active');
    thumbnailActiveOld.classList.remove('active');

    // active new item
    items[itemActive].classList.add('active');
    thumbnails[itemActive].classList.add('active');

    // clear auto time run gallery
    clearInterval(refreshInterval);
    refreshInterval = setInterval(() => {
        next.click();
    }, 5000)
}

// thumbnail
thumbnails.forEach((thumbnail, index) => {
    thumbnail.addEventListener('click', () => {
        itemActive = index;
        showgallery();
    })
})

document.getElementById("share-btn").addEventListener("click", function () {
    var shareIcon = document.querySelector(".share-icon");
    if (shareIcon.style.display === "none") {
        shareIcon.style.display = "block";
    } else {
        shareIcon.style.display = "none";
    }
});

// --------------------current url-----------------------
// Function to share on social media platforms
function share(platform, id) {
    // Get the current URL
    var currentURL = window.location.href;

    // Manipulate the URL
    var newURL = currentURL.replace("example", "newexample");

    // Log the modified URL to the console
    console.log("Modified URL: " + newURL);

    var shareURL;
    switch (platform) {
        case 'facebook':
            shareURL = "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(newURL);
            break;
        case 'twitter':
            shareURL = "https://twitter.com/intent/tweet?text=Northwest%20Office%20Space&amp;url=" + encodeURIComponent(newURL);
            break;
        case 'linkedin':
            shareURL = "https://www.linkedin.com/shareArticle?url=" + encodeURIComponent(newURL);
            break;
        case 'pinterest':
            shareURL = "https://pinterest.com/pin/create/button/?url=" + encodeURIComponent(newURL);
            break;
        case 'whatsapp':
            shareURL = "https://wa.me/?text=" + encodeURIComponent(newURL);
            break;
        default:
            console.error('Invalid platform specified');
            return;
    }

    // Open share dialog with the generated URL
    window.open(shareURL, "_blank");
}

// Function to copy URL to clipboard
function copyToClipboard(text) {
    var textArea = document.createElement('textarea');
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    alert('Link copied to clipboard');
}

// Function to get current page URL
function getCurrentURL() {
    return window.location.href;
}

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

// Get the destination address
var destinationAddress = "Amar Hotel, 282001, Agra, Uttar Pradesh"; // Replace with your destination address

// Get the destination coordinates and add to map
getCoordinates(destinationAddress, function (destinationLatLng) {
    var destinationMarker = L.marker(destinationLatLng).addTo(map)
        .bindPopup('Destination')
        .openPopup();

    destinationMarker.on('click', function () {
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

        currentLocationMarker.on('click', function () {
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
            createMarker: function (i, waypoint, n) {
                if (i === 0) {
                    return L.marker(waypoint.latLng).bindPopup('Your Location').openPopup();
                } else if (i === n - 1) {
                    return L.marker(waypoint.latLng).bindPopup('Destination').openPopup();
                }
            },
            routeWhileDragging: true,
            showAlternatives: true,
            lineOptions: {
                styles: [{ color: 'blue', opacity: 0.6, weight: 4 }]
            }
        }).addTo(map);

        // Add click event to the map to open Google Maps with destination set
        map.on('click', function (ev) {
            var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${destinationLatLng[0]},${destinationLatLng[1]}`;
            window.open(googleMapsUrl, '_blank');
        });
    }

    // Function to handle Geolocation error
    function onLocationError(e) {
        alert(e.message);
    }

    // Request the user's current location
    map.locate({ setView: true, maxZoom: 16 });

    // Event listeners for location found and error
    map.on('locationfound', onLocationFound);
    map.on('locationerror', onLocationError);
});


//   // Function to copy URL to clipboard
//   function copyToClipboard(text) {
//     var textArea = document.createElement('textarea');
//     textArea.value = text;
//     document.body.appendChild(textArea);
//     textArea.select();
//     document.execCommand('copy');
//     document.body.removeChild(textArea);
//     alert('Link copied to clipboard');
// }

// Function to get current page URL
// function getCurrentURL() {
//     return window.location.href;
// }
