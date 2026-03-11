// ==========================================================================
// GLOBAL VARIABLES
// Used for tracking donation progress in donation-process.html
// ==========================================================================
let answeredCount = 0;
const totalQuestions = 12;

// ==========================================================================
// NOTIFICATION FILTERING LOGIC
// Primary file: notifications.html
// Handles the pill-based filtering of urgent alerts, events, and reminders
// ==========================================================================
document.addEventListener("DOMContentLoaded", () => {
  // 1. Notification Filtering Logic
  const pills = document.querySelectorAll(".filter-pills .pill");
  const cards = document.querySelectorAll(".notif-card");

  if (pills.length > 0) {
    pills.forEach((pill) => {
      pill.addEventListener("click", () => {
        // Update Active Pill UI state
        pills.forEach((p) => p.classList.remove("active"));
        pill.classList.add("active");

        // Filter cards based on data-filter attribute
        const filterValue = pill.getAttribute("data-filter");
        cards.forEach((card) => {
          if (filterValue === "all" || card.classList.contains(filterValue)) {
            card.style.display = "flex";
          } else {
            card.style.display = "none";
          }
        });
      });
    });
  }

  // 2. Initial Assessment Progress check for donation-process.html
  updateProgress();
});

// ==========================================================================
// NOTIFICATION DELETION
// Primary file: notifications.html
// Allows staff to remove notification cards from the dashboard
// ==========================================================================
document.querySelectorAll(".fa-trash").forEach((trashIcon) => {
  trashIcon.parentElement.addEventListener("click", function () {
    if (confirm("Are you sure you want to delete this notification?")) {
      this.closest(".notif-card").remove();
    }
  });
});

// ==========================================================================
// HEADER SCROLL LOGIC
// Shared across all .html files
// Changes navbar background from transparent to white on scroll
// ==========================================================================
window.addEventListener("scroll", function () {
  const header = document.getElementById("main-header");
  if (header) {
    if (window.scrollY > 50) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  }
});

// ==========================================================================
// TAB SWITCHING (FAQs vs Policies)
// Primary file: help.html
// Toggles visibility between the Frequently Asked Questions and Policy cards
// ==========================================================================
function switchTab(tabName) {
  document
    .querySelectorAll(".tab-btn")
    .forEach((btn) => btn.classList.remove("active"));
  if (event) {
    event.currentTarget.classList.add("active");
  }

  const faqSection = document.querySelector(".faq-section");
  const policiesSection = document.querySelector(".policies-section");

  if (faqSection && policiesSection) {
    faqSection.style.display = tabName === "faqs" ? "block" : "none";
    policiesSection.style.display = tabName === "policies" ? "block" : "none";
  }
}

// ==========================================================================
// ACCORDION LOGIC
// Primary file: help.html
// Handles the expanding/collapsing of FAQ questions
// ==========================================================================
document.querySelectorAll(".accordion-header").forEach((header) => {
  header.addEventListener("click", () => {
    const item = header.parentElement;
    document.querySelectorAll(".accordion-item").forEach((other) => {
      if (other !== item) other.classList.remove("active");
    });
    item.classList.toggle("active");
  });
});

// ==========================================================================
// MODAL MANAGEMENT (Staff Notifications)
// Primary file: notifications.html
// Controls the "Send New Notification" popup form
// ==========================================================================
const modal = document.getElementById("modalOverlay");
const openBtn = document.querySelector(".btn-red .fa-plus")?.parentElement;
const closeBtn = document.getElementById("closeModal");
const cancelBtn = document.getElementById("cancelModal");

if (openBtn)
  openBtn.addEventListener("click", () => (modal.style.display = "flex"));
const closeModal = () => {
  if (modal) modal.style.display = "none";
};
if (closeBtn) closeBtn.addEventListener("click", closeModal);
if (cancelBtn) cancelBtn.addEventListener("click", closeModal);
window.addEventListener("click", (e) => {
  if (e.target === modal) closeModal();
});

