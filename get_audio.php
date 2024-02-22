<?php
include 'config.php';



$sql = "SELECT id, song_name, artist_name, song_path, image_path FROM songs";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Player</title>
    <link rel="stylesheet" href="newww.css"> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<body>
    <div class="song-container">
    <?php
        while ($row = $result->fetch_assoc()) {
        $encodedFilePath = urlencode($row['song_path']);
        $encodedFilePath = str_replace('+', '%20', $encodedFilePath);
    ?>
    <div class="song-tile" onclick="showCard('<?php echo $encodedFilePath ?>', '<?php echo $row['song_name'] ?>', '<?php echo $row['artist_name'] ?>', '<?php echo $row['image_path'] ?>')">
        <div class="tile__img song-tile-img" style="background-image: url('<?php echo $row['image_path'] ?>');"></div>
        <div class="tile__info">
        <div class="tile__title"><?php echo $row['song_name'] ?></div>
        <div class="tile__subtitle"><?php echo $row['artist_name'] ?></div>
        <div class="play-pause" onclick="showCard()"><span class="material-symbols-outlined">play_arrow</span></div>
        </div>
    </div>
    <?php } ?>
    </div>


<div class="card" id="sdiv" style="display: none;">
    <div class="close-button" id="closeButton" onclick="closeCard()">Close</div>
    <div class="card__img" id="cardImg"></div>
    <div class="card__title" id="songName">Song Name</div>
    <div class="card__subtitle" id="artistName">Artist</div>
    <div class="card__wrapper">
        <div class="card__time card__time-passed">00:00</div>
        <div class="card__timeline"><progress class="progress-bar" value="0" max="100"></progress></div>
        <div class="card__time card__time-left">00:00</div>
    </div>
    <div class="card__wrapper">
        <button class="card__btn backward"><svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                xmlns="http://www.w3.org/2000/svg"><path
                    d="M11.5 8V0L0 8L11.5 16V8ZM23 0L11.5 8L23 16V0Z" fill="#fff"></path></svg></button>

        <button class="card__btn play-pause" onclick="togglePlay()"> <!-- Add onclick function for play-pause -->
            <svg fill="#fff" height="22" viewBox="0 0 18 22" width="18"
                xmlns="http://www.w3.org/2000/svg"><path
                    d="m0 0v22l18-11z" fill="#000"></path></svg>
        </button>
        <button class="card__btn forward"><svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                xmlns="http://www.w3.org/2000/svg"><path
                    d="M11.5 8V0L23 8L11.5 16V8ZM0 0L11.5 8L0 16V0Z" fill="#fff"></path></svg></button>
    </div>
</div>

<audio id="audioPlayer"></audio>

<script src="testing.js"></script>
 <!--
_________-implementing google maps api-_______

<script>
    // Fetch location using Google Maps API
    function initMap() {
        navigator.geolocation.getCurrentPosition(function (position) {
            var userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            // Send location and device name to the server using fetch
            fetch('your_php_script.php', {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'latitude=' + userLocation.lat + '&longitude=' + userLocation.lng + '&dname=' + navigator.userAgent,
            })
                .then(response => response.text())
                .then(data => console.log(data))
                .catch(error => console.error('Error:', error));

            // Display location information in text format
            var locationInfo = document.getElementById('locationInfo');
            locationInfo.textContent = 'Your current location is: ' + userLocation.lat + ', ' + userLocation.lng;
        }, function () {
            // Handle geolocation error
            alert('Error: The Geolocation service failed.');
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initMap"></script>
-->
</body>
</html>
