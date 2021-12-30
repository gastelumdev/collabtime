

// 2021-06-05 OG NEW - Get the info from the page that will be provided to map api
//                     This function can be changed to obtain the address info from a different source
//                     As long as it return an array of objects with the corresponding properties
function getAddresses() {
    // 2021-06-05 OG NEW - Get all the cards from the page 
    var groups = document.getElementsByClassName('card-info');
    // 2021-06-05 OG NEW - Initialize an empty array to store the info 
    var result = [];
    // 2021-06-05 OG NEW - Go through each group and format the info into an object 
    for (var i = 0; i < groups.length; i++) {
        result.push({
            type: groups[i].children[2].innerHTML, // The type of group
            value: groups[i].children[3].innerHTML + " " + groups[i].children[4].innerHTML, // The address value
            markup: groups[i].innerHTML // The entire markup to be used in the info window
        });
    }

    return result;
}

// 2021-06-05 OG NEW - Get geocode of an address and provide it to a callback function to set up the markers
function getGeocode(map, addresses, i, callback) {
    // 2021-06-05 OG NEW - Instantiate a new geocoder and pass in the address value 
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': addresses[i].value}, function(results, status) {
        // 2021-06-05 OG NEW - if everything's ok... 
        if (status == 'OK') {
            // 2021-06-05 OG NEW - Call the addMarker function 
            callback({
                position:results[0].geometry.location,
                map: map,
                icon: addresses[i].type,
                infoMarkup: addresses[i].markup
            });
            
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

// 2021-06-04 OG NEW - Function to add a marker on the map 
function addMarker(details) {
    var marker = new google.maps.Marker({
        position: details.position,
        map: details.map,
        icon: 'images/map_markers/'+ details.icon +'.png'
    });

    var infoWindow = new google.maps.InfoWindow({
        content: details.infoMarkup
    });

    marker.addListener('click', function(){
        infoWindow.open(details.map, marker);
    });
}

// 2021-06-29 OG NEW - Sets card markup for each group that is returned 
function createCards(info, elementId) {
    var el = document.getElementById(elementId);
    var markupString = '';
    
    for (var i = 0; i < info['groups'].length; i++) {
        markupString += '<div class="col-lg-3 col-md-6">' +
                            '<div class="card card-small">' +
                                '<div class="card-image"><img src="images/hero/' + info['groups'][i]['category'] + '.jpg" alt=""></div>'+
                                '<div class="card-details">'+
                                    '<div class="card-title">'+
                                        '<h4 class="card-title"><a href="index.php?group?id='+ info['groups'][i]['id'] +'">' + info['groups'][i]['name'] + '</a></h4>'+
                                    '</div>'+
                                    '<div class="card-info">'+
                                        '<p>Last activity: 8 days ago</p>'+
                                        '<p>'+ info['groups'][i]['membersCount'] +' Members</p>'+
                                        '<p hidden>meditation</p>'+
                                        '<p hidden>' + info['groups'][i]['street'] + '</p>'+
                                        '<p>' + info['groups'][i]['city'] + '</p>'+
                                    '</div>'+
                                    '<div class="card-description">'+
                                        '<p>' + info['groups'][i]['description'] + '</p>'+
                                    '</div>'+
                                '</div>';
        if (info['loggedIn']) {
            if (info['groups'][i]['member'] == true) {
                markupString += '<div class="card-button active">'+
                                    '<button id="' + info['groups'][i]['id'] + '" value="Leave" class="btn btn-success btn-block btn-action">Leave</button>'+
                                '</div>';
            } else {
                markupString += '<div class="card-button">'+
                                    '<button id="' + info['groups'][i]['id'] + '" value="Join" class="btn btn-primary btn-block btn-action">Join</button>'+
                                '</div>';
            }
        }

        markupString += '</div></div>';
    }

    el.innerHTML = markupString;
}

// 2021-07-01 OG NEW - Set markup for the map marker info window 
function setInfowindowMarkup(info) {
    return  '<h4>'+ info['name'] +'</h4>' +
            '<p>'+ info['street'] + ' ' + info['city'] + ' ' + info['state'] + '</p>';
}

// 2021-07-01 OG NEW - Retrieve the groups based on the zipcode parameter and set the map based on that same zipcode 
function setMap(zipcode) {
    // 2021-07-01 OG NEW - Set a new geocoder 
    var geocoder = new google.maps.Geocoder();

    // 2021-07-01 OG NEW - Get all groups located in the the zipcode provided 
    $(document).ready(function() {
        $.ajax({
            url: 'index.php?group/jsonlist',
            method: 'POST',
            data: {zipcode: zipcode},
            success: function(data, textStatus, jqXHR) {
                // 2021-07-01 OG NEW - If the call is successful 
                if (textStatus == 'success') {
                    const parsedData = JSON.parse(data);
                    const groups = parsedData['groups'];
                    
                    // 2021-07-01 OG NEW - Use the geocoder to center the map based on the zipcode 
                    geocoder.geocode({'address': zipcode}, function(results, status) {
                        if (status == 'OK') {
                            // 2021-06-04 OG NEW - Map options 
                            var options = {
                                zoom: 12,
                                center: results[0].geometry.location
                            }

                            // 2021-06-04 OG NEW - Map 
                            var map = new google.maps.Map(document.getElementById('map'), options);

                            // 2021-07-01 OG NEW - Go through all the groups and set the data needed to add markers 
                            var addresses = [];

                            for (var i = 0; i < groups.length; i++) {
                                addresses.push({
                                    type: groups[i]['category'], // The type of group
                                    value: groups[i]['street'] + ' ' + groups[i]['city'] + ' ' + groups[i]['state'], // The address value
                                    markup: setInfowindowMarkup(groups[i]) // The entire markup to be used in the info window
                                });
                            }

                            // // 2021-06-05 OG NEW - Go through each address that gets the geocode and then runs the addMarker 
                            for (var i = 0; i < addresses.length; i++) {
                                getGeocode(map, addresses, i, addMarker);
                            }

                            // 2021-07-01 OG NEW - Set the group cards for each group returned 
                            createCards(parsedData, 'groupCards');

                            membership();
                        }
                    });
                }
                
            }
        });
    });
}

// 2021-06-04 OG NEW - Attach your callback function to the `window` object 
function initMap() {
    // 2021-06-04 OG NEW - JS API is loaded and available
    // 2021-07-01 OG NEW - Get the logged-in user's zipcode to set map
    var zipcode = document.getElementById('userZipcode').value;
    setMap(zipcode);
    
}


function resetMap() {
    // 2021-07-01 OG NEW - Get the zipcode from the user input to set map 
    var zipcode = document.getElementById('search-groups').value;
    setMap(zipcode);
}

// 2021-07-01 OG NEW - When update location button is clicked, reset the map 
function handleUpadateLocation() {
    resetMap();
}

document.getElementById('updateLocation').addEventListener('click', handleUpadateLocation, false);