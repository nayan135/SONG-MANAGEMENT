<?php

include 'config.php';

// Query to retrieve all audio files
$sql = "SELECT id, audio_path FROM audio_table"; // Change audio_table to your table name

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $audioFiles = array();
    while ($row = $result->fetch_assoc()) {
        $audioFiles[] = $row;
    }
    echo json_encode($audioFiles); // Return the data as JSON
} else {
    echo "0 results";
}
$conn->close();
?>
