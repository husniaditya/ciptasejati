
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
    "/ciptasejati/cabang",
    "/ciptasejati/koordinatorcabang"
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
    var pusat = [
        { lat: -3.3063120100780785, lng: 114.56921894055016, title: "Pusat" },
        // Add more locations as needed
    ];
    var cabang = [
        { lat: -2.6680412, lng: 112.9369602, title: "Jl HM Arsyad KM 17 Ds Bapeang Meranti 01 Kab Kotim Kalimantan Tengah, Sampit" },
        { lat: -7.250445, lng: 112.768845, title: "Surabaya" },
        { lat: -6.121435, lng: 106.774124, title: "Jakarta" },
        { lat: -3.3063120100780785, lng: 114.56921894055016, title: "Jln Pembangunan Ujung RT 34 No, 30 Banjarmasin" },
        { lat: 3.597031, lng: 98.678513, title: "Medan" },
        { lat: -8.409518, lng: 115.188919, title: "Bali" },
        { lat: -5.135399, lng: 119.423790, title: "Makassar" },
        // Add more locations as needed
    ];

    // Initialize and add the map
    let map;

    async function initMap() {
        // Request needed libraries.
        //@ts-ignore
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        // The map, centered at the initial location
        map = new Map(document.getElementById("googlemaps"), {
            zoom: 17, // Adjust the initial zoom level as needed
            center: pusat[0], // Center the map at the first location
            mapId: "DEMO_MAP_ID",
        });

        // Add markers for each location
        pusat.forEach(location => {
            const marker = new AdvancedMarkerElement({
                map: map,
                position: location,
                title: location.title,
            });
        });
    }
    initMap();

    async function initCabang() {
        // Request needed libraries.
        //@ts-ignore
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        // The map, centered at the initial location
        map = new Map(document.getElementById("cabangmaps"), {
            zoom: 5, // Adjust the initial zoom level as needed
            center: cabang[0], // Center the map at the first location
            mapId: "DEMO_MAP_ID",
        });

        // Add markers for each location
        cabang.forEach(location => {
            const marker = new AdvancedMarkerElement({
                map: map,
                position: location,
                title: location.title,
            });
        });
    }
    initCabang();


    // Styles from https://snazzymaps.com/
    var styles = [{"featureType":"all","elementType":"labels.text","stylers":[{"color":"#878787"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f9f5ed"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"color":"#f5f5f5"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#aee0f4"}]}];

    var styledMap = new google.maps.StyledMapType(styles, {
        name: 'Styled Map'
    });

    mapRef.mapTypes.set('map_style', styledMap);
    mapRef.setMapTypeId('map_style');

    // End Map Setting