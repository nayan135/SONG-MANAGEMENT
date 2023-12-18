fetch('test.php') // Retrieves the list of audio files
    .then(response => response.text()) // Response should be JSON
    .then(data => {
        const audioListDiv = document.getElementById('audioList');
        const audioData = JSON.parse(data); // Parse the JSON data

        audioData.forEach(audio => {
            const audioPlayer = document.createElement('audio');
            audioPlayer.controls = true;

            const source = document.createElement('source');
            source.src = audio.audio_path;
            source.type = audio.audio_type;

            audioPlayer.appendChild(source);
            audioListDiv.appendChild(audioPlayer);

            const audioName = document.createElement('p');
            audioName.textContent = audio.audio_name;
            audioListDiv.appendChild(audioName);
        });
    })
    .catch(error => console.error('Error fetching audio list:', error));