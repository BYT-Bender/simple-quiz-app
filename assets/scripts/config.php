<?php
    function loadEnv($file) {
        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '#') === 0) continue;
                list($key, $value) = explode('=', $line, 2);
                putenv(trim($key) . '=' . trim($value));
            }
        }
    }

    loadEnv(__DIR__ . '/.env');

    // Database configuration
    define('DB_SERVER', getenv('DB_SERVER') ?: 'localhost');
    define('DB_USERNAME', getenv('DB_USERNAME') ?: 'root');
    define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
    define('DB_DATABASE', getenv('DB_DATABASE') ?: 'quiz_app');

    // Create a database connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error); 
    }

    $conn->set_charset('utf8');
?>