// ==========================================================================
// AUTH ROLES AND MODES
// Primary file: login.html
// Handles Donor vs Staff role selection and Sign-In vs Register tab switching
// ==========================================================================
function selectRole(role) {
  const donorBtn = document.getElementById("donor-role");
  const staffBtn = document.getElementById("staff-role");
  const submitBtn = document.getElementById("submit-auth-btn");
  const subtext = document.getElementById("auth-subtext");
  const notice = document.getElementById("staff-footer-note");
  const emailInput = document.getElementById("login-email");
  const badge = document.getElementById("auth-badge");

  if (role === "donor") {
    donorBtn.classList.add("active");
    staffBtn.classList.remove("active");
    submitBtn.innerHTML =
      '<i class="fa-solid fa-right-to-bracket"></i> Sign In';
    subtext.innerText =
      "Sign in to access your donor dashboard and track your donations.";
    notice.style.display = "none";
    emailInput.placeholder = "your.email@university.edu";
    badge.innerHTML = '<i class="fa-solid fa-heart"></i> Donate Now';
  } else {
    staffBtn.classList.add("active");
    donorBtn.classList.remove("active");
    submitBtn.innerHTML =
      '<i class="fa-solid fa-hospital-user"></i> Sign In as Staff';
    subtext.innerText =
      "Sign in to access the staff dashboard and manage donors.";
    notice.style.display = "block";
    emailInput.placeholder = "staff@hospital.com";
    badge.innerHTML = '<i class="fa-solid fa-heart-pulse"></i> Staff Portal';
  }
}

function toggleAuth(mode) {
  const signInTab = document.getElementById("tab-signin");
  const registerTab = document.getElementById("tab-register");
  const signInSection = document.getElementById("signin-section");
  const registerSection = document.getElementById("register-section");
  const title = document.getElementById("auth-title");
  const subtext = document.getElementById("auth-subtext");

  if (mode === "signin") {
    signInTab.classList.add("active");
    registerTab.classList.remove("active");
    signInSection.style.display = "block";
    registerSection.style.display = "none";
    title.innerText = "Welcome Back";
    subtext.innerText = "Sign in to access your donor dashboard.";
  } else {
    registerTab.classList.add("active");
    signInTab.classList.remove("active");
    registerSection.style.display = "block";
    signInSection.style.display = "none";
    title.innerText = "Join Our Community";
    subtext.innerText = "Register as a blood donor today.";
  }
}

// ==========================================================================
// ASSESSMENT LOGIC
// Primary file: donation-process.html
// Handles user input for the 12 screening questions and tracks progress
// ==========================================================================
function answer(btn, choice) {
  const row = btn.closest(".q-row");
  const btns = row.querySelectorAll(".choice-btn");
  btns.forEach((b) => b.classList.remove("selected-yes", "selected-no"));
  btn.classList.add(choice === "Yes" ? "selected-yes" : "selected-no");
  row.classList.add("answered");

  if (!row.hasAttribute("data-answered")) {
    row.setAttribute("data-answered", "true");
    answeredCount++;
  }
  row.setAttribute("data-user-choice", choice);
  updateProgress();
}

function updateProgress() {
  const progressDisplay = document.getElementById("progress-text");
  const btn = document.getElementById("continue-btn");
  if (progressDisplay)
    progressDisplay.innerText = `${answeredCount} of ${totalQuestions} questions answered`;

  if (answeredCount === totalQuestions && btn) {
    btn.disabled = false;
    btn.classList.add("ready"); // Activates Mapúa Red styling

    // Ensure button is clickable despite default CSS restrictions
    btn.style.pointerEvents = "auto";
    btn.style.opacity = "1";
  }
}

