<?php 
  $servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$audioId = '2';
// Fetch the audio file 
$sqlAudio = "SELECT audio_path FROM audio_table ";
$resultAudio = $conn->query($sqlAudio);
$audioPath = "";
if ($resultAudio->num_rows > 0) {
    $rowAudio = $resultAudio->fetch_assoc();
    $audioPath = $rowAudio["audio_path"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="newww.css">
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
    <div id="close" onclick="closeCard()"><svg version="1.1"
     xmlns="http://www.w3.org/2000/svg">
    <line x1="1" y1="11" 
          x2="11" y2="0" 
          stroke="white" 
          stroke-width="2"/>
    <line x1="1" y1="1" 
          x2="11" y2="11" 
          stroke="white" 
          stroke-width="2"/>
</svg></div>
        <div class="card__img">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"></svg>
        </div>
        <div class="card__title" id="songName">Song Name</div>
        <div class="card__subtitle" id="artistName">Artist</div>
        <div class="card__wrapper">
           <div class="card__time card__time-passed">00:00</div>
            <div class="card__timeline"><progress class="progress-bar" value="0" max="100"></progress></div>
            <div class="card__time card__time-left">00:00</div>
        </div>
        <div class="card__wrapper">
           <button class="card__btn backward"><svg width="23" height="16" viewBox="0 0 23 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 8V0L0 8L11.5 16V8ZM23 0L11.5 8L23 16V0Z" fill="#fff"></path></svg></button>
            
           <button class="card__btn play-pause" >  <svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg></button> 
               <button class="card__btn forward"><svg width="23" height="16" viewBox="0 0 23 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 8V0L23 8L11.5 16V8ZM0 0L11.5 8L0 16V0Z" fill="#fff"></path></svg></button>
        </div>
    </div>
   <audio id="audioPlayer" src="<?php echo $audioPath; ?>"></audio>

    <script>
      function showCard() {
            const card = document.getElementById('sdiv');
            card.style.display = 'block';


            // You can add additional logic here if needed
        }
       function closeCard(){

         const card = document.getElementById('sdiv');
         const ap = document.getElementById('audioPlayer');
         card.style.display= 'none';
         ap.src='none';
         
        }
      document.addEventListener('DOMContentLoaded', function() {
            const audioPlayer = document.getElementById('audioPlayer');
            const playPauseButton = document.querySelector('.play-pause');
            const progressBar = document.querySelector('.progress-bar');
            const forwardButton = document.querySelector('.forward');
            const backwardButton = document.querySelector('.backward');
            const currentTimeDisplay = document.querySelector('.card__time-passed');
            const totalTimeDisplay = document.querySelector('.card__time-left');
            const decodedSrc = decodeURIComponent(audioPlayer.src);
            const [songName, artistName] = extractSongAndArtist(decodedSrc);
document.getElementById('tileSongName').textContent = songName;
            document.getElementById('tileArtistName').textContent = artistName;
            playPauseButton.addEventListener('click', togglePlayPause);
            forwardButton.addEventListener('click', forward);
            backwardButton.addEventListener('click', backward);
            progressBar.addEventListener('input', updateProgress);

            document.getElementById('songName').textContent = songName;
            document.getElementById('artistName').textContent = artistName;
           function extractSongAndArtist(audioSrc) {
                
                const fileName = audioSrc.split('/').pop();
            
                const nameWithoutExtension = fileName.split('.')[0];
                const [song,artist] = nameWithoutExtension.split('--').map(str => str.trim());
                return [song,artist];
            }
             
          function togglePlayPause() {
    const playPauseButton = document.querySelector('.play-pause');
    if (audioPlayer.paused) {
             playPauseButton.innerHTML = '<svg height="22px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="18px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M224,435.8V76.1c0-6.7-5.4-12.1-12.2-12.1h-71.6c-6.8,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6   C218.6,448,224,442.6,224,435.8z"/><path d="M371.8,64h-71.6c-6.7,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6c6.7,0,12.2-5.4,12.2-12.2V76.1   C384,69.4,378.6,64,371.8,64z"/></g></svg>';
             audioPlayer.play();
    } else {
               playPauseButton.innerHTML = '<svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg>';
                audioPlayer.pause();
    }
}
            function forward() {
                audioPlayer.currentTime += 5;
            }
            function backward() {
                audioPlayer.currentTime -= 5;
            }
            audioPlayer.addEventListener('timeupdate', function() {
                currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
                var percentage = (audioPlayer.currentTime / audioPlayer.duration) * 100;
                progressBar.value = percentage;
                totalTimeDisplay.textContent = formatTime(audioPlayer.duration);
            });

            function updateProgress() {
                const progressBar = document.querySelector('.progress-bar');
                const progress = parseInt(progressBar.value);
                const duration = audioPlayer.duration;

                const currentTime = (progress * duration) / 100;
                audioPlayer.currentTime = currentTime;
            }
            totalTimeDisplay.textContent = formatTime(audioPlayer.duration);
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                const formattedTime = `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
                return formattedTime;
            }
            const xhr = new XMLHttpRequest();
xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const imagePath = response.imagePath;
            const cardImg = document.querySelector('.card__img');
            cardImg.style.backgroundImage = "url('" + imagePath + "')";
            cardImg.style.backgroundSize = "cover";
            cardImg.style.backgroundPosition = "center";
        } else {
            console.error('Error getting image path:', xhr.status, xhr.statusText);
        }
    }
};
xhr.open('POST', 'get_image_path.php', true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.send('songName=' + encodeURIComponent(songName));
        });



        var stickyDiv = document.getElementById("sdiv");

// Get the initial position of the div
var initialPosition = stickyDiv.getBoundingClientRect().top;

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

    </script>
</body>

</html>
