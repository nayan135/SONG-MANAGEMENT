// Replace with your actual PHP script URL
const fetchCoverScript = 'fetch.php';

// DOM elements
const audioPlayer = document.getElementById('audioPlayer');
const playPauseButton = document.querySelector('.play-pause');
const backwardButton = document.querySelector('.backward');
const forwardButton = document.querySelector('.forward');
const progressBar = document.querySelector('.progress-bar');
const currentTimeDisplay = document.querySelector('.card__time-passed');
const totalTimeDisplay = document.querySelector('.card__time-left');
const card = document.getElementById('sdiv');
const closeButton = document.querySelector('.close-button');
const songNameElement = document.getElementById('songName');
const artistNameElement = document.getElementById('artistName');
const cardImgElement = document.getElementById('cardImg');

let isPlaying = false;

function showCard(encodedSongPath, songName, artistName, imagePath) {
    // Decode the path and set the audio source
    const decodedSongPath = decodeURIComponent(encodedSongPath);
    const source = document.createElement('source');
    source.src = decodedSongPath;
    source.type = 'audio/mp3'; // Adjust based on your audio format
    audioPlayer.innerHTML = ''; // Clear previous source elements
    audioPlayer.appendChild(source);

    // Update card content
    songNameElement.textContent = songName;
    artistNameElement.textContent = artistName;
    cardImgElement.style.backgroundImage = `url(${imagePath})`;

    // Load, play, and display the song
    audioPlayer.load();
    audioPlayer.play().catch(error => console.error('Play failed:', error));
    card.style.display = 'block';

    // Update progress bar and time displays
    updateProgress();
    audioPlayer.addEventListener('loadedmetadata', () => {
        currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
        totalTimeDisplay.textContent = formatTime(audioPlayer.duration);
        updateProgress(); // Start updating progress regularly
    });
}

// ... (other functions and event listeners remain the same)

// Function to close the song card
function closeCard() {
    card.style.display = 'none';
    audioPlayer.pause();
    audioPlayer.currentTime = 0; // Reset playback
} // Function to show a song card with details and cover image

// Event listeners for audio player controls
playPauseButton.addEventListener('click', togglePlay);
backwardButton.addEventListener('click', backward);
forwardButton.addEventListener('click', forward);
audioPlayer.addEventListener('timeupdate', updateProgress);
progressBar.addEventListener('input', seek);
audioPlayer.addEventListener('play', () => {
    isPlaying = true;
    playPauseButton.innerHTML = '<span class="material-symbols-outlined"> play_arrow</span>'; // Play icon
});
audioPlayer.addEventListener('pause', () => {
    isPlaying = false;
    playPauseButton.innerHTML = '<svg...>'; // Pause icon
});

// Functions for playback control and progress
function togglePlay() {
    if (isPlaying) {
        audioPlayer.pause();
    } else {
        audioPlayer.play().catch(error => console.error('Play failed:', error));
    }
}

function backward() {
    audioPlayer.currentTime -= 5;
}

function forward() {
    audioPlayer.currentTime += 5;
}

function updateProgress() {
    const percentage = (audioPlayer.currentTime / audioPlayer.duration) * 100;
    progressBar.value = percentage;
}

function seek() {
    const progress = parseInt(progressBar.value);
    const duration = audioPlayer.duration;
    const currentTime = (progress * duration) / 100;
    audioPlayer.currentTime = currentTime;
}