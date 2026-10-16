<?php
require __DIR__ . '/includes/helpers.php';

start_secure_session();

$error = '';
$success = '';
$name = '';
$email = '';
$category = 'general';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $category = trim((string) ($_POST['category'] ?? 'general'));
    $message = trim((string) ($_POST['message'] ?? ''));

    if (!csrf_valid()) {
        $error = 'Invalid form token. Refresh and try again.';
    } elseif (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        $error = 'Name must be between 2 and 100 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (!in_array($category, ['academic', 'technical', 'events', 'general'], true)) {
        $error = 'Choose a valid category.';
    } elseif (mb_strlen($message) < 5 || mb_strlen($message) > 3000) {
        $error = 'Message must be between 5 and 3000 characters.';
    } else {
        $file = __DIR__ . '/data/contact_submissions.csv';
        $fileHandle = fopen($file, 'ab');

        if ($fileHandle) {
            if (flock($fileHandle, LOCK_EX)) {
                fputcsv($fileHandle, [date('c'), $name, $email, $category, $message]);
                flock($fileHandle, LOCK_UN);
                $success = 'Your inquiry was saved successfully.';
                $name = $email = $message = '';
            } else {
                $error = 'Could not lock the storage file.';
            }

            fclose($fileHandle);
        } else {
            $error = 'Could not write to CSV storage.';
        }
    }
}

page_start('Contact Form — ');

if ($error !== '') {
    echo '<div class="notice error">' . e($error) . '</div>';
}

if ($success !== '') {
    echo '<div class="notice">' . e($success)
        . ' <a href="contact_records.php">View saved records</a></div>';
}

echo '<div class="practical-box">';
echo '<p></p>';
echo '<form method="post">';
echo '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
echo '<label>Full name<input name="name" maxlength="100" required value="' . e($name) . '"></label>';
echo '<label>Email<input type="email" name="email" required value="' . e($email) . '"></label>';
echo '<label>Category<select name="category">';
echo '<option value="general">General</option>';
echo '<option value="academic">Academic &amp; exams</option>';
echo '<option value="technical">Technical support</option>';
echo '<option value="events">Events</option>';
echo '</select></label>';
echo '<label>Message<textarea name="message" rows="5" maxlength="3000" required>'
    . e($message)
    . '</textarea></label>';
echo '<button class="btn btn-primary">Submit inquiry</button>';
echo '</form>';
echo '</div>';

page_end();