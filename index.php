<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['submit'])){
  // Chunk size for reading the file (1MB chunks)
  $chunkSize = 1048576; // 1MB in bytes

  // Get file info
  $fileName = $_FILES['audioFile']['name'];
  $fileTmpName = $_FILES['audioFile']['tmp_name'];

$uploadDir = 'uploads/';
  if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
    }
$filePath = $uploadDir . $fileName;

  // Additional information
  $songName = $_POST['songName']; // Assuming you have a form field for song name
  $artistName = $_POST['artistName']; // Assuming you have a form field for artist name
  $audioType = pathinfo($fileName, PATHINFO_EXTENSION); // Get audio type from file extension

  // Prepare SQL query
  if (move_uploaded_file($_FILES['audioFile']['tmp_name'], $filePath)){
  $sql = "INSERT INTO songs (song_name, artist_name, song_path, audio_type) VALUES (?, ?, ?, ?)";
  }
  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $null = NULL;
$stmt->bind_param("ssss", $songName, $artistName, $filePath, $audioType);


  // Open and read the file in chunks
  if ($handle = fopen($fileTmpName, "rb")) {
    while (!feof($handle)) {
      // Read chunk
      $chunk = fread($handle, $chunkSize);
      // Bind the chunk to the parameter
      $stmt->send_long_data(2, $chunk); // Parameter index 2 corresponds to song_path
    }
    fclose($handle);
  }

  // Execute statement
  if($stmt->execute()) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $stmt->error;
  }
} else {
  echo "Please choose a file";
}

$conn->close();
?>
<!doctype html>

<form method="post" enctype="multipart/form-data">
  <label for="songName">Song Name:</label>
  <input type="text" name="songName" required><br>

  <label for="artistName">Artist Name:</label>
  <input type="text" name="artistName" required><br>

  <input type="file" name="audioFile" required><br>
  <input type="submit" name="submit" value="Upload">
</form>
</html>
