<!DOCTYPE html>
<html>
<head>
    <title>Play Audio</title>
</head>
<body>
    <audio controls>
        <source src="play_audios.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>" type="audio">
        Your browser does not support the audio element.
    </audio>

    <?php
    include 'config.php';

    if (isset($_GET['id'])) {
        $audioId = $_GET['id'];

        $result = $conn->query("SELECT audio_path, audio_type FROM audio_table WHERE id = $audioId");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $audioPath = $row['audio_path'];
            $audioType = $row['audio_type'];

            // Set the appropriate content type for the audio file
            header("Content-type: $audioType");

            // Output the audio file for playback
            readfile($audioPath);
        } else {
            echo "Audio not found";
        }
    }

    $conn->close();
    ?>
</body>
</html>
