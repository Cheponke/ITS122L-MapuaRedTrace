const Staff = {
  async getAllDonors() {
    const { data, error } = await supabaseClient
      .from(TABLES.DONORS).select("*").order("registration_date", { ascending: false });
    if (error) { console.error("getAllDonors error:", error.message); return []; }
    return data || [];
  },

  async updateDonor(donorId, updates) {
    const { error } = await supabaseClient
      .from(TABLES.DONORS).update(updates).eq("id", donorId);
    if (error) { console.error("updateDonor error:", error.message); return false; }
    return true;
  },

  async deleteDonor(donorId) {
    const { error } = await supabaseClient
      .from(TABLES.DONORS).delete().eq("id", donorId);
    if (error) { console.error("deleteDonor error:", error.message); return false; }
    return true;
  },

  async getInventory() {
    const { data, error } = await supabaseClient
      .from(TABLES.BLOOD_INVENTORY).select("*").order("expiration_date", { ascending: true });
    if (error) { console.error("getInventory error:", error.message); return []; }
    return data || [];
  },

  async addInventory(item) {
    const { error } = await supabaseClient.from(TABLES.BLOOD_INVENTORY).insert([item]);
    if (error) { console.error("addInventory error:", error.message); return false; }
    return true;
  },

  async updateInventory(id, updates) {
    const { error } = await supabaseClient
      .from(TABLES.BLOOD_INVENTORY).update(updates).eq("id", id);
    if (error) { console.error("updateInventory error:", error.message); return false; }
    return true;
  },

  async deleteInventory(id) {
    const { error } = await supabaseClient
      .from(TABLES.BLOOD_INVENTORY).delete().eq("id", id);
    if (error) { console.error("deleteInventory error:", error.message); return false; }
    return true;
  },

  async getPendingDonations() {
    const { data, error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .select("*, donors(first_name, last_name, blood_type, donor_id)")
      .eq("donation_status", "Pending")
      .order("donation_date", { ascending: false });
    if (error) { console.error("getPendingDonations error:", error.message); return []; }
    return data || [];
  },

  async updateDonationVitals(donationId, vitals) {
    const { error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .update(vitals)
      .eq("id", donationId);
    if (error) { console.error("updateDonationVitals error:", error.message); return false; }
    return true;
  },

  async approveDonation(donationId) {
    const { error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .update({ donation_status: "Approved" })
      .eq("id", donationId);
    if (error) { console.error("approveDonation error:", error.message); return false; }
    return true;
  },

  renderDonorsTable(donors) {
    const tbody = document.getElementById("donors-tbody");
    if (!tbody) return;
    if (!donors || donors.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" style="color:#888; text-align:center;">No donors found.</td></tr>`;
      return;
    }
    tbody.innerHTML = donors.map(d => `
      <tr>
        <td>${d.donor_id || "—"}</td>
        <td>${d.first_name || ""} ${d.last_name || ""}</td>
        <td>${d.email || "—"}</td>
        <td>${d.blood_type || "—"}</td>
        <td><span style="padding:0.2rem 0.6rem; border-radius:10px; font-size:0.8rem;
          background:${d.status === 'Active' ? '#eafaf1' : d.status === 'Pending' ? '#fff8e1' : '#f5f5f5'};
          color:${d.status === 'Active' ? '#27ae60' : d.status === 'Pending' ? '#e67e22' : '#888'};">
          ${d.status || "Active"}</span></td>
        <td>${formatDate(d.registration_date)}</td>
        <td style="display:flex; gap:0.4rem;">
          <button class="btn btn-sm" onclick="Staff.openEditModal(${d.id})">Edit</button>
          <button class="btn btn-sm btn-gray" onclick="confirmDeleteDonor(${d.id})">Delete</button>
        </td>
      </tr>
    `).join("");
  },

  renderInventoryTable(inventory) {
    const tbody = document.getElementById("inventory-tbody");
    if (!tbody) return;
    if (!inventory || inventory.length === 0) {
      tbody.innerHTML = `<tr><td colspan="6" style="color:#888; text-align:center;">No inventory records found.</td></tr>`;
      return;
    }
    tbody.innerHTML = inventory.map(i => {
      const isExpired = i.expiration_date && new Date(i.expiration_date) < new Date();
      return `
        <tr>
          <td>${i.id}</td>
          <td><strong>${i.blood_type || "—"}</strong></td>
          <td>${i.quantity_available ? i.quantity_available + " mL" : "—"}</td>
          <td style="color:${isExpired ? '#c0392b' : 'inherit'}">${formatDate(i.expiration_date)}</td>
          <td><span style="padding:0.2rem 0.6rem; border-radius:10px; font-size:0.8rem;
            background:${i.inventory_status === 'Available' ? '#eafaf1' : i.inventory_status === 'Reserved' ? '#e8f4fd' : '#fdf5f5'};
            color:${i.inventory_status === 'Available' ? '#27ae60' : i.inventory_status === 'Reserved' ? '#2980b9' : '#c0392b'};">
            ${i.inventory_status || "Available"}</span></td>
          <td style="display:flex; gap:0.4rem;">
            <button class="btn btn-sm btn-orange" onclick="openEditInventoryModal(${i.id})">Edit</button>
            <button class="btn btn-sm btn-gray" onclick="confirmDeleteInventory(${i.id})">Delete</button>
          </td>
        </tr>
      `;
    }).join("");
  },

  openEditModal: null, // assigned in page script
};
