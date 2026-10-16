STUDENTHUB + PHP PRACTICALS 7–15
================================

WHAT IS INCLUDED
- Original StudentHub HTML/CSS pages, shared stylesheet, JavaScript and sample event JSON.
- Practical 7: contact form with POST, server-side validation, CSRF token, CSV storage and record viewer.
- Practical 8: MySQL schema for students/events/registrations, PDO connection test and ER relationship notes.
- Practical 9: secure student registration with duplicate email checking and password_hash().
- Practical 10: login/logout, password_verify(), session ID regeneration, 30-minute idle timeout and role-aware redirect.
- Practical 11: student list, search, add, edit and delete using prepared statements, restricted to admin role.
- Practical 12: admin-only event CRUD, category/status, validated JPG/PNG/WEBP poster upload (2 MB limit), random filenames and audit records.
- Practical 13: JSON event API and Fetch-based add/update/delete/search without page reload, CSRF validation and JSON error/status handling.
- Practical 14: admin-only dashboard with database counts, latest registrations, role-aware admin menu and audit log.
- Practical 15: final integration guide, local deployment, security/accessibility checklist, test cases and GitHub submission instructions.
- Database-backed event and registration listing with event registration.

INSTALL IN XAMPP
1. Extract this folder into C:\xampp\htdocs\StudentHub_Practicals
2. Open XAMPP Control Panel and start Apache and MySQL.
3. Visit http://localhost/phpmyadmin
4. Import StudentHub_Practicals/schema.sql (select the file from this folder). This creates/uses the studenthub database and adds sample events.
5. If you already have an older studenthub database, do NOT re-import schema.sql blindly. Back up first, then import upgrade_existing_database.sql instead.
6. Visit http://localhost/StudentHub_Practicals/; index.php is the integrated portal home. Use the shared navigation or Learning Lab to open practicals 7–15.
7. Test the PDO connection at http://localhost/StudentHub_Practicals/db_connection.php.

DEFAULT DATABASE SETTINGS
Host localhost; database studenthub; username root; empty password. If your MySQL settings differ, copy config/database.local.php.example to config/database.local.php and edit the values.

ADMIN CRUD PRACTICALS
Register an account using register.php. In phpMyAdmin > studenthub > students > Browse, change your test account role to admin. Log in again and open admin_dashboard.php, events_manager.php, students.php or practical13.php. Do not use this shortcut on a public/production system.

UPGRADING PRACTICALS 12–15
If you already have the Practical 7–11 database, back it up and inspect the events table. Run upgrade_practicals_12_15.sql, skipping any ALTER TABLE statement for a column that already exists. Then test the connection and administrator pages. For a fresh database, import schema.sql instead.

NOTES
- Import schema.sql in a fresh lab database or back up existing data first. If an older studenthub database already exists, use upgrade_existing_database.sql after backing up your database. It adds the required columns for the current original schema. If you have already added any of these columns yourself, remove those ADD COLUMN clauses before running the migration.
- The static register.html and login.html remain available as original design examples only; register.html does not create an account. Use register.php and login.php for real accounts and sign-in.
- PHP PDO MySQL extension must be enabled in XAMPP.

INTEGRATED ENTRY POINTS
- index.php: unified StudentHub home and PHP-backed navigation.
- practicals.php: integrated Learning Lab for practicals 7–15.
- practical15.php: final setup and testing guide inside the portal.
- Shared PHP pages use includes/helpers.php for consistent header, navigation, footer, theme styling, and session-aware admin links.
