// ============================================================
// DONOR MODULE
// Handles: profile fetch/update, donation history
// ============================================================

const Donor = {

  // ----------------------------------------------------------
  // GET DONOR PROFILE by user_id
  // Called from: donor-dashboard.html on load
  // ----------------------------------------------------------
  async getProfile(userId) {
  console.log("Fetching profile for userId:", userId);
  
  const { data, error } = await supabaseClient
    .from("donors")
    .select("*")
    .eq("user_id", userId)
    .maybeSingle();

  console.log("Profile result — data:", data, "error:", error);

  if (error) {
    console.error("getProfile error:", error.message);
    return null;
  }
  if (!data) {
    console.warn("No donor row found for user_id:", userId);
    return null;
  }
  return data;
},

  // ----------------------------------------------------------
  // UPDATE DONOR PROFILE
  // Called from: Edit Profile form submit
  // ----------------------------------------------------------
  async updateProfile(donorId, updates) {
    const { error } = await supabaseClient
      .from(TABLES.DONORS)
      .update(updates)
      .eq("donor_id", donorId);
    if (error) { showError("profile-error", error.message); return false; }
    return true;
  },

  // ----------------------------------------------------------
  // GET DONATION HISTORY for a donor
  // Called from: donor-dashboard.html Donation History tab
  // ----------------------------------------------------------
  async getDonationHistory(donorId) {
    const { data, error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .select("*")
      .eq("donor_id", donorId)
      .order("donation_date", { ascending: false });
    if (error) { console.error(error); return []; }
    return data;
  },

  // ----------------------------------------------------------
  // RENDER PROFILE to DOM
  // TODO: customize field IDs to match your HTML elements
  // ----------------------------------------------------------
  renderProfile(profile) {
    document.getElementById("donor-name").textContent =
      `${profile.first_name} ${profile.last_name}`;
    document.getElementById("donor-id").textContent = profile.donor_id;
    document.getElementById("donor-blood-type").textContent = profile.blood_type || "—";
    document.getElementById("donor-status").textContent = profile.status || "Active";
    // ADD MORE FIELDS HERE to match your HTML
  },

  // ----------------------------------------------------------
  // RENDER DONATION HISTORY TABLE
  // TODO: point #donation-history-tbody to your table body element
  // ----------------------------------------------------------
  renderDonationHistory(donations) {
    const tbody = document.getElementById("donation-history-tbody");
    if (!tbody) return;
    tbody.innerHTML = donations.length === 0
      ? `<tr><td colspan="4">No donations yet.</td></tr>`
      : donations.map(d => `
          <tr>
            <td>${d.donation_date}</td>
            <td>${d.donation_status}</td>
            <td>${d.quantity_ml ?? "—"} mL</td>
            <td>${d.blood_type || "—"}</td>
          </tr>`).join("");
  },
};
