<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    echo "error";
    exit();
}

$user_id = $_SESSION['user_id'];

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$birthday = $_POST['birthday'];
$gender = $_POST['gender'];
$blood_type = $_POST['blood_type'];
$weight = $_POST['weight'];
$street_address = $_POST['street_address'];
$city = $_POST['city'];
$contact_name = $_POST['contact_name'];
$contact_phone = $_POST['contact_phone'];

$sql = "UPDATE users SET 
first_name='$first_name',
last_name='$last_name',
email='$email',
phone_number='$phone_number',
birthday='$birthday',
gender='$gender',
blood_type='$blood_type',
weight='$weight',
street_address='$street_address',
city='$city',
contact_name='$contact_name',
contact_phone='$contact_phone'
WHERE id='$user_id'";

if($conn->query($sql)){
    echo "Profile Update Sucessfully";
} else {
    echo "Error updating profile";
}
?>