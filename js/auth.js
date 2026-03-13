const Auth = {
  async login(email, password, role) {
    try {
      const { data, error } = await supabaseClient.auth.signInWithPassword({ email, password });
      if (error) throw error;

      const userId = data.user.id;

      if (role === "staff") {
        const { data: staffRow } = await supabaseClient
          .from(TABLES.STAFF).select("*").eq("user_id", userId).maybeSingle();
        if (!staffRow) throw new Error("No staff record found for this account.");
        localStorage.setItem("userRole", "staff");
        window.location.href = "staff-dashboard.html";
      } else {
        const { data: donorRow } = await supabaseClient
          .from(TABLES.DONORS).select("*").eq("user_id", userId).maybeSingle();
        if (!donorRow) throw new Error("No donor record found for this account.");
        localStorage.setItem("userRole", "donor");
        window.location.href = "donor-dashboard.html";
      }
    } catch (err) {
      console.error("Login error:", err.message);
      showError("login-error", err.message);
    }
  },

  async register(formData) {
    try {
      const { data, error } = await supabaseClient.auth.signUp({
        email: formData.email,
        password: formData.password,
        options: {
          data: {
            firstName: formData.firstName,
            lastName:  formData.lastName,
            phone:     formData.phone,
            birthDate: formData.birthDate,
            sex:       formData.sex,
            bloodType: formData.bloodType,
            address:   formData.address,
            role:      "donor",
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

  async logout() {
    await supabaseClient.auth.signOut();
    localStorage.removeItem("userRole");
    window.location.href = "login.html";
  },

  async getSession() {
    const { data } = await supabaseClient.auth.getSession();
    return data?.session || null;
  },

  async requireAuth() {
    const session = await this.getSession();
    if (!session) {
      window.location.href = "login.html";
      return null;
    }
    return session;
  },

  async requireStaff() {
    const session = await this.getSession();
    const role = localStorage.getItem("userRole");
    if (!session || role !== "staff") {
      window.location.href = "login.html";
      return null;
    }
    return session;
  },
};
