// ============================================================
// AUTH MODULE — Login, Register, Logout
// Handles both Donor and Staff authentication via Supabase Auth
// ============================================================

const Auth = {

  // ----------------------------------------------------------
  // LOGIN
  // Called from: pages/login.html
  // Redirects donor -> donor-dashboard.html, staff -> staff-dashboard.html
  // ----------------------------------------------------------
  async login(email, password, role) {
    try {
      const { data, error } = await supabaseClient.auth.signInWithPassword({
        email,
        password,
      });
      if (error) throw error;

      // Store role in localStorage for session use
      localStorage.setItem("userRole", role);
      localStorage.setItem("userId", data.user.id);

      if (role === "donor") {
        window.location.href = "donor-dashboard.html";
      } else if (role === "staff") {
        window.location.href = "staff-dashboard.html";
      }
    } catch (err) {
      console.error("Login error:", err.message);
      showError("login-error", err.message);
    }
  },

  // ----------------------------------------------------------
  // REGISTER (Donor only)
  // Called from: pages/login.html (Register tab)
  // Creates Supabase auth user + inserts into donors table
  // ----------------------------------------------------------
 async register(formData) {
  try {
    // Pass user details as metadata — the trigger will insert the donor row automatically
    const { data, error } = await supabaseClient.auth.signUp({
      email: formData.email,
      password: formData.password,
      options: {
        data: {
          firstName: formData.firstName,
          lastName: formData.lastName,
          phone: formData.phone,
          role: "donor",
        }
      }
    });
    if (error) throw error;
    if (!data?.user?.id) throw new Error("No user ID returned.");

    alert("Registration successful! You can now log in.");
    window.location.href = "login.html";

  } catch (err) {
    console.error("Register error:", err.message);
    showError("register-error", err.message);
  }
},

  // ----------------------------------------------------------
  // LOGOUT
  // ----------------------------------------------------------
  async logout() {
    await supabaseClient.auth.signOut();
    localStorage.clear();
    window.location.href = "../index.html";
  },

  // ----------------------------------------------------------
  // GET CURRENT SESSION
  // ----------------------------------------------------------
  async getSession() {
    const { data } = await supabaseClient.auth.getSession();
    return data.session;
  },

  // ----------------------------------------------------------
  // GUARD — Redirect to login if not authenticated
  // Call at top of any protected page
  // ----------------------------------------------------------
  async requireAuth() {
    const session = await this.getSession();
    if (!session) {
      window.location.href = "login.html";
    }
    return session;
  },

  // ----------------------------------------------------------
  // GUARD — Redirect if not staff
  // ----------------------------------------------------------
  async requireStaff() {
    const session = await this.requireAuth();
    const role = localStorage.getItem("userRole");
    if (role !== "staff") {
      window.location.href = "index.html";
    }
    return session;
  },
};
