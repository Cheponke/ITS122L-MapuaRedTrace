// ============================================================
// NOTIFICATIONS MODULE
// Handles: fetching, sending, and rendering notifications
// Non-functional requirement: alerts dispatched within 60s
// ============================================================

const Notifications = {

  // ----------------------------------------------------------
  // GET ALL NOTIFICATIONS
  // Called from: notifications.html on load
  // ----------------------------------------------------------
  async getAll(filters = {}) {
    let query = supabaseClient
      .from(TABLES.NOTIFICATIONS)
      .select("*")
      .order("notification_timestamp", { ascending: false });

    if (filters.type) query = query.eq("notification_type", filters.type);

    const { data, error } = await query;
    if (error) { console.error(error); return []; }
    return data;
  },

  // ----------------------------------------------------------
  // GET NOTIFICATIONS FOR CURRENT USER
  // Called from: donor notification bell / page
  // ----------------------------------------------------------
  async getForUser(userId) {
    const { data, error } = await supabaseClient
      .from(TABLES.NOTIFICATIONS)
      .select("*")
      .eq("user_id", userId)
      .order("notification_timestamp", { ascending: false });
    if (error) { console.error(error); return []; }
    return data;
  },

  // ----------------------------------------------------------
  // SEND NOTIFICATION (staff only)
  // Called from: "Send New Notification" button in staff dashboard
  // ----------------------------------------------------------
  async send(notifData) {
    const { error } = await supabaseClient
      .from(TABLES.NOTIFICATIONS)
      .insert([{
        notification_type: notifData.type,       // e.g. "Urgent", "Event", "Reminder"
        notification_content: notifData.content,
        notification_status: "Sent",
        notification_timestamp: new Date().toISOString(),
        user_id: notifData.userId || null,       // null = broadcast to all
      }]);

    if (error) { console.error(error); return false; }
    return true;
  },

  // ----------------------------------------------------------
  // SEND BLOOD TYPE ALERT (from Inventory > Notify button)
  // Broadcasts urgent notification for a specific blood type
  // ----------------------------------------------------------
  async sendBloodTypeAlert(bloodType) {
    const content = `URGENT: Critical shortage of ${bloodType} blood type. If you are eligible, please schedule a donation immediately.`;
    return await this.send({
      type: "Urgent",
      content,
      userId: null, // broadcast
    });
  },

  // ----------------------------------------------------------
  // RENDER NOTIFICATIONS LIST
  // TODO: point to your <div id="notifications-list"> element
  // ----------------------------------------------------------
  renderList(notifications) {
    const container = document.getElementById("notifications-list");
    if (!container) return;
    container.innerHTML = notifications.length === 0
      ? `<p>No notifications.</p>`
      : notifications.map(n => `
          <div class="notification-item ${n.notification_type?.toLowerCase()}">
            <strong>${n.notification_type}</strong>
            <p>${n.notification_content}</p>
            <small>${new Date(n.notification_timestamp).toLocaleString()}</small>
          </div>`).join("");
  },
};
