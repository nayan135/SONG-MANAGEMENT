<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link rel="stylesheet" href="newww.css">
</head>

<body>
    <button id="1">Get Song 1</button>
    <button id="2">Get Song 2</button>

    <div id="audioContainer"></div>



    <div class="card" id="sdiv">
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

            <button class="card__btn play-pause">  <svg fill="#fff" height="22" viewBox="0 0 18 22" width="18" xmlns="http://www.w3.org/2000/svg"><path d="m0 0v22l18-11z" fill="#000"></path></svg></button>
            <button class="card__btn forward"><svg width="23" height="16" viewBox="0 0 23 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 8V0L23 8L11.5 16V8ZM0 0L11.5 8L0 16V0Z" fill="#fff"></path></svg></button>
        </div>
    </div>
    <audio id="audioPlayer" src="<?php echo $audioPath; ?>"></audio>
    <script src="script.js"></script>
    <script>
        document.getElementById("1").addEventListener("click", function() {
            getAudio("1");
        });

        document.getElementById("2").addEventListener("click", function() {
            getAudio("2");
        });

        function getAudio(audioName) {
            // Make an asynchronous request to the server
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Parse the JSON response
                    var audioFiles = JSON.parse(this.responseText);
                    // Find the audio file with the matching ID
                    var selectedAudio = audioFiles.find(function(audio) {
                        return audio.id === audioName;
                    });
                    // Display the audio file path
                    document.getElementById("audioPlayer").innerHTML = '<source src="' + selectedAudio.audio_path + '" ';
                }
            };
            xhr.open("GET", "get_audio_path.php", true);
            xhr.send();
        }
    </script>
</body>

</html>