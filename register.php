<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$firstname = $_POST['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

$bday = $_POST['birthday'] ?? '';
$gender = $_POST['gender'] ?? '';
$bloodtype = $_POST['bloodtype'] ?? '';
$weight = $_POST['weight'] ?? '';

$medical_conditions = $_POST['medical_conditions'] ?? '';
$current_medications = $_POST['current_medications'] ?? '';

$street_address = $_POST['street_address'] ?? '';
$city = $_POST['city'] ?? '';
$zip = $_POST['zip'] ?? '';

$contact_name = $_POST['contact_name'] ?? '';
$contact_phone = $_POST['contact_phone'] ?? '';

$role = "donor";

$sql = "INSERT INTO users 
(first_name, last_name, email, role, phone_number, password, birthday, gender, blood_type, weight, medical_condition, current_medication, street_address, city, zip, contact_name, contact_phone)
VALUES
('$firstname','$lastname','$email','$role','$phone','$password','$bday','$gender','$bloodtype','$weight','$medical_conditions','$current_medications','$street_address','$city','$zip','$contact_name','$contact_phone')";

if ($conn->query($sql) === TRUE) {
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

}