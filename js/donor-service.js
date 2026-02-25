// ============================================================
// DONOR SERVICE - Donor profile, assessment, donation workflow
// ============================================================
import { supabase, TABLES } from "./supabase-config.js";

const DonorService = {

  // Get donor profile by user_id
  async getProfile(userId) {
    const { data, error } = await supabase
      .from(TABLES.DONORS)
      .select("*")
      .eq("user_id", userId)
      .single();
    return { data, error };
  },

  // Update donor profile
  async updateProfile(donorId, updates) {
    const { data, error } = await supabase
      .from(TABLES.DONORS)
      .update(updates)
      .eq("donor_id", donorId);
    return { data, error };
  },

  // Submit self-assessment form
  async submitSelfAssessment(donorId, answers) {
    // answers: object of { question_key: true/false }
    // Eligibility logic: if ANY disqualifying answer is "yes", mark not eligible
    // ⚠️ TODO: Define your eligibility logic here based on your questionnaire
    const isEligible = determineEligibility(answers);

    const { data, error } = await supabase
      .from(TABLES.SELF_ASSESSMENT)
      .insert([{
        donor_id: donorId,
        assessment_status: isEligible ? "Eligible" : "Not Eligible",
        assessment_result: JSON.stringify(answers),
        assessment_record: new Date().toISOString(),
      }]);

    return { data, error, isEligible };
  },

  // Get donation history for a donor
  async getDonationHistory(donorId) {
    const { data, error } = await supabase
      .from(TABLES.DONATION_PROCESS)
      .select("*")
      .eq("donor_id", donorId)
      .order("donation_date", { ascending: false });
    return { data, error };
  },

  // Confirm donation (after staff approval)
  async confirmDonation(donorId, appointmentId) {
    const { data, error } = await supabase
      .from(TABLES.DONATION_PROCESS)
      .insert([{
        donor_id: donorId,
        appointment_id: appointmentId,
        donation_date: new Date().toISOString(),
        donation_status: "Completed",
      }]);
    return { data, error };
  },
};

// ============================================================
// ELIGIBILITY LOGIC - Customize based on your questionnaire
// ============================================================
function determineEligibility(answers) {
  // TODO: Replace with actual disqualifying conditions
  // Example: if donor is sick, return false
  const disqualifiers = [
    "isSick",
    "hadAntibiotics",
    "hadRecentSurgery",
    // Add more keys as needed
  ];
  for (const key of disqualifiers) {
    if (answers[key] === true) return false;
  }
  return true;
}

export { DonorService };
