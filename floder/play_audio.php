<?php
include 'config.php';

if (isset($_GET['id'])) {
    $audioId = $_GET['id'];

    $result = $conn->query("SELECT audio_path, audio_type FROM audio_table WHERE id = $audioId");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $audioPath = $row['audio_path'];
        $audioType = $row['audio_type'];

        header("Content-type: $audioType");
        readfile($audioPath);
    } else {
        echo "Audio not found";
    }
}

$conn->close();
?>
