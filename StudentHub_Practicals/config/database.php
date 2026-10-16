<?php
declare(strict_types=1);
function getDatabaseConnection(): PDO {
    $settings = ['host'=>'localhost','database'=>'studenthub','username'=>'root','password'=>''];
    $local = __DIR__ . '/database.local.php';
    if (is_file($local)) 
        {
             $custom = require $local;
              if (is_array($custom)) $settings = array_merge($settings, $custom);
               }
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $settings['host'], $settings['database']);
    return new PDO($dsn, $settings['username'], $settings['password'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]);
}
