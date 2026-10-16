<?php
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/config/database.php';

start_secure_session();

$error = '';
$success = '';
$name = '';
$email = '';
$course = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $course = trim((string) ($_POST['course'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    if (!csrf_valid()) {
        $error = 'Invalid or expired form. Refresh and try again.';
    } elseif (!preg_match('/^[\p{L}][\p{L} .-]{1,98}$/u', $name)) {
        $error = 'Enter a valid full name (2–99 characters).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (
        strlen($password) < 8
        || !preg_match('/[A-Z]/', $password)
        || !preg_match('/[a-z]/', $password)
        || !preg_match('/[0-9]/', $password)
    ) {
        $error = 'Password must be at least 8 characters and contain uppercase, lowercase and a number.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $pdo = getDatabaseConnection();
            $check = $pdo->prepare('SELECT student_id FROM students WHERE email=?');
            $check->execute([$email]);

            if ($check->fetch()) {
                $error = 'That email is already registered.';
            } else {
                $insert = $pdo->prepare(
                    "INSERT INTO students(full_name,email,password_hash,role,course) VALUES(?,?,?,'student',?)"
                );
                $insert->execute([
                    $name,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT),
                    $course ?: 'Not specified',
                ]);

                $success = 'Registration successful. You can now log in.';
                $name = $email = $course = '';
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $error = 'Could not save registration. Confirm that schema.sql has been imported.';
        }
    }
}

page_start('Secure Student Registration');

if ($error !== '') {
    echo '<div class="notice error">' . e($error) . '</div>';
}

if ($success !== '') {
    echo '<div class="notice">' . e($success) . ' <a href="login.php">Login here</a>.</div>';
}

echo '<div class="practical-box">';
echo '<form method="post">';
echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
echo '<label>Full name<input name="name" required maxlength="100" value="' . e($name) . '"></label>';
echo '<label>Email<input type="email" name="email" required maxlength="254" value="' . e($email) . '"></label>';
echo '<label>Course / Program<input name="course" maxlength="120" value="' . e($course) . '"></label>';
echo '<div class="form-grid">';
echo '<label>Password<input type="password" name="password" required minlength="8" autocomplete="new-password"></label>';
echo '<label>Confirm password<input type="password" name="confirm_password" required minlength="8" autocomplete="new-password"></label>';
echo '</div>';
echo '<button class="btn btn-primary" type="submit">Create account</button>';
echo '</form>';
echo '</div>';

page_end();
