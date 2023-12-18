<!DOCTYPE html>
<html>

<head>
    <title>Play Audio</title>
    <link rel="stylesheet" href="newww.css">
</head>

<body>
    <div id="audioPlayers"></div>

    <script>
        fetch('get_audio_path.php') // Replace with the endpoint to fetch all audio files
            .then(response => response.json()) // Assuming the response is in JSON format
            .then(audioFiles => {
                const audioPlayersDiv = document.getElementById('audioPlayers');
                audioFiles.forEach(file => {
                    // Create card container
                    const cardDiv = document.createElement('div');
                    cardDiv.className = 'card';

                    // Create audio player
                    const audioPlayer = document.createElement('audio');

                    const source = document.createElement('source');
                    source.src = file.audio_path;
                    audioPlayer.appendChild(source);

                    // Add audio player to card
                    cardDiv.appendChild(audioPlayer);

                    // Add song name and artist to card
                    const songNameDiv = document.createElement('div');
                    songNameDiv.className = 'card__title';
                    songNameDiv.innerText = 'Song Name';
                    cardDiv.appendChild(songNameDiv);

                    const artistNameDiv = document.createElement('div');
                    artistNameDiv.className = 'card__subtitle';
                    artistNameDiv.innerText = 'Artist';
                    cardDiv.appendChild(artistNameDiv);

                    // Add progress bar and control buttons to card
                    const cardWrapperDiv = document.createElement('div');
                    cardWrapperDiv.className = 'card__wrapper';

                    const timePassedDiv = document.createElement('div');
                    timePassedDiv.className = 'card__time card__time-passed';
                    timePassedDiv.innerText = '00:00';
                    cardWrapperDiv.appendChild(timePassedDiv);

                    const progressBar = document.createElement('progress');
                    progressBar.className = 'progress-bar';
                    progressBar.value = 0;
                    progressBar.max = 100;
                    cardWrapperDiv.appendChild(progressBar);

                    const timeLeftDiv = document.createElement('div');
                    timeLeftDiv.className = 'card__time card__time-left';
                    timeLeftDiv.innerText = '00:00';
                    cardWrapperDiv.appendChild(timeLeftDiv);

                    const backwardBtn = document.createElement('button');
                    backwardBtn.className = 'card__btn backward';
                    backwardBtn.innerHTML = '<svg width="23" height="16" viewBox="0 0 23 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 8V0L0 8L11.5 16V8ZM23 0L11.5 8L23 16V0Z" fill="#fff"></path></svg>';
                    cardWrapperDiv.appendChild(backwardBtn);

                    const playPauseBtn = document.createElement('button');
                    playPauseBtn.className = 'card__btn play-pause';
                    playPauseBtn.innerHTML = '<svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg>';
                    cardWrapperDiv.appendChild(playPauseBtn);

                    const forwardBtn = document.createElement('button');
                    forwardBtn.className = 'card__btn forward';
                    forwardBtn.innerHTML = '<svg width="23" height="16" viewBox="0 0 23 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 8V0L23 8L11.5 16V8ZM0 0L11.5 8L0 16V0Z" fill="#fff"></path></svg>';
                    cardWrapperDiv.appendChild(forwardBtn);

                    // Add wrapper to card
                    cardDiv.appendChild(cardWrapperDiv);

                    // Add card to the main container
                    audioPlayersDiv.appendChild(cardDiv);
                });
            })
            .catch(error => console.error('Error fetching audio files:', error));

    </script>
    <script src="js.js"></script>
</body>

</html>
