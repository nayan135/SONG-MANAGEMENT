<?php
include 'config.php';

$result = $conn->query("SELECT id, audio_path, audio_type, audio_name FROM audio_table");

$audioFiles = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $audioFiles[] = array(
            'id' => $row['id'],
            'audio_path' => $row['audio_path'],
            'audio_type' => $row['audio_type'],
            'audio_name' => $row['audio_name']
        );
    }
}

echo json_encode($audioFiles);

$conn->close();
?>
