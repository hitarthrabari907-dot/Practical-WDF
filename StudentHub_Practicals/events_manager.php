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
    echo '<div class="notice error">Administrator access is required.</div>';
    page_end();
    exit;
}

$pdo = getDatabaseConnection();
$message = '';
$error = '';
$uploadDir = __DIR__ . '/uploads/event-posters/';
$uploadUrl = 'uploads/event-posters/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

function savePoster(array $file, string $uploadDir, string $uploadUrl): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Poster upload failed. Please try again.');
    }

    if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        throw new RuntimeException('Poster must be 2 MB or smaller.');
    }

    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->file($file['tmp_name']);
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowedTypes[$mimeType]) || @getimagesize($file['tmp_name']) === false) {
        throw new RuntimeException('Only valid JPG, PNG, or WEBP image files are allowed.');
    }

    $fileName = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
        throw new RuntimeException('Could not save the poster file.');
    }

    return $uploadUrl . $fileName;
}

function auditEvent(PDO $pdo, int $userId, string $action, string $details): void
{
    try {
        $query = $pdo->prepare(
            'INSERT INTO audit_logs(user_id,action,details) VALUES(?,?,?)'
        );
        $query->execute([$userId, $action, $details]);
    } catch (Throwable $exception) {
        error_log('Audit log insert failed: ' . $exception->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $error = 'Your form expired. Refresh and try again.';
    } else {
        $action = (string) ($_POST['action'] ?? '');
        $id = filter_var($_POST['event_id'] ?? 0, FILTER_VALIDATE_INT) ?: 0;

        try {
            if ($action === 'delete' && $id > 0) {
                $query = $pdo->prepare('SELECT poster_path FROM events WHERE event_id=?');
                $query->execute([$id]);
                $oldEvent = $query->fetch();

                $query = $pdo->prepare('DELETE FROM events WHERE event_id=?');
                $query->execute([$id]);

                if ($oldEvent && !empty($oldEvent['poster_path'])) {
                    $posterPath = __DIR__ . '/' . $oldEvent['poster_path'];

                    if (is_file($posterPath)) {
                        @unlink($posterPath);
                    }
                }

                auditEvent(
                    $pdo,
                    (int) $_SESSION['student_id'],
                    'event.delete',
                    'Deleted event ID ' . $id
                );
                $message = 'Event deleted successfully.';
            } elseif (in_array($action, ['add', 'update'], true)) {
                $title = trim((string) ($_POST['title'] ?? ''));
                $description = trim((string) ($_POST['description'] ?? ''));
                $date = trim((string) ($_POST['event_date'] ?? ''));
                $location = trim((string) ($_POST['location'] ?? ''));
                $capacity = filter_var($_POST['capacity'] ?? 100, FILTER_VALIDATE_INT);
                $category = trim((string) ($_POST['category'] ?? 'Technical'));
                $status = (string) ($_POST['status'] ?? 'open');

                if (
                    $title === ''
                    || mb_strlen($title) > 150
                    || $description === ''
                    || mb_strlen($description) > 500
                    || $location === ''
                    || mb_strlen($location) > 150
                    || !$date
                    || strtotime($date) === false
                    || !$capacity
                    || $capacity < 1
                    || $capacity > 50000
                ) {
                    throw new RuntimeException(
                        'Check the title, description, date, location, and capacity fields.'
                    );
                }

                if (!in_array($category, ['Technical', 'Workshop', 'Seminar', 'Cultural', 'Sports', 'Other'], true)) {
                    throw new RuntimeException('Choose a valid category.');
                }

                if (!in_array($status, ['open', 'closed', 'coming_soon'], true)) {
                    throw new RuntimeException('Choose a valid registration status.');
                }

                $poster = savePoster($_FILES['poster'] ?? [], $uploadDir, $uploadUrl);

                if ($action === 'add') {
                    $query = $pdo->prepare(
                        'INSERT INTO events(title,description,event_date,location,capacity,category,status,poster_path)
                        VALUES(?,?,?,?,?,?,?,?)'
                    );
                    $query->execute([
                        $title,
                        $description,
                        date('Y-m-d H:i:s', strtotime($date)),
                        $location,
                        $capacity,
                        $category,
                        $status,
                        $poster,
                    ]);

                    auditEvent(
                        $pdo,
                        (int) $_SESSION['student_id'],
                        'event.create',
                        'Created event: ' . $title
                    );
                    $message = 'Event added successfully.';
                } else {
                    $query = $pdo->prepare('SELECT poster_path FROM events WHERE event_id=?');
                    $query->execute([$id]);
                    $oldEvent = $query->fetch();

                    if (!$oldEvent) {
                        throw new RuntimeException('Event not found.');
                    }

                    $posterPath = $poster ?? $oldEvent['poster_path'];
                    $query = $pdo->prepare(
                        'UPDATE events
                        SET title=?,description=?,event_date=?,location=?,capacity=?,category=?,status=?,poster_path=?
                        WHERE event_id=?'
                    );
                    $query->execute([
                        $title,
                        $description,
                        date('Y-m-d H:i:s', strtotime($date)),
                        $location,
                        $capacity,
                        $category,
                        $status,
                        $posterPath,
                        $id,
                    ]);

                    if ($poster && !empty($oldEvent['poster_path'])) {
                        $oldPosterPath = __DIR__ . '/' . $oldEvent['poster_path'];

                        if (is_file($oldPosterPath)) {
                            @unlink($oldPosterPath);
                        }
                    }

                    auditEvent(
                        $pdo,
                        (int) $_SESSION['student_id'],
                        'event.update',
                        'Updated event ID ' . $id . ': ' . $title
                    );
                    $message = 'Event updated successfully.';
                }
            }
        } catch (Throwable $exception) {
            $error = $exception instanceof RuntimeException
                ? $exception->getMessage()
                : 'Could not complete the event operation. Check the database schema and try again.';
            error_log($exception->getMessage());
        }
    }
}

$edit = null;

if (isset($_GET['edit']) && ctype_digit((string) $_GET['edit'])) {
    $query = $pdo->prepare('SELECT * FROM events WHERE event_id=?');
    $query->execute([(int) $_GET['edit']]);
    $edit = $query->fetch() ?: null;
}

$rows = $pdo->query(
    "SELECT e.*,
        (SELECT COUNT(*) FROM registrations r
        WHERE r.event_id=e.event_id AND r.status<>'cancelled') AS registration_count
    FROM events e
    ORDER BY event_date DESC"
)->fetchAll();

page_start('Practical 12 — Event Management CRUD');

echo '<p>Admin-only event CRUD with validated poster uploads. Allowed formats: JPG, PNG, WEBP; maximum size: 2 MB.</p>';

if ($message !== '') {
    echo '<div class="notice">' . e($message) . '</div>';
}

if ($error !== '') {
    echo '<div class="notice error">' . e($error) . '</div>';
}

$values = $edit ?: [
    'event_id' => '',
    'title' => '',
    'description' => '',
    'event_date' => '',
    'location' => '',
    'capacity' => 100,
    'category' => 'Technical',
    'status' => 'open',
    'poster_path' => null,
];

echo '<section class="practical-box">';
echo '<h2>' . ($edit ? 'Update event' : 'Add event') . '</h2>';
echo '<form method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
echo '<input type="hidden" name="action" value="' . ($edit ? 'update' : 'add') . '">';
echo '<input type="hidden" name="event_id" value="' . (int) $values['event_id'] . '">';
echo '<div class="form-grid">';
echo '<label>Event title<input name="title" maxlength="150" required value="' . e($values['title']) . '"></label>';
echo '<label>Date and time<input type="datetime-local" name="event_date" required value="'
    . e($values['event_date'] ? date('Y-m-d\TH:i', strtotime($values['event_date'])) : '')
    . '"></label>';
echo '<label>Location<input name="location" maxlength="150" required value="' . e($values['location']) . '"></label>';
echo '<label>Capacity<input type="number" name="capacity" min="1" max="50000" required value="'
    . (int) $values['capacity']
    . '"></label>';
echo '<label>Category<select name="category">';

foreach (['Technical', 'Workshop', 'Seminar', 'Cultural', 'Sports', 'Other'] as $category) {
    echo '<option' . ($values['category'] === $category ? ' selected' : '') . '>'
        . e($category)
        . '</option>';
}

echo '</select></label>';
echo '<label>Registration status<select name="status">';

foreach (['open' => 'Open', 'closed' => 'Closed', 'coming_soon' => 'Coming soon'] as $key => $label) {
    echo '<option value="' . e($key) . '"' . ($values['status'] === $key ? ' selected' : '') . '>'
        . e($label)
        . '</option>';
}

echo '</select></label>';
echo '</div>';
echo '<label>Description<textarea name="description" maxlength="500" required rows="3">'
    . e($values['description'])
    . '</textarea></label>';
echo '<label>Poster image (optional)<input type="file" name="poster" accept="image/jpeg,image/png,image/webp">';
echo '<small>JPG, PNG or WEBP only. Maximum 2 MB.</small></label>';

if (!empty($values['poster_path'])) {
    echo '<p>Current poster: <img src="' . e($values['poster_path'])
        . '" alt="Current event poster" style="max-width:140px;max-height:100px;object-fit:cover"></p>';
}

echo '<button class="btn btn-primary">' . ($edit ? 'Save changes' : 'Add event') . '</button> ';

if ($edit) {
    echo '<a href="events_manager.php">Cancel</a>';
}

echo '</form>';
echo '</section>';

echo '<section class="practical-box">';
echo '<h2>Existing events</h2>';
echo '<div style="overflow-x:auto">';
echo '<table>';
echo '<thead><tr><th>Poster</th><th>Event</th><th>Category / status</th><th>Date</th><th>Registrations</th><th>Actions</th></tr></thead>';
echo '<tbody>';

foreach ($rows as $row) {
    echo '<tr>';
    echo '<td>'
        . (!empty($row['poster_path'])
            ? '<img src="' . e($row['poster_path']) . '" alt="Poster for ' . e($row['title'])
                . '" style="width:72px;height:55px;object-fit:cover">'
            : '—')
        . '</td>';
    echo '<td><strong>' . e($row['title']) . '</strong><br>' . e($row['location']) . '</td>';
    echo '<td>' . e($row['category']) . '<br>' . e(str_replace('_', ' ', $row['status'])) . '</td>';
    echo '<td>' . e($row['event_date']) . '</td>';
    echo '<td>' . (int) $row['registration_count'] . ' / ' . (int) $row['capacity'] . '</td>';
    echo '<td>';
    echo '<a href="?edit=' . (int) $row['event_id'] . '">Edit</a>';
    echo '<form method="post" style="display:inline" onsubmit="return confirm(\'Delete this event? Related registrations will also be removed.\')">';
    echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    echo '<input type="hidden" name="action" value="delete">';
    echo '<input type="hidden" name="event_id" value="' . (int) $row['event_id'] . '">';
    echo '<button class="btn btn-primary" type="submit">Delete</button>';
    echo '</form>';
    echo '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';
echo '</section>';

page_end();