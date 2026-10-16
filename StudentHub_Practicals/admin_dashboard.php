<?php
declare(strict_types=1);

require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/config/database.php';

start_secure_session();

if (empty($_SESSION['student_id'])) {
    header('Location: login.php');
    exit;
}

if (($_SESSION['role'] ?? 'student') !== 'admin') {
    http_response_code(403);
    page_start('Access denied');
    echo '<div class="notice error">This dashboard is available to administrator accounts only.</div>';
    page_end();
    exit;
}

try {
    $pdo = getDatabaseConnection();
    $stats = [];
    $stats['students'] = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    $stats['events'] = (int) $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
    $stats['registrations'] = (int) $pdo
        ->query("SELECT COUNT(*) FROM registrations WHERE status<>'cancelled'")
        ->fetchColumn();
    $stats['admins'] = (int) $pdo
        ->query("SELECT COUNT(*) FROM students WHERE role='admin'")
        ->fetchColumn();

    $latest = $pdo->query(
        'SELECT s.full_name,s.email,e.title,r.registered_at,r.status
        FROM registrations r
        JOIN students s ON s.student_id=r.student_id
        JOIN events e ON e.event_id=r.event_id
        ORDER BY r.registered_at DESC
        LIMIT 10'
    )->fetchAll();

    try {
        $logs = $pdo->query(
            'SELECT a.action,a.details,a.created_at,s.full_name
            FROM audit_logs a
            LEFT JOIN students s ON s.student_id=a.user_id
            ORDER BY a.created_at DESC
            LIMIT 12'
        )->fetchAll();
    } catch (Throwable $exception) {
        $logs = [];
    }
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    $stats = [];
    $latest = [];
    $logs = [];
    $error = 'Dashboard data could not be loaded. Import schema.sql or upgrade_practicals_12_15.sql.';
}

page_start('Practical 14 — Admin Dashboard');

if (isset($error)) {
    echo '<div class="notice error">' . e($error) . '</div>';
}

echo '<p>Welcome, ' . e((string) ($_SESSION['full_name'] ?? 'Administrator'))
    . '. Menu items below are shown based on your administrator role.</p>';
echo '<div class="form-grid">';

foreach ([
    'students' => 'Students',
    'events' => 'Events',
    'registrations' => 'Registrations',
    'admins' => 'Administrators',
] as $key => $label) {
    echo '<div class="practical-box">';
    echo '<p>' . e($label) . '</p>';
    echo '<h2 style="font-size:2rem">' . (int) ($stats[$key] ?? 0) . '</h2>';
    echo '</div>';
}

echo '</div>';
echo '<div class="practical-box">';
echo '<h2>Admin menu</h2>';
echo '<div class="practical-nav">';
echo '<a href="events_manager.php">Manage events &amp; posters</a>';
echo '<a href="practical13.php">AJAX / JSON CRUD</a>';
echo '<a href="students.php">Manage students</a>';
echo '<a href="events.php">View registrations</a>';
echo '<a href="contact_records.php">Contact submissions</a>';
echo '</div>';
echo '</div>';

echo '<div class="practical-box">';
echo '<h2>Latest registrations</h2>';
echo '<div style="overflow-x:auto">';
echo '<table>';
echo '<tr><th>Student</th><th>Email</th><th>Event</th><th>Registered</th><th>Status</th></tr>';

foreach ($latest as $registration) {
    echo '<tr>';
    echo '<td>' . e($registration['full_name']) . '</td>';
    echo '<td>' . e($registration['email']) . '</td>';
    echo '<td>' . e($registration['title']) . '</td>';
    echo '<td>' . e($registration['registered_at']) . '</td>';
    echo '<td>' . e($registration['status']) . '</td>';
    echo '</tr>';
}

if (!$latest) {
    echo '<tr><td colspan="5">No registrations yet.</td></tr>';
}

echo '</table>';
echo '</div>';
echo '</div>';

echo '<div class="practical-box">';
echo '<h2>Recent audit log</h2>';
echo '<div style="overflow-x:auto">';
echo '<table>';
echo '<tr><th>Time</th><th>User</th><th>Action</th><th>Details</th></tr>';

foreach ($logs as $log) {
    echo '<tr>';
    echo '<td>' . e($log['created_at']) . '</td>';
    echo '<td>' . e($log['full_name'] ?? 'Deleted user') . '</td>';
    echo '<td>' . e($log['action']) . '</td>';
    echo '<td>' . e($log['details']) . '</td>';
    echo '</tr>';
}

if (!$logs) {
    echo '<tr><td colspan="4">No audit entries yet. Event changes will be recorded after the audit table is installed.</td></tr>';
}

echo '</table>';
echo '</div>';
echo '</div>';

page_end();