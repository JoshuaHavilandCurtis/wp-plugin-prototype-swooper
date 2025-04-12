function getUserId() {
    // Assuming userData is a global variable with user info, and userData.userId contains the logged-in user ID
    if (typeof userData !== 'undefined' && userData.userId) {
        console.log('Logged-in User ID:', userData.userId);
        return userData.userId;  // Return the logged-in user ID
    } else {
        console.log('User is not logged in.');
        return null;  // No user ID for non-logged-in users
    }
}

// Send your location to the server
function sendLocation(lat, lng) {
    const userId = getUserId();
    if (!userId) return;  // Do not send location if user is not logged in
    
    fetch('/wp-json/ult/v1/location', {
        method: 'POST',
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ lat, lng, user_id: userId })  // Include the user ID
    });
}

// Fetch all user locations and show them
function fetchAndDisplayUsers(map) {
    fetch('/wp-json/ult/v1/locations')
    .then(res => res.json())
    .then(users => {
        users.forEach(user => {
            new google.maps.Marker({
                position: { lat: parseFloat(user.lat), lng: parseFloat(user.lng) },
                map: map,
                icon: {
                    url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                }
            });
        });
    });
}

function initMap() {
    const userId = getUserId();

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 2,
        center: { lat: 0, lng: 0 }
    });

    if (navigator.geolocation && userId !== null) {
        // If the user is logged in, get and send their location
        navigator.geolocation.watchPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                sendLocation(pos.lat, pos.lng);  // Send the logged-in user's location

                new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: "You",
                    icon: {
                        url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    }
                });

                map.setCenter(pos);
                map.setZoom(12);

                fetchAndDisplayUsers(map);
                
                // Fetch all users every 10 seconds
                setInterval(() => {
                    fetchAndDisplayUsers(map);
                }, 10000);
            },
            () => {
                alert("Geolocation failed.");
            }
        );
    } else if (userId === null) {
        // If the user is not logged in, just fetch and display the other users' locations
        fetchAndDisplayUsers(map);
    }
}