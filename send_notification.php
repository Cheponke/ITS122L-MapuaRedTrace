<?php
session_start();
include 'config.php';

if($_SESSION['role'] != "staff"){
    header("Location: login.html");
    exit();
}

$type = $_POST['type'];
$title = $_POST['title'];
$message = $_POST['message'];
$priority = $_POST['priority'];
$recipients = $_POST['recipients'];

$sql = "INSERT INTO notifications(type,title,message,priority,recipients)
VALUES('$type','$title','$message','$priority','$recipients')";

$conn->query($sql);

header("Location: staff-notifications.php");
exit();
?>