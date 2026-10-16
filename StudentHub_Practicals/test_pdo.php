<?php

require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDatabaseConnection();

    echo "<h2>PDO Connection Successful!</h2>";

    $stmt = $pdo->prepare(
        "SELECT * FROM students WHERE student_id = :id"
    );

    $stmt->execute(['id' => 1]);

    $student = $stmt->fetch();

    echo "<h3>Prepared Statement Result</h3>";

    if ($student) {
        echo "<pre>";
        echo htmlspecialchars(print_r($student, true));
        echo "</pre>";
    } else {
        echo "Connection works, but no student with ID 1 was found.";
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo "Database test failed. Check your configuration.";
}