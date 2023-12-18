   <?php
   $servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: *");

// AJAX REQ
$songName = $_POST['songName'];


$stmt = $conn->prepare("SELECT image_path FROM image_table WHERE image_name = ?");
$stmt->bind_param("s", $songName);
$stmt->execute();
if ($stmt->error) {
    echo "SQL Error: " . $stmt->error;
}
$stmt->bind_result($imagePath);



if ($stmt->fetch()) {
    echo json_encode(array('imagePath' => $imagePath));
} else {
    echo "image path not found";
}

$stmt->close();
$conn->close();
?>