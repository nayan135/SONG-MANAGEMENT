<?php
include 'config.php';

if(isset($_POST['submit'])) {
    if (!$conn->ping()) {
        $conn = new mysqli($servername, $username, $password, $dbname);
    }

    $audioData = file_get_contents($_FILES['audioFile']['tmp_name']);
    $audioData = mysqli_real_escape_string($conn, $audioData);
    $audioType = $_FILES['audioFile']['type'];

    $sql = "INSERT INTO audio_table (audio, audio_type) VALUES ('$audioData', '$audioType')";

    if ($conn->query($sql) === TRUE) {
        echo "Audio uploaded successfully";
        // Redirect to a new page after successful upload
        header("Location: play.html");
        exit; // Ensure to stop script execution after header redirection
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>