// ==========================================================================
// ELIGIBILITY CHECK
// Primary file: donation-process.html
// Compares user answers to data-required attributes and shows Success/Fail modals
// ==========================================================================
function checkEligibility() {
  document.getElementById("loading-overlay").style.display = "flex";
  setTimeout(() => {
    document.getElementById("loading-overlay").style.display = "none";
    const rows = document.querySelectorAll(".q-row");
    let failedQuestion = null;

    for (let row of rows) {
      if (
        row.getAttribute("data-user-choice") !==
        row.getAttribute("data-required")
      ) {
        failedQuestion = row.querySelector("p").innerText.split(". ")[1];
        break;
      }
    }

    if (failedQuestion) {
      document.getElementById("failure-reason").innerText = failedQuestion;
      document.getElementById("ineligible-modal").style.display = "flex";
    } else {
      document.getElementById("eligible-modal").style.display = "flex";
    }
  }, 2000);
}

// ==========================================================================
// STEP TRANSITION LOGIC
// Primary file: donation-process.html
// Manages the single-page transition from Self-Assessment upto Complete
// ==========================================================================
function changeStep(step) {
  const assessmentSection = document.getElementById("section-assessment");
  const screeningSection = document.getElementById("section-screening");
  const confirmSection = document.getElementById("section-confirm");
  const successSection = document.getElementById("success-view");
  const eligibleModal = document.getElementById("eligible-modal");

  // Step Node/Line variables
  const step2Node = document.getElementById("step-2-node");
  const step3Node = document.getElementById("step-3-node");
  const step4Node = document.getElementById("step-4-node");
  const line1 = document.getElementById("line-1");
  const line2 = document.getElementById("line-2");
  const line3 = document.getElementById("line-3");

  // 1. HIDE EVERYTHING FIRST (Prevents overlapping steps)
  [assessmentSection, screeningSection, confirmSection, successSection].forEach(
    (sec) => {
      if (sec) sec.style.display = "none";
    },
  );
  if (eligibleModal) eligibleModal.style.display = "none";

  // 2. SHOW SPECIFIC STEP
  if (step === 1) {
    assessmentSection.style.display = "block";
    // Reset Stepper
    step2Node.classList.remove("active");
    line1.style.backgroundColor = "#e2e8f0";
  } else if (step === 2) {
    screeningSection.style.display = "block";
    step2Node.classList.add("active");
    line1.style.backgroundColor = "var(--primary-red)";
  } else if (step === 3) {
    confirmSection.style.display = "block";
    step3Node.classList.add("active");
    line2.style.backgroundColor = "var(--primary-red)";
  } else if (step === 4) {
    successSection.style.display = "block";
    step4Node.classList.add("active");
    line3.style.backgroundColor = "var(--primary-red)";
  }

  window.scrollTo(0, 0);
}

// =========================================================================
// PHYSICAL SCREENING INITIALIZATION
// Simulates staff check, hides the pending card, and shows the results card.
// =========================================================================
function startPhysicalScreening() {
  const overlay = document.getElementById("loading-overlay");
  const pendingCard = document.querySelector(
    "#section-screening .assessment-card",
  );
  const resultsCard = document.getElementById("vitals-results");

  // Show loading overlay
  if (overlay) {
    overlay.querySelector("h2").innerText = "Staff checking vitals...";
    overlay.style.display = "flex";
  }

  const donorId = window.currentDonorId; // donor id from PHP

  setInterval(() => {
    fetch("check_screening.php?donor_id=" + donorId)
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "done") {
          if (overlay) overlay.style.display = "none";

          const currentVitals = {
            systolic: data.systolic,
            diastolic: data.diastolic,
            pulse: data.pulse,
            hemoglobin: data.hemoglobin,
            weight: data.weight,
          };

          const isEligible = checkPhysicalEligibility(currentVitals);

          if (isEligible) {
            if (pendingCard) pendingCard.style.display = "none";
            if (resultsCard) resultsCard.style.display = "block";
          }
        }
      });
  }, 5000);
}

