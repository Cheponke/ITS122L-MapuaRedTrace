<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$blood_type = $user['blood_type'] ?? 'O+';
$status = $user['status'];

$blood_labels = [
    'O-'  => 'Universal donor type',
    'O+'  => 'Most common blood type',
    'A-'  => '',
    'A+'  => '',
    'B-'  => '',
    'B+'  => '',
    'AB-' => 'Rarest blood type',
    'AB+' => 'Universal recipient type'
];

$blood_label = $blood_labels[$blood_type] ?? '';

// count total donations of this donor
$donation_query = "SELECT COUNT(*) as total FROM screenings WHERE donor_id='$user_id'";
$donation_result = mysqli_query($conn,$donation_query);
$donation_data = mysqli_fetch_assoc($donation_result);

$total_donations = $donation_data['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard | Mapúa RedTrace</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="help-page">
    <header id="main-header">
        <nav class="navbar">
            <div class="logo">
                <a href="index.php">
                    <img src="mapua-logo.png" alt="Mapúa Logo"> 
                    <span>Mapúa RedTrace</span>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="help.php">Help/Policies</a></li>
                <li><a href="notifications.php">Notifications</a></li>
            </ul>
        </div>
        </nav>
    </header>

    <main class="dashboard-container">
        <section class="donor-profile-header">
            <div class="profile-info-main">
                <div class="profile-avatar">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div class="profile-meta">
                    <h1><?php echo $user['first_name'] . " " . $user['last_name']; ?></h1>
                    <p>Donor ID: D<?php echo $user['id']; ?></p>
                </div>
            </div>
           <a href="logout.php" class="btn btn-white btn-signout" style="text-decoration:none;">
            <i class="fa-solid fa-right-from-bracket"></i> Sign Out
            </a>
        </section>

        <div class="notif-stats-grid">
            <div class="notif-stat-card">
                <div class="icon-box red-light"><i class="fa-solid fa-droplet"></i></div>
                <div class="stat-info">
                    <span class="count"><?php echo $total_donations; ?></span>
                    <span class="label">Total Donations</span>
                    <small>You've saved 24 lives</small>
                </div>
            </div>
            <div class="notif-stat-card">
                <div class="icon-box orange-light"><i class="fa-solid fa-heart-pulse"></i></div>
                <div class="stat-info">
                    <span class="count"><?php echo $blood_type; ?></span>
                    <span class="label">Blood Type</span>
                    <small><?php echo $blood_label; ?></small>
                </div>
            </div>
            <div class="notif-stat-card">
                <div class="icon-box green-light"><i class="fa-solid fa-user-check"></i></div>
                <div class="stat-info">
                <?php
    if($status == "active"){
        echo "<span class='count'>Active</span>";
    }
    elseif($status == "pending"){
        echo "<span class='count'>Pending</span>";
    }
    else{
        echo "<span class='count'>Inactive</span>";
    }
    ?>

                    <span class="label">Donor Status</span>
                    <small>Eligible to donate</small>
                </div>
            </div>
        </div>

        <div class="dashboard-tabs">
            <button class="dash-tab active" id="tab-profile-btn" onclick="toggleDashboard('profile')">
                <i class="fa-solid fa-user"></i> My Profile
            </button>
            <button class="dash-tab" id="tab-history-btn" onclick="toggleDashboard('history')">
                <i class="fa-solid fa-clock-rotate-left"></i> Donation History
            </button>
        </div>

        <section id="profile-section" class="dash-content-card">
            <div class="card-header-flex">
                <h2>Personal Information</h2>
               <button class="btn btn-red btn-sm" onclick="openModal()">
               <i class="fa-solid fa-pen"></i> Edit Profile
               </button>
            </div>
            
            <div class="info-grid">
                <div class="info-group">
                    <label><i class="fa-solid fa-user"></i> Basic Information</label>
                    <div class="details-row">
                        <div><span>First Name</span><p><?php echo $user['first_name']; ?></p></div>
                        <div><span>Last Name</span><p><?php echo $user['last_name']; ?></p></div>
                        <div><span>Email</span><p><?php echo $user['email']; ?></p></div>
                        <div><span>Phone</span><p><?php echo $user['phone_number']; ?></p></div>
                        <div><span>Date of Birth</span><p><?php echo $user['birthday']; ?></p></div>
                        <div><span>Gender</span><p><?php echo $user['gender']; ?></p></div>
                    </div>
                </div>

                <div class="info-group">
                    <label><i class="fa-solid fa-heart-pulse"></i> Medical Information</label>
                    <div class="details-row">
                        <div><span>Blood Type</span><span class="count"><?php echo $user['blood_type']; ?></span></div>
                        <div><span>Weight (LBS)</span><p><?php echo $user['weight']; ?> lbs</p></div>
                        <div><span>Registration Date</span><p>2024-01-15</p></div>
                    </div>
                </div>

                <div class="info-group">
                    <label><i class="fa-solid fa-location-dot"></i> Address</label>
                    <div class="details-row">
                        <div class="full-width"><span>Street Address</span><p><?php echo $user['street_address']; ?></p></div>
                        <div><span>City</span><p><?php echo $user['city']; ?></p></div>
                    </div>
                </div>

                <div class="info-group">
                    <label><i class="fa-solid fa-phone-flip"></i> Emergency Contact</label>
                    <div class="details-row">
                        <div><span>Contact Name</span><p><?php echo $user['contact_name']; ?></p></div>
                        <div><span>Contact Phone</span><p><?php echo $user['contact_phone']; ?></p></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="history-section" class="dash-content-card" style="display: none;">
            <h2>Donation History</h2>
            <div class="impact-summary-box">
                <div class="icon-box-pink"><i class="fa-solid fa-heart"></i></div>
                <div class="impact-text">
                    <strong>Your Impact</strong>
                    <p>Your <span>3 donations</span> have potentially saved up to <span>24 lives</span>. Thank you for your continued support!</p>
                </div>
            </div>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Blood Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-11-20</td>
                        <td>University Medical Center</td>
                        <td><span class="blood-badge">O+</span></td>
                        <td><span class="status-pill success"><i class="fa-solid fa-check"></i> Completed</span></td>
                    </tr>
                    <tr>
                        <td>2024-09-15</td>
                        <td>University Medical Center</td>
                        <td><span class="blood-badge">O+</span></td>
                        <td><span class="status-pill success"><i class="fa-solid fa-check"></i> Completed</span></td>
                    </tr>
                    <tr>
                        <td>2024-07-10</td>
                        <td>Campus Blood Drive</td>
                        <td><span class="blood-badge">O+</span></td>
                        <td><span class="status-pill success"><i class="fa-solid fa-check"></i> Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-brand">
                <div class="logo">
                    <img src="mapua-logo.png" alt="Mapúa Logo"> 
                    <span>RedTrace</span>
                </div>
                <p>Empowering our university community to save lives through efficient blood donation management and transparent tracking systems.</p>
                <div class="social-icons">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a>
                <a href="#">Donate Now</a>
                <a href="#">Dashboard</a>
                <a href="notificatinos.php">Notifications</a>
                <a href="help.php">Help/Policies</a>
            </div>
            <div class="footer-links">
                <h4>Resources</h4>
                <a href="#">Donation Guidelines</a>
                <a href="#">Eligibility Criteria</a>
                <a href="#">FAQs</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
            <div class="footer-contact">
                <h4>Contact Us</h4>
                <p><i class="fa-solid fa-location-dot"></i> University Medical Center Room 201, Main Campus</p>
                <p><i class="fa-solid fa-phone"></i> +1 (555) 123-4567</p>
                <p><i class="fa-solid fa-envelope"></i> info@redtrace.edu</p>
                <p><i class="fa-solid fa-clock"></i> Mon-Fri: 9AM - 5PM</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 RedTrace. All rights reserved.</p>
            <p>Powered by VSCode</p>
        </div>
    </footer>

    

    <!-- Edit Profile Modal -->

    <div id="editProfileModal" class="modal">
    <div class="modal-content">
    <span id="closeModalBtn" class="close-btn">&times;</span>
    <h2>Edit Profile</h2>
    <form id="editProfileForm">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone_number" value="<?php echo $user['phone_number']; ?>">
        </div>
        <div class="form-group">
            <label>Birthday</label>
            <input type="date" name="birthday" value="<?php echo $user['birthday']; ?>">
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select name="gender">
                <option value="Male" <?php if($user['gender']=='Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($user['gender']=='Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if($user['gender']=='Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
        <label>Blood Type</label>
        <select name="blood_type" required>
            <option value="O+" <?php if($user['blood_type']=='O+') echo 'selected'; ?>>O+</option>
            <option value="O-" <?php if($user['blood_type']=='O-') echo 'selected'; ?>>O-</option>
            <option value="A+" <?php if($user['blood_type']=='A+') echo 'selected'; ?>>A+</option>
            <option value="A-" <?php if($user['blood_type']=='A-') echo 'selected'; ?>>A-</option>
            <option value="B+" <?php if($user['blood_type']=='B+') echo 'selected'; ?>>B+</option>
            <option value="B-" <?php if($user['blood_type']=='B-') echo 'selected'; ?>>B-</option>
            <option value="AB+" <?php if($user['blood_type']=='AB+') echo 'selected'; ?>>AB+</option>
            <option value="AB-" <?php if($user['blood_type']=='AB-') echo 'selected'; ?>>AB-</option>
        </select>
        </div>
        <div class="form-group">
            <label>Weight (lbs)</label>
            <input type="number" name="weight" value="<?php echo $user['weight']; ?>">
        </div>
        <div class="form-group">
            <label>Street Address</label>
            <input type="text" name="street_address" value="<?php echo $user['street_address']; ?>">
        </div>
        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" value="<?php echo $user['city']; ?>">
        </div>
        <div class="form-group">
            <label>Emergency Contact Name</label>
            <input type="text" name="contact_name" value="<?php echo $user['contact_name']; ?>">
        </div>
        <div class="form-group">
            <label>Emergency Contact Phone</label>
            <input type="text" name="contact_phone" value="<?php echo $user['contact_phone']; ?>">
        </div>
        <button type="submit" class="btn btn-red">Update Profile</button>
        <div id="formMessage"></div>
    </form>
  </div>
</div>

<script src="script.js"></script>

</body>
</html>


