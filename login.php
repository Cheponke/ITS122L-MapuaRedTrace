<?php
session_start();
include 'config.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {    

    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['first_name'];
        $_SESSION['role'] = $row['role'];

        // Redirect based on role
        if ($row['role'] == "staff") {

            echo "
            <script>
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('userRole', 'staff');
            window.location.href='staff-dashboard.php';
            </script>
            ";

        } else {

            echo "
            <script>
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('userRole', 'donor');
            window.location.href='index.html';
            </script>
            ";

        }

        exit();

    } else {
        echo "Invalid password";
    }

} else {
    echo "User not found";
}
?>