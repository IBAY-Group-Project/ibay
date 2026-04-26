<?php
require 'connection.php';

if (isset($_POST["submit"])) {
    $name = $_POST["name"];

    // Check if image is uploaded
    if ($_FILES["image"]["error"] === 4) {
        echo "<script> alert('Image Does Not Exist'); </script>";
    } else {
        $fileName  = $_FILES["image"]["name"];
        $fileSize  = $_FILES["image"]["size"];
        $tempName  = $_FILES["image"]["tmp_name"];

        // Debugging: Print file details
        echo "Temporary file: $tempName<br>";
        echo "File name: $fileName<br>";
        echo "File size: $fileSize bytes<br>";

        // Check file extension
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension      = explode('.', $fileName);
        $imageExtension      = strtolower(end($imageExtension));

        // Validate image extension
        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script> alert('Invalid Image Extension'); </script>";
        // Check file size
        } else if ($fileSize > 1000000) {
            echo "<script> alert('Image Size Too Large'); </script>";
        } else {
            // Generate new image name
            $newImageName = uniqid() . '_' . bin2hex(random_bytes(5)) . '.' . $imageExtension;

            // Ensure the img/ directory exists
            if (!is_dir('img')) {
                mkdir('img', 0755, true);
            }

            // Move the uploaded file
            $destination = __DIR__ . '/img/' . $newImageName;
            echo "Destination: $destination<br>";

            if (move_uploaded_file($tempName, $destination)) {
                // Prepare SQL query
                $query = "INSERT INTO tb_upload (name, image) VALUES (?, ?)";
                $stmt  = mysqli_prepare($conn, $query);

                if ($stmt) {
                    // Bind parameters to the query
                    mysqli_stmt_bind_param($stmt, "ss", $name, $newImageName);

                    // Execute the query
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<script>
                            alert('Successfully Added');
                            document.location.href = 'data.php';
                        </script>";
                    } else {
                        echo "<script> alert('Database Error: " . mysqli_error($conn) . "'); </script>";
                    }

                    // Close the statement
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<script> alert('Failed to Prepare Statement'); </script>";
                }
            } else {
                echo "<script> alert('Failed to upload image. Error: " . error_get_last()['message'] . "'); </script>";
            }
        }
    }
}