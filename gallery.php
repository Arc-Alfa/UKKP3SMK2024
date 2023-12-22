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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" type="text/css" href="css/gallery.css">
    <!-- Include lightGallery styles -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/lightgallery@1.10.0/dist/css/lightgallery.min.css">
</head>

<body>

    <a href="logout.php">Logout</a>

    <form method="post" action="" enctype="multipart/form-data">
        <label>Upload Photo:</label>
        <input type="file" name="file" accept="image/*">
        <br>
        <label>Description:</label>
        <input type="text" name="description">
        <br>
        <input type="submit" value="Upload">
    </form>

    <h2>Gallery</h2>
    <div id="lightgallery">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo '<a href="uploads/' . $row['filename'] . '" data-sub-html="' . $row['description'] . '">';
            echo '<img src="uploads/' . $row['filename'] . '" alt="' . $row['description'] . '">';
            echo '</a>';
        }
        ?>
    </div>

    <!-- Include jQuery from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include lightGallery scripts from a CDN -->
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@1.10.0/dist/js/lightgallery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@1.10.0/dist/js/plugins/lg-thumbnail.min.js"></script>

    <script>
        // Initialize lightGallery
        $(document).ready(function () {
            $("#lightgallery").lightGallery({
                selector: 'a',
                thumbnail: true,
                download: false
            });
        });
    </script>
</body>

</html>