function checkPhysicalEligibility(vitals) {
  let failures = [];

  // 1. Check Blood Pressure: 90-160/60-100 mmHg
  if (
    vitals.systolic < 90 ||
    vitals.systolic > 160 ||
    vitals.diastolic < 60 ||
    vitals.diastolic > 100
  ) {
    failures.push(
      "Blood Pressure (" + vitals.systolic + "/" + vitals.diastolic + " mmHg)",
    );
  }

  // 2. Check Pulse Rate: 60-100 bpm
  if (vitals.pulse < 60 || vitals.pulse > 100) {
    failures.push("Pulse Rate (" + vitals.pulse + " bpm)");
  }

  // 3. Check Hemoglobin: min 12.5 g/dL (125 g/L)
  if (vitals.hemoglobin < 12.5) {
    failures.push("Hemoglobin (" + vitals.hemoglobin + " g/dL)");
  }

  // 4. Check Weight: min 110 lbs
  if (vitals.weight < 110) {
    failures.push("Weight (" + vitals.weight + " lbs)");
  }

  // Modal Trigger Logic
  if (failures.length > 0) {
    // Format the reason string (e.g., "Weight (105 lbs), Pulse Rate (110 bpm)")
    const reasonText = failures.join(", ");

    // Update the span in your HTML
    document.getElementById("failure-reason").innerText = reasonText;

    // Show the modal
    document.getElementById("ineligible-modal").style.display = "flex";
    return false; // Ineligible
  }

  // If no failures, hide modal and show success state
  document.getElementById("ineligible-modal").style.display = "none";
  return true; // Eligible
}

// =========================================================================
// COMPLETE INITIALIZATION
// Shows the thank you card and donation summary
// =========================================================================
function completeProcess() {
  const consentCheck = document.getElementById("consent-check");

  // Check if checkbox exists
  if (!consentCheck) {
    console.error("Checkbox element 'consent-check' not found.");
    return;
  }

  // If not checked, show modal
  if (!consentCheck.checked) {
    showAccuracyModal();
    consentCheck.focus();
    return;
  }

  // If checked → go directly to Step 4
  changeStep(4);

  // Ensure success view is visible
  const successView = document.getElementById("success-view");
  if (successView) {
    successView.classList.remove("hidden");
    successView.style.display = "block";
  }
}

// Modal Toggle Functions
function showAccuracyModal() {
  document.getElementById("accuracy-modal").style.display = "flex";
}

function closeAccuracyModal() {
  document.getElementById("accuracy-modal").style.display = "none";
}

// ==========================================================================
// DONOR DASHBOARD TOGGLE
// Switches between Personal Info and Donation History
// ==========================================================================
function toggleDashboard(view) {
  const profileSection = document.getElementById("profile-section");
  const historySection = document.getElementById("history-section");
  const profileBtn = document.getElementById("tab-profile-btn");
  const historyBtn = document.getElementById("tab-history-btn");

  if (view === "profile") {
    profileSection.style.display = "block";
    historySection.style.display = "none";
    profileBtn.classList.add("active");
    historyBtn.classList.remove("active");
  } else {
    profileSection.style.display = "none";
    historySection.style.display = "block";
    historyBtn.classList.add("active");
    profileBtn.classList.remove("active");
  }
}

// ==========================================================================
// STAFF DASHBOARD VIEW TOGGLE
// ==========================================================================
function toggleStaffView(view) {
  const donorSection = document.getElementById("donor-management-section");
  const inventorySection = document.getElementById(
    "inventory-management-section",
  );
  const donorBtn = document.getElementById("tab-manage-donors-btn");
  const inventoryBtn = document.getElementById("tab-manage-inventory-btn");

  if (view === "donors") {
    donorSection.style.display = "block";
    inventorySection.style.display = "none";
    donorBtn.classList.add("active");
    inventoryBtn.classList.remove("active");
  } else {
    donorSection.style.display = "none";
    inventorySection.style.display = "block";
    inventoryBtn.classList.add("active");
    donorBtn.classList.remove("active");
  }
}

