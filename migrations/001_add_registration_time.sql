-- Migration to add registration_time column to veterinarian table
ALTER TABLE veterinarian
ADD COLUMN registration_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
