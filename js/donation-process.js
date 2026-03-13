const DonationProcess = {
  async submitSelfAssessment(donorId, answers) {
    // Eligibility rules per JRMMC criteria
    const disqualifiers = ["hadFever", "hadAntibiotics", "hadSurgery", "hadTattoo", "recentTravel", "isPregnant"];
    const isEligible = !disqualifiers.some(key => answers[key] === true);

    const record = JSON.stringify(answers);
    const { data, error } = await supabaseClient
      .from(TABLES.SELF_ASSESSMENT)
      .insert([{
        donor_id: donorId,
        assessment_status: "Completed",
        assessment_result: isEligible ? "Eligible" : "Not Eligible",
        assessment_record: record,
      }])
      .select()
      .single();

    if (error) { console.error("submitSelfAssessment error:", error.message); return { isEligible: false, error: error.message }; }
    return { isEligible, assessmentId: data.id };
  },

  async createDonationRecord(donorId, bloodType) {
    const { data, error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .insert([{
        donor_id: donorId,
        donation_date: new Date().toISOString().split("T")[0],
        donation_status: "Pending",
        blood_type: bloodType,
        location: "Mapua University Medical Center",
      }])
      .select()
      .single();

    if (error) { console.error("createDonationRecord error:", error.message); return null; }
    return data;
  },

  async checkApprovalStatus(donationId) {
    const { data, error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .select("donation_status, blood_pressure, pulse_rate, temperature, weight")
      .eq("id", donationId)
      .maybeSingle();
    if (error) { console.error("checkApprovalStatus error:", error.message); return null; }
    return data;
  },

  async completeDonation(donationId, quantityMl) {
    const { error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .update({ donation_status: "Completed", quantity_ml: quantityMl || 450 })
      .eq("id", donationId);
    if (error) { console.error("completeDonation error:", error.message); return false; }
    return true;
  },

  async addToInventory(donationId, bloodType, quantityMl) {
    const expDate = new Date();
    expDate.setDate(expDate.getDate() + 35); // whole blood expires in 35 days
    const { error } = await supabaseClient.from(TABLES.BLOOD_INVENTORY).insert([{
      donation_id: donationId,
      blood_type: bloodType,
      quantity_available: quantityMl || 450,
      expiration_date: expDate.toISOString().split("T")[0],
      inventory_status: "Available",
      storage_location: "Main Blood Bank",
    }]);
    if (error) { console.error("addToInventory error:", error.message); return false; }
    return true;
  },
};
