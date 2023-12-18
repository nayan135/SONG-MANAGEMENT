<!DOCTYPE html>
<html>
<head>
    <title>Play Audio</title>
</head>
<body>
    <div id="audioList"></div>



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

    $result = $conn->query("SELECT id, audio_path, audio_type FROM audio_table");

    $audioFiles = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $audioFiles[] = array(
                'id' => $row['id'],
                'audio_path' => $row['audio_path'],
                'audio_type' => $row['audio_type'],
            );
        }
    }

    echo json_encode($audioFiles);

    $conn->close();
    ?>
    <script src="text.js"></script>
</body>
</html>
