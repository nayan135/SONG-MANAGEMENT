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