<?php
include('dbconnection.php');

$limitReached = false;


$countResult = mysqli_query($con, "SELECT COUNT(*) as total FROM images");
$countRow = mysqli_fetch_assoc($countResult);

if ($countRow['total'] >= 5) {
    $limitReached = true;
}


if (isset($_POST['submit'])) {
    $file_name = $_FILES['photo']['name'];
    $tempname = $_FILES['photo']['tmp_name'];

    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Upload failed. Error code: {$_FILES['photo']['error']}');</script>";
        exit();
    }

    $image_info = getimagesize($tempname);
    if ($image_info === false) {
        echo "<script>alert('The uploaded file is not a valid image.');</script>";
        exit();
    }

    $allowed_types = ['image/jpeg', 'image/png'];
    $file_type = mime_content_type($tempname);
    if (!in_array($file_type, $allowed_types)) {
        echo "<script>alert('Only JPEG and PNG files are allowed.');</script>";
        exit();
    }

    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_filename = uniqid('img_', true) . '.' . $ext;
    $folder = 'img/' . $new_filename;

    $uploadSuccess = false;


    if (move_uploaded_file($tempname, $folder)) {
        $stmt = $con->prepare("INSERT INTO images (file) VALUES (?)");
        $stmt->bind_param("s", $new_filename);
        $stmt->execute();


        $uploadSuccess = true;
    } else {
        echo "<script>alert('Failed to move uploaded image.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload your photo</title>

    <!--Font Awesome-->
    <link href="fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="fontawesome/css/brands.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="Css/upload.css" rel="stylesheet">
</head>

<body>

    <header class="header">

        <h2>Gallery Community</h2>

    </header>

    <div class="container">

        <h2>Upload your photo</h2>

        <form method="POST" enctype="multipart/form-data">

            <label for="photo" class="drop-area" id="drop-area">
                <div id="instructions">
                    <span id="drop-text">Drag & drop a photo here or click to choose</span><br>
                    <small>Supported formats: JPEG and PNG<br>
                        Maximum file size: 5MB</small>
                </div>
                <img id="preview" src="" alt="Preview">
            </label>

            <input type="file" id="photo" name="photo" accept="image/*" required>

            <button class="button-1" type="submit" name="submit"><i class="fa-solid fa-plus"></i> Upload Photo</button>
        </form>

        <button class="button-2" type="button" onclick="window.location='gallery.php'">View Gallery</button>
    </div>

    <script src="Js/upload.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($uploadSuccess) && $uploadSuccess): ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Your photo has been successfully submitted to the gallery.',
                icon: 'success',
                confirmButtonColor: '#a31621',
                confirmButtonText: 'OK',
                customClass: {
                    title: 'custom-swal-title',
                    htmlContainer: 'custom-swal-text'
                }
            });
        </script>
    <?php endif; ?>

    <?php if (isset($limitReached) && $limitReached): ?>
        <script>
            Swal.fire({
                title: 'Upload Limit Reached',
                text: 'You can only upload up to 5 photos.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#a31621',
                customClass: {
                    title: 'custom-limit-title',
                    htmlContainer: 'custom-limit-text'
                },
                didOpen: () => {
                    document.querySelector('.swal2-title').style.color = '#a31621';
                    document.querySelector('.swal2-title').style.fontFamily = 'Poppins, sans-serif';
                    document.querySelector('.swal2-title').style.fontSize = '24px';

                    document.querySelector('.swal2-html-container').style.color = '#a31621';
                    document.querySelector('.swal2-html-container').style.fontFamily = 'Open Sans, sans-serif';
                    document.querySelector('.swal2-html-container').style.fontSize = '16px';
                }
            }).then(() => {
                window.location.href = 'gallery.php';
            });
        </script>
    <?php endif; ?>


</body>

</html>