<?php
// Check if .env file exists
if (!file_exists(__DIR__ . "./../.env")) {
    die("Please create a .env file");
}

// Load .env file
$lines = file(
    __DIR__ . "/../.env",
    FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
);
foreach ($lines as $line) {
    if (strpos(trim($line), "#") === 0) {
        continue; // Skip comments
    }

    list($name, $value) = explode("=", $line, 2);
    $name = trim($name);
    $value = trim($value);

    if (!array_key_exists($name, $_ENV)) {
        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

// Set default timezone
date_default_timezone_set("UTC");

// Error reporting
if (getenv("APP_ENV") === "development" || getenv("APP_DEBUG") === "true") {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
} else {
    error_reporting(0);
    ini_set("display_errors", 0);
}
