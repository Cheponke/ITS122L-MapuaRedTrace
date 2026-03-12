<?php
include "config.php";

if(isset($_POST['donor_id'])){

$donor_id = $_POST['donor_id'];
$blood_pressure = $_POST['blood_pressure'];
$temperature = $_POST['temperature'];
$pulse_rate = $_POST['pulse_rate'];
$weight = $_POST['weight'];
$hemoglobin_level = $_POST['hemoglobin_level'];
$location = $_POST['location']; 

$sql = "INSERT INTO screenings 
(donor_id, blood_pressure, temperature, pulse_rate, weight, hemoglobin_level, location)
VALUES 
('$donor_id','$blood_pressure','$temperature','$pulse_rate','$weight','$hemoglobin_level','$location')";

if(mysqli_query($conn,$sql)){
    header("Location: staff-dashboard.php?success=1");
}else{
    echo "Error: " . mysqli_error($conn);
}

}
?>