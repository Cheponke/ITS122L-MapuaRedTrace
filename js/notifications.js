const Notifications = {
  async getAll() {
    const { data, error } = await supabaseClient
      .from(TABLES.NOTIFICATIONS)
      .select("*")
      .order("notification_timestamp", { ascending: false });
    if (error) { console.error("getAll error:", error.message); return []; }
    return data || [];
  },

  async getForUser(userId) {
    const { data, error } = await supabaseClient
      .from(TABLES.NOTIFICATIONS)
      .select("*")
      .or(`user_id.eq.${userId},user_id.is.null`)
      .order("notification_timestamp", { ascending: false });
    if (error) { console.error("getForUser error:", error.message); return []; }
    return data || [];
  },

  async send({ type, content, userId = null }) {
    const session = await Auth.getSession();
    if (!session) return false;
    const { error } = await supabaseClient.from(TABLES.NOTIFICATIONS).insert([{
      user_id: userId,
      notification_type: type,
      notification_content: content,
      notification_status: "Sent",
      notification_timestamp: new Date().toISOString(),
    }]);
    if (error) { console.error("send notification error:", error.message); return false; }
    return true;
  },

  async delete(id) {
    const { error } = await supabaseClient
      .from(TABLES.NOTIFICATIONS).delete().eq("id", id);
    if (error) { console.error("delete notification error:", error.message); return false; }
    return true;
  },
};
