USE studenthub;

-- Run this file only if you already have the original StudentHub database.
-- These three columns are required by register.php, login.php and students.php.
ALTER TABLE students
  ADD COLUMN password_hash VARCHAR(255) NULL,
  ADD COLUMN role ENUM('student','faculty','admin') NOT NULL DEFAULT 'student',
  ADD COLUMN course VARCHAR(120) NOT NULL DEFAULT 'Not specified';

CREATE TABLE IF NOT EXISTS contact_messages (
  message_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(254) NOT NULL,
  category VARCHAR(60) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO events (title, description, event_date, location, capacity)
SELECT 'Web Development Masterclass', 'Build a responsive interface with semantic HTML, modern CSS layouts, and JavaScript.', '2026-10-18 10:00:00', 'Computer Lab 304', 40
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Web Development Masterclass');

INSERT INTO events (title, description, event_date, location, capacity)
SELECT 'Cybersecurity: The Human Factor', 'A guest lecture about practical security habits and threat modeling.', '2026-10-24 14:00:00', 'Seminar Hall 1', 80
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Cybersecurity: The Human Factor');

INSERT INTO events (title, description, event_date, location, capacity)
SELECT 'Annual Youth Festival', 'Music, drama, quiz competitions, and student art exhibitions.', '2026-11-05 09:30:00', 'University Grounds', 200
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Annual Youth Festival');