// ==========================================================================
// ROLE-BASED ROUTING LOGIC
// Logic for index.html "View Dashboard" and Navbar User Icon
// ==========================================================================

function handleDashboardRedirection() {
  // These values will be set by your login.php script later
  const isLoggedIn = localStorage.getItem("isLoggedIn"); // true/false
  const userRole = localStorage.getItem("userRole"); // 'donor' or 'staff'

  if (!isLoggedIn || isLoggedIn === "false") {
    // If not logged in, always go to login page
    window.location.href = "login.html";
  } else {
    // Redirect based on role
    if (userRole === "staff") {
      window.location.href = "staff-dashboard.php";
    } else {
      window.location.href = "donor-dashboard.php";
    }
  }
}

// Attach event listeners
document.addEventListener("DOMContentLoaded", () => {
  const dashBtn = document.getElementById("main-dashboard-btn");
  const userIcons = document.querySelectorAll(".user-icon");

  if (dashBtn) {
    dashBtn.addEventListener("click", handleDashboardRedirection);
  }

  userIcons.forEach((icon) => {
    icon.style.cursor = "pointer"; // Ensure the icon looks clickable
    icon.addEventListener("click", handleDashboardRedirection);
  });
});

// Hide login button if user is logged in
document.addEventListener("DOMContentLoaded", function () {
  const loginBtn = document.getElementById("login-btn");
  const isLoggedIn = localStorage.getItem("isLoggedIn");

  if (isLoggedIn === "true" && loginBtn) {
    loginBtn.style.display = "none";
  }
});

// Logout Button
function logout() {
  localStorage.removeItem("isLoggedIn");
  localStorage.removeItem("userRole");
  window.location.href = "index.html";
}

// Edit Profiles
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("editProfileModal");
  const closeBtn = document.getElementById("closeModalBtn");
  const form = document.getElementById("editProfileForm");

  // 1. Open Logic (Call this from your dashboard button)
  window.openModal = function () {
    if (modal) modal.style.display = "block";
  };

  // 2. Close Logic via Button
  if (closeBtn) {
    closeBtn.addEventListener("click", function () {
      modal.style.display = "none";
    });
  }

  // 3. Close Logic via Clicking Outside
  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  // AJAX form submission...
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      let formData = new FormData(this);

      fetch("edit-profile.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          document.getElementById("formMessage").innerText = data;

          if (data.includes("success")) {
            setTimeout(() => {
              location.reload();
            }, 1500);
          }
        })
        .catch((error) => console.error("Error:", error));
    });
  }
});

// ==========================================================================
// STAFF DASHBOARD EDIT MODAL
// ==========================================================================
// Opens the modal and labels it with the donor's info
function openVitalsModal(id, name) {
  const modal = document.getElementById("vitalsModal");

  document.getElementById("currentDonorId").value = id;

  document.getElementById("modalDonorInfo").innerText =
    `Editing Screening for: ${name} (${id})`;

  modal.style.display = "flex";
}

// Closes the modal and resets the form fields
function closeVitalsModal() {
  const modal = document.getElementById("vitalsModal");
  modal.style.display = "none";
  document.getElementById("vitalsForm").reset();
}

// Close modal if the user clicks the dark background
window.onclick = function (event) {
  const modal = document.getElementById("vitalsModal");
  if (event.target == modal) {
    closeVitalsModal();
  }
};

// For Password constraits
document.addEventListener("DOMContentLoaded", function () {
  const registerForm = document.querySelector("#register-section form");

  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      const pass = registerForm.querySelector('input[name="password"]').value;
      const confirm = registerForm.querySelector(
        'input[name="confirm_password"]',
      ).value;

      if (pass !== confirm) {
        alert("Passwords do not match.");
        e.preventDefault();
      }
    });
  }
});

// Pass error
const params = new URLSearchParams(window.location.search);

if (params.get("error")) {
  const errorMsg = document.getElementById("login-error");
  if (errorMsg) {
    errorMsg.style.display = "block";
  }
}
