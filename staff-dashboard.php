<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard | Mapúa RedTrace</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="help-page">
    <header id="main-header">
        <nav class="navbar">
            <div class="logo">
                <a href="index.html">
                    <img src="mapua-logo.png" alt="Mapúa Logo"> 
                    <span>Mapúa RedTrace</span>
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="help.html">Help/Policies</a></li>
                <li><a href="notifications.html">Notifications</a></li>
            </ul>
        
        </div>
        </nav>
    </header>

    <main class="dashboard-container">
        <section class="donor-profile-header">
            <div class="profile-info-main">
                <div class="icon-box-pink" style="width: 60px; height: 60px; font-size: 1.5rem; background: var(--primary-red); color: white;">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <div class="profile-meta">
                    <h1>Staff Dashboard</h1>
                    <p>Hospital Staff Portal</p>
                </div>
            </div>
            <button class="btn btn-white btn-signout"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</button>
        </section>

        <div class="notif-stats-grid" style="grid-template-columns: repeat(4, 1fr);">
            <div class="notif-stat-card">
                <div class="icon-box blue-light"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <span class="count">6</span>
                    <span class="label">Total Donors</span>
                    <small>All registered donors</small>
                </div>
            </div>
            <div class="notif-stat-card">
                <div class="icon-box orange-light"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-info">
                    <span class="count">2</span>
                    <span class="label">Pending Approval</span>
                    <small>Awaiting review</small>
                </div>
            </div>
            <div class="notif-stat-card">
                <div class="icon-box green-light"><i class="fa-solid fa-check-circle"></i></div>
                <div class="stat-info">
                    <span class="count">3</span>
                    <span class="label">Active Donors</span>
                    <small>Approved and active</small>
                </div>
            </div>
            <div class="notif-stat-card">
                <div class="icon-box red-light" style="background: #f1f5f9; color: #64748b;"><i class="fa-solid fa-xmark"></i></div>
                <div class="stat-info">
                    <span class="count">1</span>
                    <span class="label">Inactive Donors</span>
                    <small>Not currently active</small>
                </div>
            </div>
        </div>

        <div class="dashboard-tabs">
            <button class="dash-tab active" id="tab-manage-donors-btn" onclick="toggleStaffView('donors')">
                <i class="fa-solid fa-user-group"></i> Manage Donors
            </button>
            <button class="dash-tab" id="tab-manage-inventory-btn" onclick="toggleStaffView('inventory')">
                <i class="fa-solid fa-box-archive"></i> Manage Inventory
            </button>
        </div>

        <section id="donor-management-section" class="dash-content-card">
            <div class="card-header-flex">
                <h2>Donor Management</h2>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search donors..." id="donorSearch">
                    <select class="filter-select">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Pending</option>
                    </select>
                </div>
            </div>
            
            <table class="history-table staff-table">
                <thead>
                    <tr>
                        <th>Donor ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Blood Type</th>
                        <th>Status</th>
                        <th>Donations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

            <?php
            include "config.php";

            $sql = "SELECT * FROM users WHERE role='donor'";
            $result = mysqli_query($conn,$sql);

            while($row = mysqli_fetch_assoc($result)){

            $id = $row['id'];
            $name = $row['first_name']." ".$row['last_name'];
            $email = $row['email'];
            $blood = $row['blood_type'];

            echo "
            <tr>
            <td>$id</td>
            <td>$name</td>
            <td>$email</td>
            <td><span class='blood-badge'>$blood</span></td>
            <td><span class='status-pill success'>Active</span></td>
            <td>0</td>
            <td>
            <button class='icon-btn edit-btn' onclick='openVitalsModal(\"$id\",\"$name\")'>
            <i class='fa-solid fa-pen'></i>
            </button>
            </td>
            </tr>
            ";
            }
            ?>

            </tbody>
            </table>
           
        </section>

        <section id="inventory-management-section" class="dash-content-card" style="display: none;">
            <div class="card-header-flex">
                <h2>Blood Inventory Management</h2>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search inventory...">
                </div>
            </div>

            <?php
			include "config.php";

			// Fetch counts based on VARCHAR status strings
			$total_res     = mysqli_query($conn, "SELECT COUNT(*) AS total FROM inventory");
			$avail_res     = mysqli_query($conn, "SELECT COUNT(*) AS total FROM inventory WHERE Inventory_Status = 'Available'");
			$reserved_res  = mysqli_query($conn, "SELECT COUNT(*) AS total FROM inventory WHERE Inventory_Status = 'Reserved'");

			// Counts as Expired if status is 'Expired' OR if the date has passed
			$expired_res   = mysqli_query($conn, "SELECT COUNT(*) AS total FROM inventory WHERE Inventory_Status = 'Expired' OR Inventory_ExpDate < CURDATE()");

			$total     = mysqli_fetch_assoc($total_res)['total'];
			$available = mysqli_fetch_assoc($avail_res)['total'];
			$reserved  = mysqli_fetch_assoc($reserved_res)['total'];
			$expired   = mysqli_fetch_assoc($expired_res)['total'];
			?>

			<div class="inventory-summary-grid">
				<div class="inv-summary-item blue-bg"><span><?php echo $total; ?></span><p>Total Units</p></div>
				<div class="inv-summary-item green-bg"><span><?php echo $available; ?></span><p>Available</p></div>
				<div class="inv-summary-item yellow-bg"><span><?php echo $reserved; ?></span><p>Reserved</p></div>
				<div class="inv-summary-item gray-bg"><span><?php echo $expired; ?></span><p>Expired</p></div>
			</div>

            <table class="history-table staff-table">
                <thead>
                    <tr>
                        <th>Inventory ID</th>
                        <th>Donor Name</th>
                        <th>Blood Type</th>
                        <th>Volume</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					mysqli_query($conn,"
					UPDATE inventory
					SET Inventory_Status='Expired'
					WHERE Inventory_ExpDate < CURDATE()
					AND Inventory_Status!='Expired'
					");
					
					include_once "config.php";

					$sql = "SELECT 
					inventory.InventoryID,
					inventory.Inventory_BloodType,
					inventory.Inventory_Volume,
					inventory.Inventory_ExpDate,
					inventory.Inventory_Status,
					users.first_name,
					users.last_name
					FROM inventory
					JOIN users ON inventory.DonationID = users.id";

					$result = mysqli_query($conn,$sql);

					if(mysqli_num_rows($result) > 0){

					while($row = mysqli_fetch_assoc($result)){

					$inventory_id = $row['InventoryID'];
					$name = $row['first_name']." ".$row['last_name'];
					$blood = $row['Inventory_BloodType'];
					$volume = $row['Inventory_Volume'];
					$exp = $row['Inventory_ExpDate'];
					$status = $row['Inventory_Status'];

					if($status == "Available"){
						$statusClass = "success";
					}
					elseif($status == "Reserved"){
						$statusClass = "warning";
					}
					elseif($status == "Expired"){
						$statusClass = "expired";
					}
					else{
						$statusClass = "";
					}

					echo "
					<tr>
					<td>INV00$inventory_id</td>
					<td>$name</td>
					<td><span class='blood-badge'>$blood</span></td>
					<td>{$volume} ml</td>
					<td>$exp</td>
					<td><span class='status-pill $statusClass'>$status</span></td>
					<td>

					<button class='icon-btn' style='color:#3b82f6;'>
					<i class='fa-solid fa-bell'></i>
					</button>

					<button class='icon-btn' style='color:#ef4444;' onclick='deleteInventory($inventory_id)'>
					<i class='fa-solid fa-trash'></i>
					</button>

					</td>
					</tr>
					";
					}

					}else{

					echo "<tr><td colspan='7'>No inventory records found</td></tr>";

					}
					?>
                </tbody>
            </table>
        </section>
    </main>

    <div id="vitalsModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <div>
                    <h2 style="margin:0;">Physical Screening</h2>
                    <small id="modalDonorInfo" style="color: #666; font-weight: bold;"></small>
                </div>
                <button class="close-btn" onclick="closeVitalsModal()">&times;</button>
            </div>
        
            <form id="vitalsForm" action="save_screening.php" method="POST">

                <input type="hidden" id="currentDonorId" name="donor_id">

                <div class="form-grid">

                <div class="input-group">
                <label>Blood Pressure (mmHg)</label>
                <input type="text" name="blood_pressure" placeholder="120/80" required>
                </div>

                <div class="input-group">
                <label>Pulse Rate (bpm)</label>
                <input type="number" name="pulse_rate" placeholder="72" required>
                </div>

                <div class="input-group">
                <label>Temperature (°C)</label>
                <input type="number" step="0.1" name="temperature" placeholder="36.5" required>
                </div>

                <div class="input-group">
                <label>Hemoglobin (g/dL)</label>
                <input type="number" step="0.1" name="hemoglobin_level" placeholder="13.5" required>
                </div>

                <div class="input-group full-width">
                <label>Weight (lbs)</label>
                <input type="number" name="weight" placeholder="165" required>
                </div>

                </div>

                <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeVitalsModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Screening</button>
                </div>

                </form>
        </div>
    </div>

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
                <a href="index.html">Home</a>
                <a href="#">Donate Now</a>
                <a href="#">Dashboard</a>
                <a href="notifications.html">Notifications</a>
                <a href="help.html">Help/Policies</a>
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

    <script src="script.js"></script>
</body>
</html>