<?php
require_once __DIR__ . "./bootstrap.php";

// Session configuration
ini_set("session.cookie_lifetime", getenv("SESSION_TIMEOUT"));
ini_set("session.gc_maxlifetime", getenv("SESSION_TIMEOUT"));

// Start session with security settings
session_start([
    "cookie_httponly" => true,
    "cookie_secure" => getenv("APP_ENV") === "production", // Enable in production with HTTPS
    "use_strict_mode" => true,
    "cookie_samesite" => "Strict",
]);

// Regenerate session ID periodically for security
if (!isset($_SESSION["last_regeneration"])) {
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
} elseif (
    time() - $_SESSION["last_regeneration"] >
    getenv("SESSION_REGENERATE_TIME")
) {
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}
