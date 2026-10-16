CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub;
CREATE TABLE IF NOT EXISTS students (
 student_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(100) NOT NULL,
 email VARCHAR(254) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NULL,
 role ENUM('student','faculty','admin') NOT NULL DEFAULT 'student',
 course VARCHAR(120) NOT NULL DEFAULT 'Not specified',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS events (
 event_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(150) NOT NULL,
 description VARCHAR(500) NOT NULL,
 event_date DATETIME NOT NULL,
 location VARCHAR(150) NOT NULL,
 capacity SMALLINT UNSIGNED NOT NULL DEFAULT 100,
 category VARCHAR(40) NOT NULL DEFAULT 'Technical',
 status ENUM('open','closed','coming_soon') NOT NULL DEFAULT 'open',
 poster_path VARCHAR(255) NULL
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS registrations (
 student_id INT UNSIGNED NOT NULL,
 event_id INT UNSIGNED NOT NULL,
 registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 status ENUM('registered','attended','cancelled') NOT NULL DEFAULT 'registered',
 PRIMARY KEY(student_id,event_id), KEY idx_registrations_event(event_id),
 CONSTRAINT fk_registrations_student FOREIGN KEY(student_id) REFERENCES students(student_id) ON UPDATE CASCADE ON DELETE CASCADE,
 CONSTRAINT fk_registrations_event FOREIGN KEY(event_id) REFERENCES events(event_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS contact_messages (
 message_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(100) NOT NULL, email VARCHAR(254) NOT NULL,
 category VARCHAR(60) NOT NULL, message TEXT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO events(title,description,event_date,location,capacity)
SELECT 'Web Development Masterclass','Build a responsive interface with semantic HTML, modern CSS layouts, and JavaScript.','2026-10-18 10:00:00','Computer Lab 304',40
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Web Development Masterclass');
INSERT INTO events(title,description,event_date,location,capacity)
SELECT 'Cybersecurity: The Human Factor','A guest lecture about practical security habits and threat modeling.','2026-10-24 14:00:00','Seminar Hall 1',80
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Cybersecurity: The Human Factor');
INSERT INTO events(title,description,event_date,location,capacity)
SELECT 'Annual Youth Festival','Music, drama, quiz competitions, and student art exhibitions.','2026-11-05 09:30:00','University Grounds',200
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Annual Youth Festival');


CREATE TABLE IF NOT EXISTS audit_logs (
 audit_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NULL,
 action VARCHAR(80) NOT NULL,
 details VARCHAR(500) NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY idx_audit_created(created_at),
 KEY idx_audit_user(user_id),
 CONSTRAINT fk_audit_user FOREIGN KEY(user_id) REFERENCES students(student_id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;
