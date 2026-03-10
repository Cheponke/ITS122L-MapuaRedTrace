<?php
header('Content-Type: application/json');
include "config.php";

$donor_id = $_GET['donor_id'];

$sql = "SELECT * FROM screenings 
        WHERE donor_id='$donor_id' 
        ORDER BY id DESC 
        LIMIT 1";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result) > 0){

$row = mysqli_fetch_assoc($result);

$bp = explode("/", $row['blood_pressure']);

echo json_encode([
    "status" => "done",
    "systolic" => $bp[0],
    "diastolic" => $bp[1],
    "pulse" => $row['pulse_rate'],
    "hemoglobin" => $row['hemoglobin_level'],
    "weight" => $row['weight']
]);

}else{

echo json_encode([
    "status" => "waiting"
]);

}
?>