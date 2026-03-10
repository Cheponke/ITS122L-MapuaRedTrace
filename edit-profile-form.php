<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();
?>

<form action="edit_profile.php" method="POST">
    <label>First Name</label>
    <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>

    <label>Last Name</label>
    <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

    <label>Phone</label>
    <input type="text" name="phone_number" value="<?php echo $user['phone_number']; ?>">

    <label>Birthday</label>
    <input type="date" name="birthday" value="<?php echo $user['birthday']; ?>">

    <label>Gender</label>
    <select name="gender">
        <option value="Male" <?php if($user['gender']=='Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if($user['gender']=='Female') echo 'selected'; ?>>Female</option>
        <option value="Other" <?php if($user['gender']=='Other') echo 'selected'; ?>>Other</option>
    </select>

    <label>Blood Type</label>
    <input type="text" name="blood_type" value="<?php echo $user['blood_type']; ?>">

    <label>Weight</label>
    <input type="number" name="weight" value="<?php echo $user['weight']; ?>">

    <label>Street Address</label>
    <input type="text" name="street_address" value="<?php echo $user['street_address']; ?>">

    <label>City</label>
    <input type="text" name="city" value="<?php echo $user['city']; ?>">

    <label>Emergency Contact Name</label>
    <input type="text" name="contact_name" value="<?php echo $user['contact_name']; ?>">

    <label>Emergency Contact Phone</label>
    <input type="text" name="contact_phone" value="<?php echo $user['contact_phone']; ?>">

    <button type="submit">Update Profile</button>
</form>