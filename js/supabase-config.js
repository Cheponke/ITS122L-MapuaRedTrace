// ============================================================
// SUPABASE CONFIGURATION
// Replace the values below with your actual Supabase credentials
// Found in: Supabase Dashboard > Project Settings > API
// ============================================================

const SUPABASE_URL = "https://lhidgnpmqbryzsjqwzor.supabase.co";          // e.g. https://xyzabcdef.supabase.co
const SUPABASE_ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImxoaWRnbnBtcWJyeXpzanF3em9yIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzE5ODgwNTMsImV4cCI6MjA4NzU2NDA1M30.nFp6TRrTGOLxIvcuC3rC_iaN7Hl-taL9qHcCijFvfjA"; // e.g. eyJhbGciOiJIUzI1NiIsInR5...

// Initialize Supabase client
// Requires: <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
const supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

// ============================================================
// DATABASE TABLE NAMES (match your Supabase schema)
// Update these if your table names differ
// ============================================================
const TABLES = {
  USERS: "users",
  DONORS: "donors",
  STAFF: "staff",
  SELF_ASSESSMENT: "self_assessments",
  DONATION_PROCESS: "donation_process",
  BLOOD_INVENTORY: "blood_inventory",
  NOTIFICATIONS: "notifications",
};
