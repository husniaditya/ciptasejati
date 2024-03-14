
// Start Nav Acitve
// Get the current page URL
var currentUrl = window.location.href;
			
// Get the current page URL
var currentPath = window.location.pathname;

// Get all the menu items with the class "nav-link"
var menuItems = document.querySelectorAll('.nav-link');
// Get the top-level dropdown header item with the class "nav-link"
var dropdownHeaderItem = document.querySelector('.dropdown-item.dropdown-toggle.nav-link');


// Loop through the menu items
menuItems.forEach(function (menuItem) {
    // Check if the menu item's href matches the current URL
    if (menuItem.href === currentUrl) {
        // Add the "active" class to the matching menu item
        menuItem.classList.add('active');
    }
});

// Check if the current URL matches any of the target URLs for the dropdown header
var targetDropdownHeaderUrls = [
    "/ciptasejati/cabang.php",
    "/ciptasejati/koordinatorcabang.php"
];
if (targetDropdownHeaderUrls.includes(currentPath)) {
    // Add the "active" class to the dropdown header item
    dropdownHeaderItem.classList.add('active');
}
// End Nav Active


// Start Map Setting

    /*
    Map Settings

        Find the Latitude and Longitude of your address:
            - https://www.latlong.net/
            - http://www.findlatitudeandlongitude.com/find-address-from-latitude-and-longitude/

    */

    

// Map Initial Locations

var cabang = [];

// Perform AJAX request
var xhr = new XMLHttpRequest();
xhr.open('POST', 'module/ajax/aj_loadmaps.php', true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
        if (xhr.status === 200) {
            // Parse the JSON response
            var responseData = JSON.parse(xhr.responseText);
            
            // Populate the cabang array with the retrieved data
            cabang = responseData;
            
            // Now you can use the cabang array for your purposes
            // console.log("cabang:", cabang);
            
            // Call initCabang after fetching data
            initCabang();
        } else {
            console.error("AJAX request failed with status:", xhr.status);
        }
    }
};
xhr.onerror = function() {
    console.error("AJAX request failed.");
};
xhr.send();


// Initialize and add the map
let map;

async function initCabang() {
    // Check if Google Maps API script is loaded
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
        const cabangMapsDiv = document.getElementById("cabangmaps");
        
        // The map, centered at the initial location
        map = new google.maps.Map(cabangMapsDiv, {
            zoom: 5, // Adjust the initial zoom level as needed
            center: { lat: -2.668103, lng: 112.9343172 }, // Center the map at the first location
            mapId: "DEMO_MAP_ID",
        });

        // console.log("cabang:", cabang); // Check if cabang data is available

        // Add markers for each location
        cabang.forEach(location => {
            const latLng = { lat: parseFloat(location.lat), lng: parseFloat(location.lng) };
            // console.log("Adding marker at:", latLng); // Check if marker positions are correct
            const marker = new google.maps.Marker({
                position: latLng,
                map: map,
                title: location.title,
            });
        });
    } else {
        // Google Maps API script hasn't loaded yet, retry after a delay
        setTimeout(initCabang, 100);
    }
}

// Call the function to initialize the map
initCabang();





// Styles from https://snazzymaps.com/
var styles = [{"featureType":"all","elementType":"labels.text","stylers":[{"color":"#878787"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f9f5ed"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"color":"#f5f5f5"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#aee0f4"}]}];

var styledMap = new google.maps.StyledMapType(styles, {
    name: 'Styled Map'
});


// End Map Setting