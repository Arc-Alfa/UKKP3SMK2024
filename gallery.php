<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $filename = basename($_FILES["file"]["name"]);

        $sql = "INSERT INTO photos (user_id, filename, description) VALUES ('$user_id', '$filename', '$description')";
        $result = $conn->query($sql);

        if ($result) {
            echo 'Photo uploaded successfully.';
        } else {
            echo 'Upload failed.';
        }
    } else {
        echo 'Error uploading file.';
    }
}

$sql = "SELECT * FROM photos WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>
<link rel="stylesheet" type="text/css" href="css/gallery.css">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
</head>
<body>
    <h2>Gallery</h2>
    <a href="logout.php">Logout</a>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Description:</label>
        <input type="text" name="description">
        <br>
        <label>Upload Photo:</label>
        <input type="file" name="file" accept="image/*">
        <br>
        <input type="submit" value="Upload">
    </form>
    <br>

    <?php
    while ($row = $result->fetch_assoc()) {
        echo '<img src="uploads/' . $row['filename'] . '" alt="' . $row['description'] . '" style="max-width: 200px; margin: 10px;">';
    }
    ?>
</body>
</html>
