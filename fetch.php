
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if song_name is set in the GET parameters
$songName = isset($_GET['song_name']) ? $_GET['song_name'] : null;

if ($songName === null) {
    // If song_name is not provided, return an error response
    $data['success'] = false;
    $data['error'] = "Song name not provided";
    echo json_encode($data);
    exit;
}

// Query database for cover image path
$sql = "SELECT image_path FROM songs WHERE song_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $songName);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $data['success'] = true;
    $data['cover_path'] = $row['image_path'];
} else {
    $data['success'] = false;
    $data['error'] = "Song cover image not found";
}

echo json_encode($data);

// Close connections
$stmt->close();
$conn->close();

?>
