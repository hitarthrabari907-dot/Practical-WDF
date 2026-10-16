USE studenthub;

-- Run only if these columns are not already present in studenthub.events.
-- In phpMyAdmin, inspect Structure first. If a column exists, skip its ALTER line.
ALTER TABLE events ADD COLUMN category VARCHAR(40) NOT NULL DEFAULT 'Technical';
ALTER TABLE events ADD COLUMN status ENUM('open','closed','coming_soon') NOT NULL DEFAULT 'open';
ALTER TABLE events ADD COLUMN poster_path VARCHAR(255) NULL;

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
