<?php
include "config.php";

if (isset($_POST['inventory_id'])) {
    // Sanitize input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_POST['inventory_id']);


    $sql = "DELETE FROM inventory WHERE InventoryID = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo mysqli_error($conn);
    }
} else {
    echo "No ID provided.";
}

mysqli_close($conn);
?>