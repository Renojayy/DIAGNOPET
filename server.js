// server.js
import express from "express";
import cors from "cors";
import dotenv from "dotenv";
import fetch from "node-fetch"; // Node <18

dotenv.config();
const app = express();
app.use(cors());
app.use(express.json());

const API_BASE_URL = "https://third-party-api.com"; // Replace with your real API
const API_KEY = process.env.API_KEY;
const PORT = process.env.PORT || 3000;

// Endpoint to process symptoms
app.post("/api/symptoms", async (req, res) => {
  const { symptoms } = req.body;
  try {
    const response = await fetch(`${API_BASE_URL}/symptoms`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${API_KEY}`
      },
      body: JSON.stringify({ symptoms })
    });
    const data = await response.json();
    res.json(data);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: "Failed to fetch from API" });
  }
});

// Endpoint to fetch nearby vets
app.get("/api/vets", async (req, res) => {
  const location = req.query.location;
  try {
    const response = await fetch(`${API_BASE_URL}/vets?location=${encodeURIComponent(location)}`, {
      headers: { "Authorization": `Bearer ${API_KEY}` }
    });
    const data = await response.json();
    res.json(data);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: "Failed to fetch vets" });
  }
});

app.listen(PORT, () => console.log(`Backend running on http://localhost:${PORT}`));
