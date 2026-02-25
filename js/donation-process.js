// ============================================================
// DONATION PROCESS MODULE
// Handles the 4-step donation workflow:
// Step 1: Self-Assessment → Step 2: Screening → Step 3: Donation → Step 4: Complete
// ============================================================

const DonationProcess = {

  currentStep: 1,
  donorId: null,
  donationId: null,

  // ----------------------------------------------------------
  // STEP 1: Submit Self-Assessment
  // Called from: self-assessment form submit
  // Inserts into self_assessments table
  // Returns: eligible (bool) — controls whether to show popup or proceed
  // ----------------------------------------------------------
  async submitSelfAssessment(formData) {
    try {
      const userId = localStorage.getItem("userId");

      // Simple eligibility check — customize rules as needed
      const eligible = this.checkEligibility(formData);

      const { data, error } = await supabaseClient
        .from(TABLES.SELF_ASSESSMENT)
        .insert([{
          donor_id: formData.donorId,
          assessment_status: eligible ? "Eligible" : "Not Eligible",
          assessment_result: JSON.stringify(formData.answers),
          // ADD: assessment_record, donation_id after donation record created
        }])
        .select()
        .single();

      if (error) throw error;

      if (!eligible) {
        // Show ineligibility popup
        showPopup("assessment-popup", "You are not eligible to donate at this time. Please consult a health professional.");
        return false;
      }

      // Proceed to Step 2 (Screening)
      this.goToStep(2);
      return true;
    } catch (err) {
      console.error("Self-assessment error:", err.message);
    }
  },

  // ----------------------------------------------------------
  // ELIGIBILITY LOGIC
  // TODO: Replace with your actual eligibility rules
  // ----------------------------------------------------------
  checkEligibility(formData) {
    // PLACEHOLDER: Basic example — customize with real rules
    // e.g. formData.answers.hasIllness === "no" && formData.answers.antibiotics === "no"
    return true; // Default: eligible
  },

  // ----------------------------------------------------------
  // STEP 2: Staff submits Screening result
  // Called from: staff physical screening form
  // Updates donation_process record status
  // ----------------------------------------------------------
  async submitScreening(donationId, screeningData) {
    try {
      const eligible = screeningData.eligible; // bool set by staff

      const { error } = await supabaseClient
        .from(TABLES.DONATION_PROCESS)
        .update({ donation_status: eligible ? "Screened" : "Rejected" })
        .eq("donation_id", donationId);

      if (error) throw error;

      if (!eligible) {
        showPopup("screening-popup", "Donor did not pass physical screening.");
        return false;
      }

      this.goToStep(3);
      return true;
    } catch (err) {
      console.error("Screening error:", err.message);
    }
  },

  // ----------------------------------------------------------
  // STEP 3: Confirm & Record Donation
  // Creates or updates donation_process record to "Completed"
  // Also updates blood_inventory
  // ----------------------------------------------------------
  async confirmDonation(donorId, appointmentId, bloodType, quantityMl) {
    try {
      // Create donation record
      const { data: donation, error: donErr } = await supabaseClient
        .from(TABLES.DONATION_PROCESS)
        .insert([{
          donor_id: donorId,
          donation_date: new Date().toISOString().split("T")[0],
          donation_status: "Completed",
          // appointment_id: appointmentId,  // ADD when appointments table ready
        }])
        .select()
        .single();

      if (donErr) throw donErr;

      // Update blood inventory
      const { error: invErr } = await supabaseClient
        .from(TABLES.BLOOD_INVENTORY)
        .insert([{
          donation_id: donation.donation_id,
          blood_type: bloodType,
          quantity_available: quantityMl,
          inventory_status: "Available",
          // ADD: storage_location, expiration_date
        }]);

      if (invErr) throw invErr;

      this.goToStep(4);
    } catch (err) {
      console.error("Donation confirmation error:", err.message);
    }
  },

  // ----------------------------------------------------------
  // STEP NAVIGATION HELPER
  // Hides all steps, shows only the target step
  // Requires step divs with IDs: step-1, step-2, step-3, step-4
  // ----------------------------------------------------------
  goToStep(step) {
    for (let i = 1; i <= 4; i++) {
      const el = document.getElementById(`step-${i}`);
      if (el) el.style.display = i === step ? "block" : "none";
    }
    this.currentStep = step;
    // Update progress indicator
    document.querySelectorAll(".step-indicator").forEach((el, idx) => {
      el.classList.toggle("active", idx + 1 === step);
      el.classList.toggle("completed", idx + 1 < step);
    });
  },
};

// ----------------------------------------------------------
// POPUP HELPER
// Shows a modal with a given message
// Requires a <div id="POPUP_ID" class="popup"> element in HTML
// ----------------------------------------------------------
function showPopup(popupId, message) {
  const popup = document.getElementById(popupId);
  if (!popup) return;
  popup.querySelector(".popup-message").textContent = message;
  popup.style.display = "flex";
}

function closePopup(popupId) {
  const popup = document.getElementById(popupId);
  if (popup) popup.style.display = "none";
}
