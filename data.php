<?php require 'connection.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data</title>
</head>
<body>
    <table border=1 cellspacing=0 cellpadding=10>
        <tr>
            <td>#</td>
            <td>Name</td>
            <td>Image</td>
        </tr>
        <?php
        $i = 1;
        $rows = mysqli_query($conn, "SELECT * FROM tb_upload ORDER BY id DESC");
        ?>
        <?php foreach($rows as $row) : ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row["Name"]; ?></td>
            <td>
                <img src="img/<?php echo $row['Image']; ?>" width="200" title="<?php echo $row['Image']; ?>">
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="../index.php">Upload Image File</a>
</body>
</html>