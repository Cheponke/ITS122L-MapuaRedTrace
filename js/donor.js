const Donor = {
  async getProfile(userId) {
    const { data, error } = await supabaseClient
      .from(TABLES.DONORS)
      .select("*")
      .eq("user_id", userId)
      .maybeSingle();
    if (error) { console.error("getProfile error:", error.message); return null; }
    return data;
  },

  async updateProfile(donorId, updates) {
    const { error } = await supabaseClient
      .from(TABLES.DONORS)
      .update(updates)
      .eq("id", donorId);
    if (error) { console.error("updateProfile error:", error.message); return false; }
    return true;
  },

  async getDonationHistory(donorId) {
    if (!donorId) return [];
    const { data, error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .select("*")
      .eq("donor_id", donorId)
      .order("donation_date", { ascending: false });
    if (error) { console.error("getDonationHistory error:", error.message); return []; }
    return data || [];
  },

  renderDonationHistory(history) {
    const tbody = document.getElementById("donation-history-tbody");
    if (!tbody) return;
    if (!history || history.length === 0) {
      tbody.innerHTML = `<tr><td colspan="4" style="color:#888; text-align:center;">No donation history yet.</td></tr>`;
      return;
    }
    tbody.innerHTML = history.map(d => `
      <tr>
        <td>${formatDate(d.donation_date)}</td>
        <td><span style="padding:0.2rem 0.6rem; border-radius:10px; font-size:0.8rem;
          background:${d.donation_status === 'Completed' ? '#eafaf1' : '#fff8e1'};
          color:${d.donation_status === 'Completed' ? '#27ae60' : '#e67e22'};">
          ${d.donation_status || "Pending"}</span></td>
        <td>${d.quantity_ml ? d.quantity_ml + " mL" : "—"}</td>
        <td>${d.blood_type || "—"}</td>
      </tr>
    `).join("");
  },
};
