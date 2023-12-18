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

    playPauseButton.addEventListener('click', togglePlayPause);
    forwardButton.addEventListener('click', forward);
    backwardButton.addEventListener('click', backward);
    progressBar.addEventListener('input', updateProgress);

    document.getElementById('songName').textContent = songName;
    document.getElementById('artistName').textContent = artistName;

    function extractSongAndArtist(audioSrc) {

        const fileName = audioSrc.split('/').pop();

        const nameWithoutExtension = fileName.split('.')[0];
        const [song, artist] = nameWithoutExtension.split('--').map(str => str.trim());
        return [song, artist];
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
    xhr.onreadystatechange = function() {
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