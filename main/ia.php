<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the first 5 audio files
$sqlAudio = "SELECT audio_path FROM audio_table ";
$resultAudio = $conn->query($sqlAudio);

$audioList = array();

if ($resultAudio->num_rows > 0) {
    while ($rowAudio = $resultAudio->fetch_assoc()) {
        $audioList[] = $rowAudio;
    }
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

    <?php foreach ($audioList as $index => $audio): ?>
        <div class="card">
            <div class="card__img">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"></svg>
            </div>
            <div class="card__title" id="songName">songName</div>
            <div class="card__subtitle" id="artistName">Artist</div>
            <div class="card__wrapper">
                <div class="card__time card__time-passed">00:00</div>
                <div class="card__timeline"><progress class="progress-bar" value="0" max="100"></progress></div>
                <div class="card__time card__time-left">00:00</div>
                <p>
                </p>
            </div>
            <div class="card__wrapper">
                <button class="card__btn backward"><svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.5 8V0L0 8L11.5 16V8ZM23 0L11.5 8L23 16V0Z"
                            fill="#fff"></path>
                    </svg></button>

                <button class="card__btn play-pause"><svg fill="#fff" height="22" viewBox="0 0 18 22" width="18"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="m0 0v22l18-11z"
                            fill="#000"></path>
                    </svg></button>
                <button class="card__btn forward"><svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.5 8V0L23 8L11.5 16V8ZM0 0L11.5 8L0 16V0Z"
                            fill="#fff"></path>
                    </svg></button>
            </div>
        </div>
        <audio id="audioPlayer <?= $index ?>" src="<?php echo $audio['audio_path']; ?>"></audio>
    <?php endforeach; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php foreach ($audioList as $index => $audio): ?>
            const audioPlayer<?= $index ?> = document.getElementById('audioPlayer<?= $index ?>');
            const playPauseButton<?= $index ?> = document.querySelector('.play-pause<?= $index ?>');
            const progressBar<?= $index ?> = document.querySelector('.progress-bar<?= $index ?>');
            const forwardButton<?= $index ?> = document.querySelector('.forward<?= $index ?>');
            const backwardButton<?= $index ?> = document.querySelector('.backward<?= $index ?>');
            const currentTimeDisplay<?= $index ?> = document.querySelector('.card__time-passed<?= $index ?>');
            const totalTimeDisplay<?= $index ?> = document.querySelector('.card__time-left<?= $index ?>');

            const [songName<?= $index ?>, artistName<?= $index ?>] = extractSongAndArtist('<?php echo $audio['audio_path']; ?>');

            playPauseButton<?= $index ?>.addEventListener('click', function () {
                togglePlayPause(audioPlayer<?= $index ?>, playPauseButton<?= $index ?>);
            });

            forwardButton<?= $index ?>.addEventListener('click', function () {
                forward(audioPlayer<?= $index ?>);
            });

            backwardButton<?= $index ?>.addEventListener('click', function () {
                backward(audioPlayer<?= $index ?>);
            });

            progressBar<?= $index ?>.addEventListener('input', function () {
                updateProgress(audioPlayer<?= $index ?>, progressBar<?= $index ?>);
            });

            document.getElementById('songName<?= $index ?>').textContent = songName<?= $index ?>;
            document.getElementById('artistName').textContent = artistName<?= $index ?>;

            const xhr<?= $index ?> = new XMLHttpRequest();
            xhr<?= $index ?>.onreadystatechange = function () {
                if (xhr<?= $index ?>.readyState === XMLHttpRequest.DONE) {
                    if (xhr<?= $index ?>.status === 200) {
                        const response<?= $index ?> = JSON.parse(xhr<?= $index ?>.responseText);
                        const imagePath<?= $index ?> = response<?= $index ?>.imagePath;
                        const cardImg<?= $index ?> = document.querySelector('.card__img<?= $index ?>');
                        cardImg<?= $index ?>.style.backgroundImage = "url('" + imagePath<?= $index ?> + "')";
                        cardImg<?= $index ?>.style.backgroundSize = "cover";
                        cardImg<?= $index ?>.style.backgroundPosition = "center";
                    } else {
                        console.error('Error getting image path:', xhr<?= $index ?>.status, xhr<?= $index ?>.statusText);
                    }
                }
            };
            xhr<?= $index ?>.open('POST', 'get_image_path.php', true);
            xhr<?= $index ?>.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr<?= $index ?>.send('songName=' + encodeURIComponent(songName<?= $index ?>));
        <?php endforeach; ?>

        function extractSongAndArtist(audioSrc) {
            const fileName = audioSrc.split('/').pop();
            const nameWithoutExtension = fileName.split('.')[0];
            const [song, artist] = nameWithoutExtension.split('--').map(str => str.trim());
            return [song, artist];
        }

        function togglePlayPause(audio, playPauseButton) {
            if (audio.paused) {
                playPauseButton.innerHTML = '<svg height="22px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="18px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M224,435.8V76.1c0-6.7-5.4-12.1-12.2-12.1h-71.6c-6.8,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6   C218.6,448,224,442.6,224,435.8z"/><path d="M371.8,64h-71.6c-6.7,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6c6.7,0,12.2-5.4,12.2-12.2V76.1   C384,69.4,378.6,64,371.8,64z"/></g></svg>';
                audio.play();
            } else {
                playPauseButton.innerHTML = '<svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg>';
                audio.pause();
            }
        }

        function forward(audio) {
            audio.currentTime += 5;
        }

        function backward(audio) {
            audio.currentTime -= 5;
        }

        function updateProgress(audio, progressBar) {
            const progress = parseInt(progressBar.value);
            const duration = audio.duration;
            const currentTime = (progress * duration) / 100;
            audio.currentTime = currentTime;
        }

        <?php foreach ($audioList as $index => $audio): ?>
            audioPlayer<?= $index ?>.addEventListener('timeupdate', function () {
                currentTimeDisplay<?= $index ?>.textContent = formatTime(audioPlayer<?= $index ?>.currentTime);
                var percentage<?= $index ?> = (audioPlayer<?= $index ?>.currentTime / audioPlayer<?= $index ?>.duration) * 100;
                progressBar<?= $index ?>.value = percentage<?= $index ?>;
                totalTimeDisplay<?= $index ?>.textContent = formatTime(audioPlayer<?= $index ?>.duration);
            });
            totalTimeDisplay<?= $index ?>.textContent = formatTime(audioPlayer<?= $index ?>.duration);
        <?php endforeach; ?>

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            const formattedTime = `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
            return formattedTime;
        }
    });
</script>
