<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $audioType = $_FILES['audioFile']['type'];
    $audioFileName = $_FILES['audioFile']['name'];
    $uploadDirectory = 'audio_uploads/'; // Directory where audio files will be stored

    // Move the uploaded file to a designated directory
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true); // Create the directory if it doesn't exist
    }

    $audioPath = $uploadDirectory . $audioFileName;

    if (move_uploaded_file($_FILES['audioFile']['tmp_name'], $audioPath)) {
        $sql = "INSERT INTO audio_table (audio_path, audio_type) VALUES ('$audioPath', '$audioType')";

        if ($conn->query($sql) === TRUE) {
            echo "Audio uploaded successfully";
             header('Location: getaudio.html');
            // Redirect or display a success message
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "File upload failed";
    }
}

$conn->close();
?>
