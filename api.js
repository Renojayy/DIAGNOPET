// =========================
// Diagnopet API Integration
// =========================

// 🔹 Example placeholder API configuration
const API_BASE_URL = "https://your-api-endpoint.com"; // Replace when available

// Save pet data (example)
async function savePetData(data) {
  try {
    const res = await fetch(${API_BASE_URL}/pets, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });
    return await res.json();
  } catch (err) {
    console.error("Failed to save pet data:", err);
    throw err;
  }
}

// Send symptoms to your backend for diagnosis or vet matching
async function sendSymptoms(symptoms) {
  try {
    const res = await fetch(${API_BASE_URL}/symptoms, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ symptoms }),
    });
    return await res.json();
  } catch (err) {
    console.error("Error sending symptoms:", err);
    throw err;
  }
}

// Fetch nearby vets (future geolocation integration)
async function getNearbyVets(location) {
  try {
    const res = await fetch(${API_BASE_URL}/vets?location=${encodeURIComponent(location)});
    return await res.json();
  } catch (err) {
    console.error("Error fetching vets:", err);
    throw err;
  }
}