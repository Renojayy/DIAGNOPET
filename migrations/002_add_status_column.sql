-- Migration to add status column to veterinarian table
ALTER TABLE veterinarian
ADD COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending';
