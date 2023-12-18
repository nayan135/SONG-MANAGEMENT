<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

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
    <link rel="stylesheet" href="\new\main\newww.css"> 
</head>
<body>

<?php
// Output audio data
while ($row = $result->fetch_assoc()) {
    $encodedFilePath = urlencode($row['song_path']);
    $encodedFilePath = str_replace('+', '%20', $encodedFilePath); 
    
echo '<div class="song-tile" onclick="showCard(\'' . $encodedFilePath . '\', \'' . $row['song_name'] . '\', \'' . $row['artist_name'] . '\', \'' . $row['image_path'] . '\')">';
           echo '<div class="tile__img" id="cardImg"></div>';
    echo '<div class="tile__info">';
    echo '<div class="tile__title" id="tileSongName">' . $row['song_name'] . '</div>';
    echo '<div class="tile__subtitle" id="tileArtistName">' . $row['artist_name'] . '</div>';
    echo '</div>';
    echo '</div>';
}
?>

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

<script>


  function showCard(encodedSongPath, songName, artistName, imagePath) {
    const card = document.getElementById('sdiv');
    const audioPlayer = document.getElementById('audioPlayer');
    const cardImg = document.getElementById('cardImg');
    audioPlayer.innerHTML = '';

    // Decode the URL-encoded file path
    const decodedSongPath = decodeURIComponent(encodedSongPath);

    // Create a new source element
    const source = document.createElement('source');
    source.src = decodedSongPath;
    source.type = 'audio/mp3'; // Adjust the type based on your audio file format

    // Append the source element to the audio player
    audioPlayer.appendChild(source);

    // Set the text content of card elements
    document.getElementById('songName').textContent = songName;
    document.getElementById('artistName').textContent = artistName;
 cardImg.style.backgroundImage = "url(" + imagePath + ")";
        
 audioPlayer.load(); // Reload the audio element to apply the changes
 
    audioPlayer.play();

    card.style.display = 'block';
}

    function closeCard() {
        const card = document.getElementById('sdiv');
        card.style.display = 'none';
    }
// Get references to HTML elements

const audioPlayer = document.getElementById('audioPlayer');
const playPauseButton = document.querySelector('.play-pause');
const backwardButton = document.querySelector('.backward');
const forwardButton = document.querySelector('.forward');
 const progressBar = document.querySelector('.progress-bar');
    const currentTimeDisplay = document.querySelector('.card__time-passed');
    const totalTimeDisplay = document.querySelector('.card__time-left');
let isPlaying = false;
playPauseButton.addEventListener('click', togglePlay);

// Add event listeners for play-pause button
playPauseButton.addEventListener('click', togglePlay);

// Add event listeners for backward and forward buttons
backwardButton.addEventListener('click', backward);
forwardButton.addEventListener('click', forward);

// Event listener for canplaythrough event

// Function to toggle play-pause


// Function to toggle play-pause
function togglePlay() {
    if (audioPlayer.paused) {
        audioPlayer.play().catch((error) => {
            console.error('Play failed:', error);
        });
    } else {
        audioPlayer.pause();
    }
}

audioPlayer.addEventListener('play', () => {
    isPlaying = true;
    playPauseButton.innerHTML = '<svg height="22px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="18px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M224,435.8V76.1c0-6.7-5.4-12.1-12.2-12.1h-71.6c-6.8,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6   C218.6,448,224,442.6,224,435.8z"/><path d="M371.8,64h-71.6c-6.7,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6c6.7,0,12.2-5.4,12.2-12.2V76.1   C384,69.4,378.6,64,371.8,64z"/></g></svg>';

    });

// Event listener for pause event
audioPlayer.addEventListener('pause', () => {
    isPlaying = false;
    playPauseButton.innerHTML = '<svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg>';

    });

// Event listener for canplaythrough event
audioPlayer.addEventListener('canplaythrough', () => {
    if (isPlaying) {
        audioPlayer.play();
    }
});
// Function to go backward
function backward() {
    audioPlayer.currentTime -= 5; // Go back 5 seconds
}

// Function to go forward
function forward() {
    audioPlayer.currentTime += 5; // Go forward 5 seconds
}
currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
            totalTimeDisplay.textContent = formatTime(audioPlayer.duration);

            // Add event listeners for the audio player
            audioPlayer.addEventListener('timeupdate', function () {
                currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
                totalTimeDisplay.textContent = formatTime(audioPlayer.duration);
                var percentage = (audioPlayer.currentTime / audioPlayer.duration) * 100;
                progressBar.value = percentage;
            });

 progressBar.addEventListener('input', updateProgress);

            function updateProgress() {
                const progress = parseInt(progressBar.value);
                const duration = audioPlayer.duration;
                const currentTime = (progress * duration) / 100;
                audioPlayer.currentTime = currentTime;
            }
        

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            const formattedTime = `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
            return formattedTime;
        }


  const stickyDiv = document.getElementById("sdiv");

        // Get the initial position of the div
        const initialPosition = stickyDiv.getBoundingClientRect().top;

        // Add a scroll event listener to handle scrolling
        window.addEventListener("scroll", function () {
            // Check if the page has been scrolled past the initial position of the div
            if (window.scrollY >= initialPosition) {
                // Make the div sticky
                stickyDiv.style.position = "fixed";
                stickyDiv.style.top = "auto";
            } else {
                // Unstick the div
                stickyDiv.style.position = "static";
            }
        });

     

        card.addEventListener('mouseover', function () {
            closeButton.style.display = 'block';
        });

        card.addEventListener('mouseout', function () {
            closeButton.style.display = 'none';
        });

</script>
</body>
</html>
