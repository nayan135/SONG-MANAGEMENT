<?php

include 'config.php';

// Query to retrieve all audio files
$sql = "SELECT id, audio_path FROM audio_table"; // Change audio_table to your table name

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $audioFiles = array();
    while ($row = $result->fetch_assoc()) {
        $audioFiles[] = $row;
    }
    echo json_encode($audioFiles);
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
</head>

<body>
    <div class="audio-players-container">
       
    </div>

    
</body><script>
    document.addEventListener('DOMContentLoaded', function () {
        const audioPaths = <?php echo json_encode($audioFiles); ?>;
        const audioPlayersContainer = document.querySelector('.audio-players-container');

        audioPaths.forEach((audioPath, index) => {
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = ` <div class="card">
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
   <audio id="audioPlayer" src="<?php echo $audioFiles; ?>"></audio> `;
            audioPlayersContainer.appendChild(card);

            const audioPlayer = new Audio(audioPath);
            audioPlayer.addEventListener('loadedmetadata', function () {
                document.getElementById(`songName${index}`).textContent = audioPlayer.title;
                document.getElementById(`artistName${index}`).textContent = audioPlayer.artist;
            });

            const playPauseButton = card.querySelector('.play-pause');
            const progressBar = card.querySelector('.progress-bar');
            const forwardButton = card.querySelector('.forward');
            const backwardButton = card.querySelector('.backward');
            const currentTimeDisplay = card.querySelector('.card__time-passed');
            const totalTimeDisplay = card.querySelector('.card__time-left');

            playPauseButton.addEventListener('click', togglePlayPause);
            forwardButton.addEventListener('click', () => seek(5));
            backwardButton.addEventListener('click', () => seek(-5));
            progressBar.addEventListener('input', updateProgress);

            function togglePlayPause() {
                if (audioPlayer.paused) {
                    playPauseButton.innerHTML = '<svg height="22px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="18px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M224,435.8V76.1c0-6.7-5.4-12.1-12.2-12.1h-71.6c-6.8,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6C218.6,448,224,442.6,224,435.8z"/><path d="M371.8,64h-71.6c-6.7,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6c6.7,0,12.2-5.4,12.2-12.2V76.1C384,69.4,378.6,64,371.8,64z"/></g></svg>';
                    audioPlayer.play();
                } else {
                    playPauseButton.innerHTML = '<svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg>';
                    audioPlayer.pause();
                }
            }

            function seek(offset) {
                audioPlayer.currentTime += offset;
            }

            audioPlayer.addEventListener('timeupdate', function () {
                currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
                const percentage = (audioPlayer.currentTime / audioPlayer.duration) * 100;
                progressBar.value = percentage;
                totalTimeDisplay.textContent = formatTime(audioPlayer.duration);
            });

            function updateProgress() {
                const progress = parseInt(progressBar.value);
                const duration = audioPlayer.duration;
                const currentTime = (progress * duration) / 100;
                audioPlayer.currentTime = currentTime;
            }

            totalTimeDisplay.textContent = formatTime(audioPlayer.duration);

            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
            }

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        const imagePath = response.imagePath;
                        const cardImg = card.querySelector('.card__img');
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
    });
</script>


</html>
