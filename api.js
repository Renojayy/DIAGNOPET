// api.js - communicates with your backend
const API_BASE_URL = "http://localhost:3000/api"; // backend URL

export async function sendSymptoms(symptoms) {
  try {
    const res = await fetch(`${API_BASE_URL}/symptoms`, {
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

export async function getNearbyVets(location) {
  try {
    const res = await fetch(`${API_BASE_URL}/vets?location=${encodeURIComponent(location)}`);
    return await res.json();
  } catch (err) {
    console.error("Error fetching vets:", err);
    throw err;
  }
}
