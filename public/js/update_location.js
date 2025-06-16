// Google map
let longitude = Number($("#city_id").find(":selected").attr("data-longitude"));
let latitude = Number($("#city_id").find(":selected").attr("data-latitude"));
let district = document.getElementById("district");
let isSet = $("#city_id").find(":selected").attr("data-isSet");
let cityCircle = "";
let searchInput = document.getElementById("pac-input");
let searchBtn = document.getElementById("search-button");
let Location_URL = document.getElementById("Location_URL");

let searchBo = " ";
mapLoad();

console.log(isSet);

$("#city_id").change(function () {
    longitude = Number($("#city_id").find(":selected").attr("data-longitude-city"));
    latitude = Number($("#city_id").find(":selected").attr("data-latitude-city"));
    document.getElementById("longitude").value = "";
    document.getElementById("district").value = "";
    document.getElementById("latitude").value = "";
    document.getElementById("location_radius").value = "";
    document.getElementById("Location_URL").value = "";
    searchInput.value = "";
    mapLoad();
});

function mapLoad() {
    $(document).ready(function () {
        var location = new google.maps.LatLng(latitude, longitude);
        var mapProperty = {
            center: location,
            zoom: 15,
            mapTypeControl: false,
        };

        var map = new google.maps.Map(
            document.getElementById("map"),
            mapProperty
        );

        var location_radius = document.getElementById("location_radius").value;

        cityCircle = new google.maps.Circle({
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35,
            map,
            center: {
                lat: Number(document.getElementById("latitude").value),
                lng: Number(document.getElementById("longitude").value),
            },
            radius: location_radius * 1,
        });
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(
                Number(document.getElementById("latitude").value),
                Number(document.getElementById("longitude").value)
            ),
            map: map,
        });


        function getDistrict(latitude, longitude) {
            var geocoder = new google.maps.Geocoder();
            var latlng = {
                lat: latitude,
                lng: longitude
            };

            geocoder.geocode({
                location: latlng
            }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var addressComponents = results[0].address_components;
                        var city, district, street, streetNumber;

                        for (var i = 0; i < addressComponents.length; i++) {
                            var addressType = addressComponents[i].types[0];

                            if (addressType === "locality") {
                                city = addressComponents[i].long_name;
                            } else if (addressType === "administrative_area_level_2") {
                                district = addressComponents[i].long_name;
                            } else if (addressType === "route") {
                                street = addressComponents[i].long_name;
                            } else if (addressType === "street_number") {
                                streetNumber = addressComponents[i].long_name;
                            }
                        }

                        var response = "";
                        if (city) {
                            response +=  city + ", ";
                        }
                        if (district) {
                            response += district + ", ";
                        }
                        if (street) {
                            response += street + ", ";
                        }
                        if (streetNumber) {
                            response +=  streetNumber;
                        }

                        document.getElementById("district").value = response;
                    }
                } else {
                    console.log("Geocoder failed due to: " + status);
                }
            });
        }

        document.getElementById("longitude").onchange = function () {
            var longitude = document.getElementById("longitude").value;
            console.log(longitude);
            cityCircle.setCenter({
                lat: Number(document.getElementById("latitude").value),
                lng: Number(document.getElementById("longitude").value),
            });
            marker.setPosition(
                new google.maps.LatLng(
                    Number(document.getElementById("latitude").value),
                    Number(document.getElementById("longitude").value)
                )
            );
            Location_URL.value = `https://www.google.com/maps/search/?api=1&query=${Number(
                document.getElementById("latitude").value
            )}%2C${Number(document.getElementById("longitude").value)}`;

            getDistrict(Number(document.getElementById("latitude").value), Number(document.getElementById("longitude").value));
        };

        document.getElementById("latitude").onchange = function () {
            var latitude = document.getElementById("latitude").value;
            cityCircle.setCenter({
                lat: Number(document.getElementById("latitude").value),
                lng: Number(document.getElementById("longitude").value),
            });
            marker.setPosition(
                new google.maps.LatLng(
                    Number(document.getElementById("latitude").value),
                    Number(document.getElementById("longitude").value)
                )
            );
            Location_URL.value = `https://www.google.com/maps/search/?api=1&query=${Number(
                document.getElementById("latitude").value
            )}%2C${Number(document.getElementById("longitude").value)}`;

            getDistrict(Number(document.getElementById("latitude").value), Number(document.getElementById("longitude").value));
        };
        document.getElementById("location_radius").oninput = function () {
            var location_radius =
                document.getElementById("location_radius").value;
            cityCircle.setRadius(location_radius * 1);
        };

        // Configure the click listener.
        map.addListener("click", (mapsMouseEvent) => {
            $("#latitude").val(mapsMouseEvent.latLng.toJSON()["lat"]);
            $("#longitude").val(mapsMouseEvent.latLng.toJSON()["lng"]);
            marker.setPosition(
                new google.maps.LatLng(
                    Number(document.getElementById("latitude").value),
                    Number(document.getElementById("longitude").value)
                )
            );
            cityCircle.setCenter({
                lat: Number(document.getElementById("latitude").value),
                lng: Number(document.getElementById("longitude").value),
            });
            Location_URL.value = `https://www.google.com/maps/search/?api=1&query=${Number(
                document.getElementById("latitude").value
            )}%2C${Number(document.getElementById("longitude").value)}`;

            getDistrict(Number(document.getElementById("latitude").value), Number(document.getElementById("longitude").value));
        });

        mapSearch(map);
    });
}

function mapSearch(map) {
    // Create the search box and link it to the UI element.
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(searchInput);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(searchBtn);
    console.log("ddd");

    searchBox = new google.maps.places.SearchBox(searchInput);

    map.addListener("bounds_changed", function () {
        searchBox.setBounds(map.getBounds());
    });

    searchBtn.onclick = function () {
        displaySearchResults(map, searchBox, marker);
    };
}

function displaySearchResults(map, searchBox, marker) {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
        return;
    }

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function (place) {
        if (!place.geometry) {
            console.log("Returned place contains no geometry");
            return;
        }

        // location = new google.maps.LatLng(place.geometry.location.toJSON().lat, place.geometry.location.toJSON().lng);
        map.setCenter(
            new google.maps.LatLng(
                place.geometry.location.toJSON().lat,
                place.geometry.location.toJSON().lng
            )
        );
        marker.setPosition(place.geometry.location);
        latitude = document.getElementById("latitude").value =
            place.geometry.location.toJSON().lat;
        longitude = document.getElementById("longitude").value =
            place.geometry.location.toJSON().lng;
        Location_URL.value = `https://www.google.com/maps/search/?api=1&query=${
            place.geometry.location.toJSON().lat
        }%2C${place.geometry.location.toJSON().lng}`;

        cityCircle.setCenter({
            lat: place.geometry.location.toJSON().lat,
            lng: place.geometry.location.toJSON().lng,
        });

        getDistrict(Number(document.getElementById("latitude").value), Number(document.getElementById("longitude").value));

        if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
        } else {
            bounds.extend(place.geometry.location);
        }
    });
    map.fitBounds(bounds);
}
