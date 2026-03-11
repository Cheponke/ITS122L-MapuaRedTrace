<?php
session_start();
include "config.php";

$donor_id = $_SESSION['user_id']; // logged in donor

$query = "SELECT * FROM screenings WHERE donor_id='$donor_id' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn,$query);
$data = mysqli_fetch_assoc($result);

if(!$data){
    $data = [
        "date" => date("Y-m-d H:i:s"),
        "location" => "Waiting for staff screening",
        "blood_pressure" => "--",
        "pulse_rate" => "--",
        "temperature" => "--",
        "hemoglobin_level" => "--",
        "weight" => "--"
    ];
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Donation Process | Mapúa RedTrace</title>
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
  </head>
  <body class="help-page">
    <header id="main-header">
      <nav class="navbar">
        <div class="logo">
          <a href="index.html">
            <img src="mapua-logo.png" alt="Mapúa Logo" />
            <span>Mapúa RedTrace</span>
          </a>
        </div>
        <ul class="nav-links">
          <li><a href="index.html">Home</a></li>
          <li><a href="help.html">Help/Policies</a></li>
          <li><a href="notifications.html">Notifications</a></li>
        </ul>
        <div class="nav-actions">
          <a href="login.html" class="btn btn-red" style="text-decoration: none"
            >Sign-In/Register</a
          >
          <div class="user-icon"><i class="fa-regular fa-user"></i></div>
        </div>
      </nav>
    </header>

    <main class="process-container">
      <div class="stepper">
        <div class="step active" id="step-1-node">
          <div class="step-num">1</div>
          <span class="step-text">Self-Assessment</span>
        </div>
        <div class="step-line" id="line-1"></div>
        <div class="step" id="step-2-node">
          <div class="step-num">2</div>
          <span class="step-text">Physical Screening</span>
        </div>
        <div class="step-line" id="line-2"></div>
        <div class="step" id="step-3-node">
          <div class="step-num">3</div>
          <span class="step-text">Confirm Donation</span>
        </div>
        <div class="step-line" id="line-3"></div>
        <div class="step" id="step-4-node">
          <div class="step-num">4</div>
          <span class="step-text">Complete</span>
        </div>
      </div>

      <section id="section-assessment">
        <div class="assessment-card">
          <div class="assessment-header">
            <div class="icon-box-pink">
              <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <div class="header-text">
              <h1>Self-Assessment Screening</h1>
              <p>
                Please answer all questions honestly to determine your
                eligibility
              </p>
            </div>
          </div>

          <div id="questions-list">
            <div class="q-row" data-required="Yes">
              <div class="q-content">
                <span class="q-tag">General Health</span>
                <p>1. Are you feeling healthy and well today?</p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes
                </button>
                <button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">General Health</span>
                <p>
                  2. Have you had any cold, flu, or fever in the past 14 days?
                </p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">Medications</span>
                <p>3. Have you taken any antibiotics in the past 14 days?</p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">Medications</span>
                <p>
                  4. Have you taken aspirin or any blood thinners in the past 48
                  hours?
                </p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">Risk Factors</span>
                <p>
                  5. Have you had any tattoos, piercings, or acupuncture in the
                  past 12 months?
                </p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">Risk Factors</span>
                <p>
                  6. Have you traveled outside the country in the past 12
                  months?
                </p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">Medical Procedures</span>
                <p>
                  7. Have you had any dental work or surgery in the past 72
                  hours?
                </p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">Medical Procedures</span>
                <p>
                  8. Have you received any vaccinations in the past 4 weeks?
                </p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">Donation History</span>
                <p>9. Have you donated blood in the past 56 days (8 weeks)?</p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="Yes">
              <div class="q-content">
                <span class="q-tag">Readiness</span>
                <p>10. Did you get at least 6 hours of sleep last night?</p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="Yes">
              <div class="q-content">
                <span class="q-tag">Readiness</span>
                <p>11. Have you eaten a meal in the past 4 hours?</p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
            <div class="q-row" data-required="No">
              <div class="q-content">
                <span class="q-tag">Readiness</span>
                <p>12. Have you consumed alcohol in the past 24 hours?</p>
              </div>
              <div class="q-btns">
                <button class="choice-btn yes" onclick="answer(this, 'Yes')">
                  Yes</button
                ><button class="choice-btn no" onclick="answer(this, 'No')">
                  No
                </button>
              </div>
            </div>
          </div>

          <div class="assessment-footer">
            <p id="progress-text">0 of 12 questions answered</p>
            <button
              id="continue-btn"
              class="btn-red-fade"
              disabled
              onclick="checkEligibility()"
            >
              Continue <i class="fa-solid fa-arrow-right"></i>
            </button>
          </div>
        </div>
      </section>

      <section id="section-screening" style="display: none">
        <div class="assessment-card">
          <div class="assessment-header">
            <div class="icon-box-pink">
              <i class="fa-solid fa-stethoscope"></i>
            </div>
            <div class="header-text">
              <h1>Physical Screening</h1>
              <p>Hospital staff will check your vital signs</p>
            </div>
          </div>

          <div class="info-alert-blue">
            <div class="icon-circle-blue">
              <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div class="alert-content">
              <strong>Staff Physical Examination</strong>
              <p>
                A medical staff member will now perform a brief physical
                examination to check your vital signs and ensure you're ready to
                donate safely.
              </p>
            </div>
          </div>

          <h3>Vital Signs to be Checked</h3>
          <div class="vitals-grid">
            <div class="vital-item">
              <div class="vital-icon pink-light">
                <i class="fa-solid fa-heart-pulse"></i>
              </div>
              <div class="vital-info">
                <span>Blood Pressure</span>
                <p>Pending...</p>
              </div>
            </div>
            <div class="vital-item">
              <div class="vital-icon pink-light">
                <i class="fa-solid fa-wave-square"></i>
              </div>
              <div class="vital-info">
                <span>Pulse Rate</span>
                <p>Pending...</p>
              </div>
            </div>
            <div class="vital-item">
              <div class="vital-icon pink-light">
                <i class="fa-solid fa-temperature-half"></i>
              </div>
              <div class="vital-info">
                <span>Temperature</span>
                <p>Pending...</p>
              </div>
            </div>
            <div class="vital-item">
              <div class="vital-icon pink-light">
                <i class="fa-solid fa-droplet"></i>
              </div>
              <div class="vital-info">
                <span>Hemoglobin Level</span>
                <p>Pending...</p>
              </div>
            </div>
            <div class="vital-item full-width">
              <div class="vital-icon pink-light">
                <i class="fa-solid fa-weight-scale"></i>
              </div>
              <div class="vital-info">
                <span>Weight</span>
                <p>Pending...</p>
              </div>
            </div>
          </div>

          <div class="reason-box" style="text-align: left">
            <strong style="color: #b45309"
              ><i class="fa-solid fa-circle-info"></i> What to Expect</strong
            >
            <ul class="expect-list">
              <li>Blood pressure will be measured using a cuff on your arm</li>
              <li>Pulse rate will be checked at your wrist</li>
              <li>Temperature will be taken using a thermometer</li>
              <li>A small finger prick will test your hemoglobin level</li>
              <li>You will be weighed to ensure safe donation volume</li>
            </ul>
          </div>

          <div class="assessment-footer">
            <button class="btn btn-white" onclick="changeStep(1)">
              <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <button class="btn btn-red" onclick="startPhysicalScreening()">
              Begin Physical Screening <i class="fa-solid fa-stethoscope"></i>
            </button>
          </div>
        </div>

        <div id="vitals-results" style="display: none">
          <div class="assessment-card">
            <div class="assessment-header">
              <div class="icon-box-pink">
                <i class="fa-solid fa-stethoscope"></i>
              </div>
              <div class="header-text">
                <h1>Physical Screening</h1>
                <p>Hospital staff will check your vital signs</p>
              </div>
            </div>
            <div class="status-banner success-bg">
              <div class="icon-box-green">
                <i class="fa-solid fa-check"></i>
              </div>
              <div class="alert-content">
                <strong>Physical Screening Complete</strong>
                <p>
                  All your vital signs are within the acceptable range for blood
                  donation.
                </p>
              </div>
            </div>

            <h4 class="section-subtitle">Your Vital Signs</h4>

            <div class="vitals-grid">
              <div class="vital-item success-item">
                <div class="vital-icon-box success-icon">
                  <i class="fa-solid fa-heart-pulse"></i>
                </div>
                <div class="vital-details">
                  <span class="v-label">Blood Pressure</span>
                 <span class="v-value bp-value">
                  <?php echo $data['blood_pressure']; ?> mmHg
                  </span>
                  <span class="v-status success-text">Normal</span>
                </div>
              </div>
              <div class="vital-item success-item">
                <div class="vital-icon-box success-icon">
                  <i class="fa-solid fa-wave-square"></i>
                </div>
                <div class="vital-details">
                  <span class="v-label">Pulse Rate</span>
                  <span class="v-value pulse-value">
                  <?php echo $data['pulse_rate']; ?> bpm
                  </span>
                  <span class="v-status success-text">Normal</span>
                </div>
              </div>
              <div class="vital-item success-item">
                <div class="vital-icon-box success-icon">
                  <i class="fa-solid fa-temperature-half"></i>
                </div>
                <div class="vital-details">
                  <span class="v-label">Temperature</span>
                  <span class="v-value temp-value">
                    ><?php echo $data['temperature']; ?>
                    °C</span
                  >
                  <span class="v-status success-text">Normal</span>
                </div>
              </div>
              <div class="vital-item success-item">
                <div class="vital-icon-box success-icon">
                  <i class="fa-solid fa-droplet"></i>
                </div>
                <div class="vital-details">
                  <span class="v-label">Hemoglobin Level</span>
                  <span class="v-value hemo-value">
                  <?php echo $data['hemoglobin_level']; ?> g/dL
                  </span>
                  <span class="v-status success-text">Normal</span>
                </div>
              </div>
              <div class="vital-item success-item full-width">
                <div class="vital-icon-box success-icon">
                  <i class="fa-solid fa-weight-scale"></i>
                </div>
                <div class="vital-details">
                  <span class="v-label">Weight</span>
                  <span class="v-value weight-value">
                  <?php echo $data['weight']; ?> lbs
                  </span>
                  <span class="v-status success-text"
                    >Meets minimum requirements</span
                  >
                </div>
              </div>
            </div>

            <div class="info-alert-blue approval-box">
              <div class="approval-left-group">
                <div class="icon-circle-blue">
                  <i class="fa-solid fa-user-md"></i>
                </div>
                <div class="alert-content approval-content">
                  <strong>Approved by Medical Staff</strong>
                  <p>
                    Screening completed at
                    <span id="completion-time">--:-- --</span>
                  </p>
                </div>
              </div>

              <span class="badge-cleared">✓ Cleared</span>
            </div>

            <div class="assessment-footer">
              <button class="btn btn-white" onclick="changeStep(1)">
                <i class="fa-solid fa-arrow-left"></i> Back
              </button>
              <button class="btn btn-red" onclick="changeStep(3)">
                Continue to Confirmation <i class="fa-solid fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>
      </section>

      <section id="section-confirm" style="display: none">
        <div class="assessment-card">
          <div class="assessment-header">
            <div class="icon-box-success">
              <i class="fa-solid fa-check-double"></i>
            </div>
            <div class="header-text">
              <h1>You're Ready to Donate!</h1>
              <p>Please review and confirm your donation</p>
            </div>
          </div>

          <div class="status-banner success-bg">
            <div class="icon-circle-green">
              <i class="fa-solid fa-shield"></i>
            </div>
            <div class="alert-content">
              <strong>All Screenings Passed</strong>
              <p>
                You have passed both self-assessment and physical screening. You
                are cleared to donate blood.
              </p>
            </div>
          </div>

          <h4 class="section-subtitle">Donation Details</h4>
          <div class="details-grid">
            <div class="detail-item">
              <div class="detail-icon-box">
                <i class="fa-solid fa-calendar-days"></i>
              </div>
              <div>
                <label>DATE</label>
                <strong>
                <?php echo date("F j, Y g:i A", strtotime($data['date'])); ?>
                </strong>
              </div>
            </div>

            <div class="detail-item">
              <div class="detail-icon-box">
                <i class="fa-solid fa-location-dot"></i>
              </div>
              <div>
                <label>LOCATION</label>
                <strong class="location-value"><?php echo $data['location']; ?></strong>
              </div>
            </div>

            <!--
            <div class="detail-item">
              <div class="detail-icon-box">
                <i class="fa-solid fa-droplet"></i>
              </div>
              <div>
                <label>DONATION TYPE</label>
                <strong>Whole Blood</strong>
              </div>
            </div>

            <div class="detail-item">
              <div class="detail-icon-box">
                <i class="fa-solid fa-clock"></i>
              </div>
              <div>
                <label>DURATION</label>
                <strong>~10-15 minutes</strong>
                --
              </div>
            </div>
-->
          </div>


          

          <div class="vitals-summary-container">
            <h5 class="summary-header">
              <i class="fa-solid fa-stethoscope"></i> Your Vitals Summary
            </h5>

            <div class="summary-flex-row">
              <div class="summary-card">
                <span class="label">BP</span>
                <span class="v-value"
                  ><?php echo $data['blood_pressure']; ?>
                  mmHg</span
                >
              </div>

              <div class="summary-card">
                <span class="label">Pulse</span>
                <span class="v-value"
                  ><?php echo $data['pulse_rate']; ?>
                  bpm</span
                >
              </div>

              <div class="summary-card">
                <span class="label">Temp</span>
              <span class="v-value temp-value">
              <?php echo $data['temperature']; ?> °C
              </span>
              </div>

              <div class="summary-card">
                <span class="label">Hgb</span>
                <span class="v-value"
                  ><?php echo $data['hemoglobin_level']; ?>
                  g/dL</span
                >
              </div>

              <div class="summary-card">
                <span class="label">Weight</span>
                <span class="v-value"
                  ><?php echo $data['weight']; ?>
                  lbs</span
                >
              </div>
            </div>
          </div>

          <div class="info-alert-yellow reminders-box">
            <h5><i class="fa-solid fa-lightbulb"></i> Final Reminders</h5>
            <ul>
              <li>Relax and stay calm during the donation process</li>
              <li>Let the staff know if you feel dizzy or uncomfortable</li>
              <li>The actual blood draw takes only 10-15 minutes</li>
              <li>Refreshments will be provided after donation</li>
            </ul>
          </div>

          <div class="confirmation-check">
            <input type="checkbox" id="consent-check" />
            <label for="consent-check"
              >I confirm that all information provided is accurate and truthful.
              I understand the blood donation process and consent to donate
              blood voluntarily.</label
            >
          </div>

          <div class="assessment-footer">
            <button class="btn btn-white" onclick="changeStep(2)">
              <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <button class="btn btn-red" onclick="completeProcess()">
              Confirm Donation <i class="fa-solid fa-check"></i>
            </button>
          </div>
        </div>
      </section>

      <section id="success-view">
        <div class="assessment-card">
          <div class="success-icon-wrap">
            <div class="heart-bg">
              <i class="fas fa-heart"></i>
            </div>
          </div>

          <div class="success-text-header">
            <h2>Thank You for Donating!</h2>
            <p>
              Your donation has been completed successfully. You're a hero —
              your blood will help save lives!
            </p>
          </div>

          <div class="donation-summary-box">
            <div class="summary-card-header">
              <i class="fa-solid fa-tint"></i> Donation Summary
            </div>
            <div class="summary-row">
              <span>Date</span>
              <strong><?php echo date("F j, Y g:i A", strtotime($data['date'])); ?></strong>
            </div>
            <div class="summary-row">
              <span>Location</span>
              <strong><?php echo $data['location']; ?></strong>
            </div>
            <!--
            <div class="summary-row">
              <span>Type</span><strong>Whole Blood</strong>
            </div>
              -->
            <div class="summary-row">
              <span>Status</span>
              <span class="status-badge-success">
                <i class="fa-solid fa-check"></i> Completed
              </span>
            </div>
          </div>

          <div class="impact-banner-yellow">
            <i class="fa-regular fa-heart"></i> Your donation can save up to 3
            lives!
          </div>

          <div class="post-care-container">
            <h4>Post-Donation Care</h4>
            <div class="care-grid">
              <div class="care-item">
                <div class="care-icon-box">
                  <i class="fas fa-glass-whiskey"></i>
                </div>
                <p>Drink extra fluids for the next 24-48 hours</p>
              </div>
              <div class="care-item">
                <div class="care-icon-box">
                  <i class="fa-solid fa-utensils"></i>
                </div>
                <p>Eat iron-rich foods to replenish your blood</p>
              </div>
              <div class="care-item">
                <div class="care-icon-box">
                  <i class="fa-solid fa-running"></i>
                </div>
                <p>Avoid strenuous exercise for 24 hours</p>
              </div>
              <div class="care-item">
                <div class="care-icon-box">
                  <i class="fa-solid fa-bandage"></i>
                </div>
                <p>Keep the bandage on for at least 4 hours</p>
              </div>
            </div>
          </div>

          <div class="assessment-footer flex-row">
            <button class="btn btn-red flex-1" onclick="location.reload()">
              <i class="fa-solid fa-user-gear"></i> Go to Dashboard
            </button>
            <button
              class="btn btn-white flex-1"
              onclick="location.href = 'index.html'"
            >
              <i class="fa-solid fa-house"></i> Back to Home
            </button>
          </div>
        </div>
      </section>
    </main>

    <div id="loading-overlay" class="modal-overlay">
      <div class="loading-card">
        <div class="spinner"></div>
        <h2>Checking Eligibility</h2>
        <p>Please wait...</p>
      </div>
    </div>

    <div id="ineligible-modal" class="modal-overlay">
      <div class="ineligible-card">
        <div class="warning-circle">
          <i class="fa-solid fa-exclamation"></i>
        </div>
        <h2>Not Eligible to Donate</h2>
        <div class="reason-box">
          <strong>Reason:</strong> <span id="failure-reason"></span>
        </div>
        <p class="disclaimer">
          Please consult with a healthcare professional or try again later.
          Thank you!
        </p>
        <button
          class="btn btn-red btn-full"
          onclick="location.href = 'index.html'"
        >
          Return to Dashboard
        </button>
      </div>
    </div>

    <div id="eligible-modal" class="modal-overlay">
      <div class="eligible-card">
        <div class="success-circle">
          <i class="fa-solid fa-circle-check"></i>
        </div>
        <h2>You are Eligible!</h2>
        <div class="success-box">
          <strong>Status:</strong> Ready for Physical Screening
        </div>
        <p class="next-steps">
          Proceed to University Medical Center Room 201 for your physical exam.
        </p>
        <button class="btn btn-red btn-full" onclick="changeStep(2)">
          Proceed to Step 2 <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
    </div>

    <div id="accuracy-modal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-icon">
          <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <h3>Confirm Accuracy</h3>
        <p>
          Please confirm that all information provided is accurate and truthful
          before proceeding.
        </p>
        <button class="btn-confirm" onclick="closeAccuracyModal()">
          I Understand
        </button>
      </div>
    </div>

    <script>

    function checkScreening(){

    fetch("check_screening.php?donor_id=<?php echo $donor_id; ?>")
    .then(response => response.json())
    .then(data => {

    if(data.status === "done"){

    document.querySelector(".bp-value").innerText = data.systolic + "/" + data.diastolic + " mmHg";
    document.querySelector(".pulse-value").innerText = data.pulse + " bpm";
    document.querySelector(".temp-value").innerText = data.temperature + " °C ";
    document.querySelector(".hemo-value").innerText = data.hemoglobin + " g/dL";
    document.querySelector(".weight-value").innerText = data.weight + " lbs";
    document.querySelector(".location-value").innerText = data.location;

    }

    });

    }

    setInterval(checkScreening, 5000); // check every 5 seconds

</script>
    <footer>
      <div class="footer-container">
        <div class="footer-brand">
          <div class="logo">
            <img src="mapua-logo.png" alt="Mapúa Logo" />
            <span>RedTrace</span>
          </div>
          <p>
            Empowering our university community to save lives through efficient
            blood donation management and transparent tracking systems.
          </p>
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
          <p>
            <i class="fa-solid fa-location-dot"></i> University Medical Center
            Room 201, Main Campus
          </p>
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


    <script>
window.currentDonorId = <?php echo $donor_id; ?>;


</script>

    <script src="script.js"></script>
  </body>
</html>
