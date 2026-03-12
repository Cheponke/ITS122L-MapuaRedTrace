/*
<?php
/*
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
*/

include "config.php";

if(isset($_POST['id']) && isset($_POST['status'])){

$id = $_POST['id'];
$status = $_POST['status'];

$query = "UPDATE users SET status='$status' WHERE id='$id'";

mysqli_query($conn,$query);

header("Location: staff-dashboard.php");

}


?>
