<?php
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/config/database.php';

start_secure_session();

$message = '';
$error = '';

try {
    $pdo = getDatabaseConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
        if (empty($_SESSION['student_id'])) {
            $error = 'Please register and log in before registering for an event.';
        } elseif (!csrf_valid()) {
            $error = 'Form expired. Refresh and try again.';
        } else {
            $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
            $query = $pdo->prepare(
                'INSERT INTO registrations(student_id,event_id) VALUES(:s,:e)'
            );

            try {
                $query->execute([
                    's' => $_SESSION['student_id'],
                    'e' => $eventId,
                ]);
                $message = 'You have registered for the event.';
            } catch (PDOException $exception) {
                $error = $exception->getCode() === '23000'
                    ? 'You are already registered for this event.'
                    : 'Could not register for this event.';
            }
        }
    }

    $events = $pdo->query(
        "SELECT e.*,
            (SELECT COUNT(*) FROM registrations r
            WHERE r.event_id=e.event_id AND r.status<>'cancelled') AS registered_count
        FROM events e
        ORDER BY event_date"
    )->fetchAll();

    $registrations = $pdo->query(
        'SELECT s.full_name,s.email,e.title,e.event_date,r.status
        FROM registrations r
        JOIN students s ON s.student_id=r.student_id
        JOIN events e ON e.event_id=r.event_id
        ORDER BY e.event_date,s.full_name'
    )->fetchAll();
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    $error = 'Database is unavailable. Start MySQL and import schema.sql.';
    $events = [];
    $registrations = [];
}

page_start('Events and Registrations');

if ($message !== '') {
    echo '<div class="notice">' . e($message) . '</div>';
}

if ($error !== '') {
    echo '<div class="notice error">' . e($error) . '</div>';
}

echo '<div class="practical-box">';
echo '<h2>Upcoming Events</h2>';

if (!$events) {
    echo '<p>No events available yet.</p>';
} else {
    echo '<div style="overflow-x:auto">';
    echo '<table>';
    echo '<tr><th>Event</th><th>Date</th><th>Location</th><th>Seats</th><th>Action</th></tr>';

    foreach ($events as $event) {
        echo '<tr>';
        echo '<td><strong>' . e($event['title']) . '</strong><br>' . e($event['description']) . '</td>';
        echo '<td>' . e($event['event_date']) . '</td>';
        echo '<td>' . e($event['location']) . '</td>';
        echo '<td>' . (int) $event['registered_count'] . ' / ' . (int) $event['capacity'] . '</td>';
        echo '<td>';
        echo '<form method="post">';
        echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
        echo '<input type="hidden" name="event_id" value="' . (int) $event['event_id'] . '">';
        echo '<button class="btn btn-primary" type="submit">Register</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '</div>';
}

echo '</div>';
echo '<div class="practical-box">';
echo '<h2>Registrations</h2>';

if (!$registrations) {
    echo '<p>No registrations yet.</p>';
} else {
    echo '<div style="overflow-x:auto">';
    echo '<table>';
    echo '<tr><th>Student</th><th>Email</th><th>Event</th><th>Date</th><th>Status</th></tr>';

    foreach ($registrations as $registration) {
        echo '<tr>';
        echo '<td>' . e($registration['full_name']) . '</td>';
        echo '<td>' . e($registration['email']) . '</td>';
        echo '<td>' . e($registration['title']) . '</td>';
        echo '<td>' . e($registration['event_date']) . '</td>';
        echo '<td>' . e($registration['status']) . '</td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '</div>';
}

echo '</div>';

page_end();
