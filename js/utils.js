// ============================================================
// UTILITIES — Shared helpers used across all pages
// ============================================================

// Show an error message inside an element by ID
function showError(elementId, message) {
  const el = document.getElementById(elementId);
  if (el) { el.textContent = message; el.style.display = "block"; }
}

// Hide an error element
function hideError(elementId) {
  const el = document.getElementById(elementId);
  if (el) el.style.display = "none";
}

// Simple form serializer — returns { fieldName: value, ... }
function serializeForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return {};
  const data = {};
  new FormData(form).forEach((value, key) => { data[key] = value; });
  return data;
}

// Format date string to readable format
function formatDate(isoString) {
  if (!isoString) return "—";
  return new Date(isoString).toLocaleDateString("en-PH", {
    year: "numeric", month: "long", day: "numeric"
  });
}

// Toggle active tab (for tab-based pages like donor dashboard)
function switchTab(activeTabId, allTabIds, allPanelIds) {
  allTabIds.forEach((id, i) => {
    document.getElementById(id)?.classList.toggle("active", id === activeTabId);
    document.getElementById(allPanelIds[i])?.classList.toggle("hidden", id !== activeTabId);
  });
}

// ============================================================
// TODO: Add any additional helpers here
// Examples: formatBloodType(), calculateNextDonationDate(), etc.
// ============================================================
