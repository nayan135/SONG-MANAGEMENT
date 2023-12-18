document.addEventListener('DOMContentLoaded', function() {
    const audioCardsDiv = document.getElementById('audioCards');

    // Fetch all audio files from the server
    fetch('get_audio_path.php')
        .then(response => response.json())
        .then(audioFiles => {
            // Iterate through each audio file and create a card
            audioFiles.forEach(file => {
                const card = createAudioCard(file);
                audioCardsDiv.appendChild(card);
            });
        })
        .catch(error => console.error('Error fetching audio files:', error));

    function createAudioCard(audioFile) {
        const card = document.createElement('div');
        card.className = 'card';

        const cardImg = document.createElement('div');
        cardImg.className = 'card__img';

        // Create an SVG element
        const svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svgElement.setAttribute("xmlns", "http://www.w3.org/2000/svg");
        svgElement.setAttribute("viewBox", "0 0 128 128");

        // Append the SVG element to the card image div
        cardImg.appendChild(svgElement);

        // Append the card image div to the card (assuming you have a variable named 'card' referencing the card element)
        card.appendChild(cardImg);

        // You can customize the card content based on your requirements
        const cardTitle = document.createElement('div');
        cardTitle.className = 'card__title';
        cardTitle.textContent = 'Song Name'; // Customize as needed
        card.appendChild(cardTitle);

        const cardSubtitle = document.createElement('div');
        cardSubtitle.className = 'card__subtitle';
        cardSubtitle.textContent = 'Artist'; // Customize as needed
        card.appendChild(cardSubtitle);

        const audioPlayer = document.createElement('audio');

        const source = document.createElement('source');
        source.src = audioFile.audio_path;
        audioPlayer.appendChild(source);

        card.appendChild(audioPlayer);

        // Create the first card wrapper
        const cardWrapper1 = document.createElement('div');
        cardWrapper1.className = 'card__wrapper';

        // Create the time display for time passed
        const timePassedDisplay = document.createElement('div');
        timePassedDisplay.className = 'card__time card__time-passed';
        timePassedDisplay.textContent = '00:00';
        cardWrapper1.appendChild(timePassedDisplay);

        // Create the progress bar
        const progressBar = document.createElement('progress');
        progressBar.className = 'progress-bar';
        progressBar.value = 0;
        progressBar.max = 100;
        cardWrapper1.appendChild(progressBar);

        // Create the time display for time left
        const timeLeftDisplay = document.createElement('div');
        timeLeftDisplay.className = 'card__time card__time-left';
        timeLeftDisplay.textContent = '00:00';
        cardWrapper1.appendChild(timeLeftDisplay);

        // Append the first card wrapper to the card
        card.appendChild(cardWrapper1);

        // Create the second card wrapper
        const cardWrapper2 = document.createElement('div');
        cardWrapper2.className = 'card__wrapper';

        // Create the backward button
        const backwardButton = document.createElement('button');
        backwardButton.className = 'card__btn backward';
        backwardButton.innerHTML = '<svg width="23" height="16" viewBox="0 0 23 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 8V0L0 8L11.5 16V8ZM23 0L11.5 8L23 16V0Z" fill="#fff"></path></svg>';
        cardWrapper2.appendChild(backwardButton);

        // Create the play-pause button
        const playPauseButton = document.createElement('button');
        playPauseButton.className = 'card__btn play-pause';
        playPauseButton.innerHTML = '<svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg>';
        cardWrapper2.appendChild(playPauseButton);

        // Create the forward button
        const forwardButton = document.createElement('button');
        forwardButton.className = 'card__btn forward';
        forwardButton.innerHTML = '<svg width="23" height="16" viewBox="0 0 23 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 8V0L23 8L11.5 16V8ZM0 0L11.5 8L0 16V0Z" fill="#fff"></path></svg>';
        cardWrapper2.appendChild(forwardButton);

        // Append the second card wrapper to the card
        card.appendChild(cardWrapper2);

        // Add event listeners for buttons
        playPauseButton.addEventListener('click', () => togglePlayPause(audioPlayer, playPauseButton));
        forwardButton.addEventListener('click', () => forward(audioPlayer));
        backwardButton.addEventListener('click', () => backward(audioPlayer));
        progressBar.addEventListener('input', () => updateProgress(audioPlayer, progressBar));

        // Function to format time
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            const formattedTime = `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
            return formattedTime;
        }

        function togglePlayPause(player, button) {
            if (player.paused) {
                button.innerHTML = '<svg height="22px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="18px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M224,435.8V76.1c0-6.7-5.4-12.1-12.2-12.1h-71.6c-6.8,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6   C218.6,448,224,442.6,224,435.8z"/><path d="M371.8,64h-71.6c-6.7,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6c6.7,0,12.2-5.4,12.2-12.2V76.1   C384,69.4,378.6,64,371.8,64z"/></g></svg>';
                player.play();
            } else {
                button.innerHTML = '<svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg>';
                player.pause();
            }
        }

        function forward(player) {
            player.currentTime += 5;
        }

        function backward(player) {
            player.currentTime -= 5;
        }

        function updateProgress(player, progressBar) {
            const progress = parseInt(progressBar.value);
            const duration = player.duration;

            const currentTime = (progress * duration) / 100;
            player.currentTime = currentTime;
        }

        return card;
    }
});