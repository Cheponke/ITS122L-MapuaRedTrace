// ============================================================
// STAFF MODULE
// Handles: donor management, blood inventory, approvals
// ============================================================

const Staff = {

  // ----------------------------------------------------------
  // GET ALL DONORS
  // Called from: staff-dashboard.html Manage Donors tab
  // ----------------------------------------------------------
  async getAllDonors(filters = {}) {
    let query = supabaseClient.from(TABLES.DONORS).select("*");

    // Optional filtering by status
    if (filters.status) query = query.eq("status", filters.status);
    if (filters.search) {
      query = query.or(`first_name.ilike.%${filters.search}%,last_name.ilike.%${filters.search}%`);
    }

    const { data, error } = await query.order("registration_date", { ascending: false });
    if (error) { console.error(error); return []; }
    return data;
  },

  // ----------------------------------------------------------
  // UPDATE DONOR RECORD
  // Called from: Edit donor form in staff dashboard
  // ----------------------------------------------------------
  async updateDonor(donorId, updates) {
    const { error } = await supabaseClient
      .from(TABLES.DONORS)
      .update(updates)
      .eq("donor_id", donorId);
    if (error) { console.error(error); return false; }
    return true;
  },

  // ----------------------------------------------------------
  // APPROVE DONOR (move from Screening to Donation phase)
  // Updates donation_process status to "Approved"
  // ----------------------------------------------------------
  async approveDonor(donationId) {
    const { error } = await supabaseClient
      .from(TABLES.DONATION_PROCESS)
      .update({ donation_status: "Approved" })
      .eq("donation_id", donationId);
    if (error) { console.error(error); return false; }
    return true;
  },

  // ----------------------------------------------------------
  // GET BLOOD INVENTORY
  // Called from: Manage Inventory tab
  // ----------------------------------------------------------
  async getInventory(filters = {}) {
    let query = supabaseClient.from(TABLES.BLOOD_INVENTORY).select("*");
    if (filters.status) query = query.eq("inventory_status", filters.status);
    if (filters.bloodType) query = query.eq("blood_type", filters.bloodType);

    const { data, error } = await query;
    if (error) { console.error(error); return []; }
    return data;
  },

  // ----------------------------------------------------------
  // DELETE INVENTORY ENTRY
  // Called from: Delete button in inventory table
  // ----------------------------------------------------------
  async deleteInventoryEntry(inventoryId) {
    const confirmed = confirm("Are you sure you want to delete this inventory entry?");
    if (!confirmed) return;

    const { error } = await supabaseClient
      .from(TABLES.BLOOD_INVENTORY)
      .delete()
      .eq("inventory_id", inventoryId);

    if (error) { console.error(error); return false; }

    // TODO: Log this deletion for the audit trail (non-functional requirement: integrity)
    // await AuditLog.record("DELETE", "blood_inventory", inventoryId);

    return true;
  },

  // ----------------------------------------------------------
  // RENDER DONORS TABLE
  // TODO: point to your <tbody id="donors-tbody"> element
  // ----------------------------------------------------------
  renderDonorsTable(donors) {
    const tbody = document.getElementById("donors-tbody");
    if (!tbody) return;
    tbody.innerHTML = donors.length === 0
      ? `<tr><td colspan="7">No donors found.</td></tr>`
      : donors.map(d => `
          <tr>
            <td>${d.donor_id}</td>
            <td>${d.first_name} ${d.last_name}</td>
            <td>${d.email || "—"}</td>
            <td>${d.blood_type || "—"}</td>
            <td><span class="badge">${d.status || "Active"}</span></td>
            <td>${d.registration_date?.split("T")[0] || "—"}</td>
            <td>
              <button onclick="Staff.openEditModal('${d.donor_id}')">Edit</button>
              <button onclick="Staff.approveDonor('${d.donor_id}')">Approve</button>
            </td>
          </tr>`).join("");
  },

  // ----------------------------------------------------------
  // RENDER INVENTORY TABLE
  // TODO: point to your <tbody id="inventory-tbody"> element
  // ----------------------------------------------------------
  renderInventoryTable(inventory) {
    const tbody = document.getElementById("inventory-tbody");
    if (!tbody) return;
    tbody.innerHTML = inventory.length === 0
      ? `<tr><td colspan="6">No inventory records.</td></tr>`
      : inventory.map(i => `
          <tr>
            <td>${i.inventory_id}</td>
            <td>${i.blood_type}</td>
            <td>${i.quantity_available} mL</td>
            <td>${i.expiration_date || "—"}</td>
            <td>${i.inventory_status}</td>
            <td>
              <button onclick="Notifications.sendBloodTypeAlert('${i.blood_type}')">Notify</button>
              <button onclick="Staff.deleteInventoryEntry('${i.inventory_id}')">Delete</button>
            </td>
          </tr>`).join("");
  },

  // ----------------------------------------------------------
  // OPEN EDIT MODAL (placeholder — wire to your modal HTML)
  // ----------------------------------------------------------
  openEditModal(donorId) {
    // TODO: populate modal fields with donor data, then show modal
    console.log("Open edit modal for donor:", donorId);
  },
};
