<?php
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/config/database.php';

page_start('Database Connection Test');

try {
    $pdo = getDatabaseConnection();
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    echo '<div class="notice">Database connected successfully.</div>';
    echo '<div class="practical-box">';
    echo '<h2>Tables found</h2>';
    echo '<ul>';

    foreach ($tables as $table) {
        echo '<li>' . e((string) $table) . '</li>';
    }

    echo '</ul>';
    echo '</div>';
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    echo '<div class="notice error"><strong>Connection failed.</strong><br>'
        . e($exception->getMessage())
        . '<p>Start MySQL in XAMPP and import schema.sql using phpMyAdmin.</p></div>';
}

page_end();
