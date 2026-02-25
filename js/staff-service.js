// ============================================================
// STAFF SERVICE - Manage donors, inventory, notifications
// ============================================================
import { supabase, TABLES } from "./supabase-config.js";

const StaffService = {

  // ---- DONOR MANAGEMENT ----

  // Get all donors
  async getAllDonors(statusFilter = null) {
    let query = supabase.from(TABLES.DONORS).select("*");
    if (statusFilter) query = query.eq("status", statusFilter);
    const { data, error } = await query;
    return { data, error };
  },

  // Get single donor details
  async getDonorById(donorId) {
    const { data, error } = await supabase
      .from(TABLES.DONORS)
      .select("*, donation_process(*), self_assessment(*)")
      .eq("donor_id", donorId)
      .single();
    return { data, error };
  },

  // Update donor record
  async updateDonor(donorId, updates) {
    const { data, error } = await supabase
      .from(TABLES.DONORS)
      .update(updates)
      .eq("donor_id", donorId);
    return { data, error };
  },

  // Approve donor to proceed to donation stage
  async approveDonor(donorId) {
    const { data, error } = await supabase
      .from(TABLES.DONORS)
      .update({ status: "Approved" })
      .eq("donor_id", donorId);
    return { data, error };
  },

  // ---- INVENTORY MANAGEMENT ----

  // Get all blood inventory
  async getInventory(statusFilter = null) {
    let query = supabase.from(TABLES.BLOOD_INVENTORY).select("*, donation_process(*)");
    if (statusFilter) query = query.eq("inventory_status", statusFilter);
    const { data, error } = await query;
    return { data, error };
  },

  // Delete an inventory entry (with audit log)
  async deleteInventoryEntry(inventoryId, staffId) {
    // ⚠️ AUDIT LOG: Record who deleted what before deleting
    // TODO: Insert into an audit_log table before deletion
    // await supabase.from("audit_log").insert([{ action: "DELETE", table: "blood_inventory", record_id: inventoryId, staff_id: staffId, timestamp: new Date() }]);

    const { data, error } = await supabase
      .from(TABLES.BLOOD_INVENTORY)
      .delete()
      .eq("inventory_id", inventoryId);
    return { data, error };
  },

  // Update inventory entry
  async updateInventory(inventoryId, updates) {
    const { data, error } = await supabase
      .from(TABLES.BLOOD_INVENTORY)
      .update(updates)
      .eq("inventory_id", inventoryId);
    return { data, error };
  },

  // ---- NOTIFICATIONS ----

  // Send a notification to all donors / specific blood type
  async sendNotification(notifData) {
    // notifData: { type, content, target_blood_type (optional) }
    const { data, error } = await supabase
      .from(TABLES.NOTIFICATIONS)
      .insert([{
        notification_type: notifData.type,         // "Urgent" | "Event" | "Reminder" | "Info"
        notification_content: notifData.content,
        notification_timestamp: new Date().toISOString(),
        notification_status: "Sent",
        target_blood_type: notifData.target_blood_type || null,
      }]);
    return { data, error };
  },

  // Get all sent notifications
  async getNotifications() {
    const { data, error } = await supabase
      .from(TABLES.NOTIFICATIONS)
      .select("*")
      .order("notification_timestamp", { ascending: false });
    return { data, error };
  },

  // Get staff dashboard summary stats
  async getDashboardStats() {
    const [totalRes, pendingRes, activeRes, inactiveRes] = await Promise.all([
      supabase.from(TABLES.DONORS).select("donor_id", { count: "exact" }),
      supabase.from(TABLES.DONORS).select("donor_id", { count: "exact" }).eq("status", "Pending"),
      supabase.from(TABLES.DONORS).select("donor_id", { count: "exact" }).eq("status", "Active"),
      supabase.from(TABLES.DONORS).select("donor_id", { count: "exact" }).eq("status", "Inactive"),
    ]);
    return {
      total: totalRes.count || 0,
      pending: pendingRes.count || 0,
      active: activeRes.count || 0,
      inactive: inactiveRes.count || 0,
    };
  },
};

export { StaffService };
