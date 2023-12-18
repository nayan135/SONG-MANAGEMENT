<?php
include 'config.php';

// Query to retrieve all songs
$sql = "SELECT id, song_name, artist_name, song_path FROM songs"; // Change 'songs' to your table name

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $songs = array();
    while ($row = $result->fetch_assoc()) {
        $songs[] = $row;
    }
    echo json_encode($songs); // Return the data as JSON
} else {
    echo "0 results";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="newww.css">
      <style>
      .card {
            position: relative;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            display: none;
            cursor: pointer;
        } 
        /* Add or modify styles as needed */
        .song-tile {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .tile__img {
            width: 50px;
            height: 50px;
            background-size: cover;
            background-position: center;
            margin-right: 10px;
        }

        .tile__info {
            flex: 1;
        }

        .tile__title {
            font-size: 18px;
            font-weight: bold;
        }

        .tile__subtitle {
            font-size: 14px;
            color: #888;
        }
    </style>
</head>

<body>
 <div class="song-tile" onclick="showCard()">
        <div class="tile__img"></div>
        <div class="tile__info">
            <div class="tile__title" id="tileSongName">Song Name</div>
            <div class="tile__subtitle" id="tileArtistName">Artist</div>
        </div>
    </div>
  <div class="card" id="sdiv" style="display: none;">
        <div class="close-button" id="closeButton" onclick="closeCard()">Close</div>
        <div class="card__img"></div>
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

            <button class="card__btn play-pause"> <svg fill="#fff" height="22" viewBox="0 0 18 22" width="18"
                    xmlns="http://www.w3.org/2000/svg"><path
                        d="m0 0v22l18-11z" fill="#000"></path></svg></button>
            <button class="card__btn forward"><svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                    xmlns="http://www.w3.org/2000/svg"><path
                        d="M11.5 8V0L23 8L11.5 16V8ZM0 0L11.5 8L0 16V0Z" fill="#fff"></path></svg></button>
        </div>
    </div>

    <audio id="audioPlayer"></audio>
    <script>
     function showCard() {
        const card = document.getElementById('sdiv');
        card.style.display = 'block';
    }

    function closeCard() {
        const card = document.getElementById('sdiv');
        card.style.display = 'none';
    }
       const card = document.getElementById('sdiv');
        const closeButton = document.getElementById('closeButton');
    document.addEventListener('DOMContentLoaded', function () {
    const audioPlayer = document.getElementById('audioPlayer');
    const playPauseButton = document.querySelector('.play-pause');
    const progressBar = document.querySelector('.progress-bar');
    const forwardButton = document.querySelector('.forward');
    const backwardButton = document.querySelector('.backward');
    const currentTimeDisplay = document.querySelector('.card__time-passed');
    const totalTimeDisplay = document.querySelector('.card__time-left');
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const songs = response;

                songs.forEach(song => {
                    const tile = document.createElement('div');
                    tile.className = 'song-tile';
                    tile.addEventListener('click', function () {
                        loadDetailsAndPlay(song);
                    });

                    // Set background image for the tile
                    const tileImage = document.createElement('div');
                    tileImage.className = 'tile__img';
                    tileImage.style.backgroundImage = `url('${song.song_path}')`;
                    tileImage.style.backgroundSize = 'cover';
                    tileImage.style.backgroundPosition = 'center';
                    tile.appendChild(tileImage);

                    const tileInfo = document.createElement('div');
                    tileInfo.className = 'tile__info';

                    const tileTitle = document.createElement('div');
                    tileTitle.className = 'tile__title';
                    tileTitle.textContent = song.song_name;
                    tileInfo.appendChild(tileTitle);

                    const tileSubtitle = document.createElement('div');
                    tileSubtitle.className = 'tile__subtitle';
                    tileSubtitle.textContent = song.artist_name;
                    tileInfo.appendChild(tileSubtitle);

                    tile.appendChild(tileInfo);
                    card.appendChild(tile);
                });
            } else {
                console.error('Error getting song details:', xhr.status, xhr.statusText);
            }
        }
    };

    xhr.open('GET', 'get_image_path.php', true); // Change the URL to your PHP file
    xhr.send();
      function loadDetailsAndPlay(song) {
         document.getElementById('songName').textContent = song.song_name;
            document.getElementById('artistName').textContent = song.artist_name;
            const cardImg = document.querySelector('.card__img');
            cardImg.style.backgroundImage = `url('${song.image_path}')`;
            cardImg.style.backgroundSize = 'cover';
            cardImg.style.backgroundPosition = 'center';

            // Set audio source and play
            audioPlayer.src = song.audio_path;
            playPauseButton.innerHTML = '<svg height="22px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="18px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M224,435.8V76.1c0-6.7-5.4-12.1-12.2-12.1h-71.6c-6.8,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6   C218.6,448,224,442.6,224,435.8z"/><path d="M371.8,64h-71.6c-6.7,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6c6.7,0,12.2-5.4,12.2-12.2V76.1   C384,69.4,378.6,64,371.8,64z"/></g></svg>';
            audioPlayer.play();
 currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
            totalTimeDisplay.textContent = formatTime(audioPlayer.duration);

            // Add event listeners for the audio player
            audioPlayer.addEventListener('timeupdate', function () {
                currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
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
    });
</script>