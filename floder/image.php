<?php

include 'config.php';

if (isset($_POST['submit'])) {
    $imageName = $_POST['imageName'];  // Add this line to get the image name from the form
    $imageFileName = $_FILES['imageFile']['name'];
    $targetPath = "image_uploads/" . basename($imageFileName);

    // Check if the file already exists
    if (file_exists($targetPath)) {
        echo "File already exists.";
    } else {
        move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetPath);
        header("Location: uploading.php");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Image Upload</title>
</head>

<body>
    <form action="image.php" method="post" enctype="multipart/form-data">
        <label for="imageName">Image Name:</label>
        <input type="text" name="imageName" required><br>
        <input type="file" name="imageFile">
        <input type="submit" name="submit" value="Upload">
    </form>
</body>

</html>

<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $imageName = $_POST['imageName'];  // Add this line to get the image name from the form
    $imageType = $_FILES['imageFile']['type'];
    $imageFileName = $_FILES['imageFile']['name'];
    $uploadDirectory = 'image_uploads/'; // Directory where image files will be stored

    // Move the uploaded file to a designated directory
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true); // Create the directory if it doesn't exist
    }

    $imagePath = $uploadDirectory . $imageFileName;

    if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $imagePath)) {
        $sql = "INSERT INTO image_table (image_name, image_path, image_type) VALUES ('$imageName', '$imagePath', '$imageType')";

        if ($conn->query($sql) === TRUE) {
            echo "Image uploaded successfully";
       
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
