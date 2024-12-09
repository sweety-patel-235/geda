var map;
var marker;
var latitude    =  $('#latitude').val();
var longitude   =  $('#longitude').val();
if((latitude == "" || latitude == 0) && (longitude == "" || longitude == 0)){   
    latitude = "23.0221";   longitude = "72.5721";
}
var myLatlng    = new google.maps.LatLng(latitude,longitude);
var geocoder    = new google.maps.Geocoder();
var infowindow  = new google.maps.InfoWindow();

function initialize()
{
    var markers = [];
    var mapOptions = {
        zoom: 12,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        streetViewControl: false,
    };
   
    map = new google.maps.Map(document.getElementById("myMap"), mapOptions);
    marker = new google.maps.Marker({
        map: map,
        position: myLatlng,
        draggable: true,
    }); 

    // Create the search box and link it to the UI element.
    var input = /** @type {HTMLInputElement} */(
        document.getElementById('pac-input'));
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var searchBox = new google.maps.places.SearchBox(
        /** @type {HTMLInputElement} */(input));

    // For search location using lat long
    google.maps.event.addListener(searchBox, 'places_changed', function() {
        geocodeLatLng(geocoder, map, infowindow);
    }); 
    
    // [START region_getplaces]
    // Listen for the event fired when the user selects an item from the
    // pick list. Retrieve the matching places for that item.
    google.maps.event.addListener(searchBox, 'places_changed', function()
    {
        var places = searchBox.getPlaces();
        if (places.length == 0) {
            return;
        }
        for (var i = 0, marker; marker = markers[i]; i++) {
            marker.setMap(null);
        }
        // For each place, get the icon, place name, and location.
        markers = [];
        var bounds = new google.maps.LatLngBounds();

        for (var i = 0, place; place = places[i]; i++) {
            var image = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };
            // marker.setPosition(place.geometry.location);
            changeMarkerPosition(place.geometry.location);
            bounds.extend(place.geometry.location);
        }
        map.fitBounds(bounds);
    });
    
    function geocodeLatLng(geocoder, map, infowindow) { 

        var input = document.getElementById('pac-input').value;
        var latlngStr = input.split(',', 2);
        var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
        geocoder.geocode({'location': latlng}, function(results, status) {
            console.log(results);
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) { 
                    map.setZoom(11);
                    marker.setPosition(latlng);
                    $('#latitude').val(latlng.lat);
                    $('#longitude').val(latlng.lng);
                    fillLandmark();
                } else {
                    window.alert('No location found');
                }
            } else {
                //window.alert('Geocoder failed due to: ' + status);
            }
        });
    }

    function changeMarkerPosition(latlng) {
        marker.setPosition(latlng);
        $('#latitude').val(latlng.lat());
        $('#longitude').val(latlng.lng());
        fillLandmark();
    }
    
    google.maps.event.addListener(map, 'bounds_changed', function() {
        var bounds = map.getBounds();
        searchBox.setBounds(bounds);
    });
    
    geocoder.geocode({'latLng': myLatlng }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                $('#latitude').val(marker.getPosition().lat());
                $('#longitude').val(marker.getPosition().lng());
                infowindow.setContent(results[0].formatted_address);
                fillLandmark();
            }
        }
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#latitude').val(marker.getPosition().lat());
                    $('#longitude').val(marker.getPosition().lng());
                    infowindow.setContent(results[0].formatted_address);
                    fillLandmark();
                }
            }
        });
    });
    getLocation();
}
    
function fillLandmark()
{
    var latlng = new google.maps.LatLng(marker.getPosition().lat(),marker.getPosition().lng());
    var geocoder = geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                $('#landmark').val(results[1].formatted_address);
                $('.landmark').val(results[1].formatted_address);

            }
        }
    });
}

/* Set Marker Postion basend on Current Location. */
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    var latlng = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
    marker.setPosition(latlng);
    
    var geocoder = geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                $('#landmark').val(results[1].formatted_address);
                $('.landmark').val(results[1].formatted_address);
            }
        }
    });
}
google.maps.event.addDomListener(window, 'load', initialize);