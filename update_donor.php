<?php
include "config.php";

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$blood = $_POST['blood_type'];

$names = explode(" ",$name);
$first = $names[0];
$last = $names[1];

$sql = "UPDATE users 
SET first_name='$first',
last_name='$last',
email='$email',
blood_type='$blood'
WHERE id='$id'";

mysqli_query($conn,$sql);

header("Location: staff-dashboard.php");
?>