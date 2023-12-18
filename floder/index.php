<?php


 include 'config1.php';
if (isset($_POST['submit'])) {
    $audioFileName = $_FILES['audioFile']['name'];
    $targetPath = "audio_uploads/" . basename($audioFileName);

    // Check if the file already exists
    if (file_exists($targetPath)) {
        echo "File already exists.";
    } else {
        move_uploaded_file($_FILES['audioFile']['tmp_name'], $targetPath);
        header("Location: uploading.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Audio Upload</title>
</head>
<body>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <input type="file" name="audioFile">
        <input type="submit" name="submit" value="Upload">
    </form>
</body>
</html>
