// ============================================================
// AUTH SERVICE - Handles login, register, logout
// ============================================================
import { supabase, TABLES } from "./supabase-config.js";

const AuthService = {

  // Register a new donor
  async registerDonor(formData) {
    const { firstName, lastName, email, password, phoneNumber } = formData;
    try {
      // Step 1: Create auth user
      const { data: authData, error: authError } = await supabase.auth.signUp({
        email,
        password,
      });
      if (authError) throw authError;

      // Step 2: Insert into donors table
      const { data, error } = await supabase.from(TABLES.DONORS).insert([{
        user_id: authData.user.id,
        first_name: firstName,
        last_name: lastName,
        email,
        contact_number: phoneNumber,
        registration_date: new Date().toISOString(),
        status: "Active",
      }]);
      if (error) throw error;

      return { success: true, data };
    } catch (err) {
      return { success: false, error: err.message };
    }
  },

  // Login as Donor
  async loginDonor(email, password) {
    try {
      const { data, error } = await supabase.auth.signInWithPassword({ email, password });
      if (error) throw error;

      // Fetch donor profile
      const { data: donor } = await supabase
        .from(TABLES.DONORS)
        .select("*")
        .eq("user_id", data.user.id)
        .single();

      return { success: true, user: data.user, donor };
    } catch (err) {
      return { success: false, error: err.message };
    }
  },

  // Login as Staff
  async loginStaff(email, password) {
    try {
      const { data, error } = await supabase.auth.signInWithPassword({ email, password });
      if (error) throw error;

      // Fetch staff profile
      const { data: staff } = await supabase
        .from(TABLES.STAFF)
        .select("*")
        .eq("user_id", data.user.id)
        .single();

      if (!staff) throw new Error("Not authorized as staff.");

      return { success: true, user: data.user, staff };
    } catch (err) {
      return { success: false, error: err.message };
    }
  },

  // Logout
  async logout() {
    await supabase.auth.signOut();
    localStorage.removeItem("userRole");
    window.location.href = "../index.html";
  },

  // Get current session
  async getSession() {
    const { data } = await supabase.auth.getSession();
    return data.session;
  },

  // Get current user role from localStorage (set on login)
  getUserRole() {
    return localStorage.getItem("userRole"); // "donor" or "staff"
  },
};

export { AuthService };
