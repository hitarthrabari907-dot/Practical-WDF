<?php
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
    echo '<div class="notice error">Administrator access is required. For local practical testing, register a user and change their role to admin in phpMyAdmin.</div>';
    page_end();
    exit;
}

$pdo = getDatabaseConnection();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $error = 'Invalid form token.';
    } else {
        $action = $_POST['action'] ?? '';
        $id = filter_var($_POST['student_id'] ?? 0, FILTER_VALIDATE_INT);

        try {
            if ($action === 'delete' && $id) {
                $query = $pdo->prepare('DELETE FROM students WHERE student_id=?');
                $query->execute([$id]);
                $message = 'Student deleted.';
            } elseif (in_array($action, ['add', 'edit'], true)) {
                $name = trim((string) ($_POST['full_name'] ?? ''));
                $email = strtolower(trim((string) ($_POST['email'] ?? '')));
                $course = trim((string) ($_POST['course'] ?? ''));

                if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Enter a name and valid email.';
                } elseif ($action === 'add') {
                    $query = $pdo->prepare(
                        'INSERT INTO students(full_name,email,course) VALUES(?,?,?)'
                    );
                    $query->execute([$name, $email, $course ?: 'Not specified']);
                    $message = 'Student added.';
                } elseif ($id) {
                    $query = $pdo->prepare(
                        'UPDATE students SET full_name=?,email=?,course=? WHERE student_id=?'
                    );
                    $query->execute([$name, $email, $course ?: 'Not specified', $id]);
                    $message = 'Student updated.';
                }
            }
        } catch (PDOException $exception) {
            $error = $exception->getCode() === '23000'
                ? 'That email already exists.'
                : 'Could not complete operation.';
            error_log($exception->getMessage());
        }
    }
}

$search = trim((string) ($_GET['q'] ?? ''));
$query = $pdo->prepare(
    'SELECT student_id,full_name,email,course,role,created_at
    FROM students
    WHERE full_name LIKE ? OR email LIKE ? OR course LIKE ?
    ORDER BY student_id DESC'
);
$like = '%' . $search . '%';
$query->execute([$like, $like, $like]);
$rows = $query->fetchAll();

page_start('Student Management CRUD');

if ($message !== '') {
    echo '<div class="notice">' . e($message) . '</div>';
}

if ($error !== '') {
    echo '<div class="notice error">' . e($error) . '</div>';
}

echo '<div class="practical-box">';
echo '<h2>Add Student</h2>';
echo '<form method="post">';
echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
echo '<input type="hidden" name="action" value="add">';
echo '<div class="form-grid">';
echo '<label>Full name<input name="full_name" required></label>';
echo '<label>Email<input type="email" name="email" required></label>';
echo '<label>Course<input name="course"></label>';
echo '</div>';
echo '<button class="btn btn-primary">Add student</button>';
echo '</form>';
echo '</div>';

echo '<div class="practical-box">';
echo '<h2>Search and Filter</h2>';
echo '<form method="get">';
echo '<label>Search name, email or course<input name="q" value="' . e($search) . '"></label>';
echo '<button class="btn btn-primary">Search</button> <a href="students.php">Clear</a>';
echo '</form>';
echo '<div style="overflow-x:auto">';
echo '<table>';
echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Role</th><th>Delete</th></tr>';

foreach ($rows as $row) {
    $studentId = (int) $row['student_id'];

    echo '<tr>';
    echo '<td>' . $studentId . '</td>';
    echo '<td colspan="3">';
    echo '<form method="post" class="form-grid">';
    echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    echo '<input type="hidden" name="action" value="edit">';
    echo '<input type="hidden" name="student_id" value="' . $studentId . '">';
    echo '<label>Name<input name="full_name" required value="' . e($row['full_name']) . '"></label>';
    echo '<label>Email<input type="email" name="email" required value="' . e($row['email']) . '"></label>';
    echo '<label>Course<input name="course" value="' . e($row['course']) . '"></label>';
    echo '<button class="btn btn-primary">Save changes</button>';
    echo '</form>';
    echo '</td>';
    echo '<td>' . e($row['role']) . '</td>';
    echo '<td>';
    echo '<form method="post" onsubmit="return confirm(&quot;Delete this student?&quot;)">';
    echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    echo '<input type="hidden" name="action" value="delete">';
    echo '<input type="hidden" name="student_id" value="' . $studentId . '">';
    echo '<button class="btn btn-primary">Delete</button>';
    echo '</form>';
    echo '</td>';
    echo '</tr>';
}

echo '</table>';
echo '</div>';
echo '<p>' . count($rows) . ' student record(s) shown.</p>';
echo '</div>';

page_end();
