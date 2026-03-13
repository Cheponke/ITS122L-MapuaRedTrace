const SUPABASE_URL = "https://lhidgnpmqbryzsjqwzor.supabase.co";
const SUPABASE_ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImxoaWRnbnBtcWJyeXpzanF3em9yIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzE5ODgwNTMsImV4cCI6MjA4NzU2NDA1M30.nFp6TRrTGOLxIvcuC3rC_iaN7Hl-taL9qHcCijFvfjA";

const supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

const TABLES = {
  USERS: "users",
  DONORS: "donors",
  STAFF: "staff",
  SELF_ASSESSMENT: "self_assessments",
  DONATION_PROCESS: "donation_process",
  BLOOD_INVENTORY: "blood_inventory",
  NOTIFICATIONS: "notifications",
};
