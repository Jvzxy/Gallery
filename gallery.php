<?php
include('dbconnection.php');

// Count total images
$countResult = mysqli_query($con, "SELECT COUNT(*) as total FROM images");
$countRow = mysqli_fetch_assoc($countResult);


if(isset($_POST['submit'])) {
    $file_name = $_FILES['photo']['name'];
    $tempname = $_FILES['photo']['tmp_name'];
    $folder = 'img/'.$file_name;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>

    <link rel="stylesheet" href="Css/gallery.css">
</head>

<body>




    <div class="gallery-header">
        <h1>Community Gallery</h1>
        <p>Share and explore photos from our community!</p>
        <a href="upload.php">Upload Your Photo</a>
    </div>

    <div class="gallery-container">

        <div id="gallery">
            <?php 
                $result = mysqli_query($con, "SELECT * FROM images ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($result)) {
                    $filepath = 'img/' . $row['file'];
                    if (file_exists($filepath) && !empty($row['file'])) {
                        echo '<div class="gallery-item">';
                        echo '<img src="' . htmlspecialchars($filepath) . '" alt="Photo">';
                        echo '</div>';
                    }
                }
            ?>
        </div>
    </div>

</body>

</html>