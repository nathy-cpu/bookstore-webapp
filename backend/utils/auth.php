<?php
require_once __DIR__ . "./../config/bootstrap.php";

function authenticateUser()
{
    if (!isset($_SESSION["user_id"])) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit();
    }
}

function authenticateAdmin()
{
    authenticateUser();
    if (!$_SESSION["is_admin"]) {
        http_response_code(403);
        echo json_encode(["error" => "Forbidden - Admin access required"]);
        exit();
    }
}

function checkSessionTimeout()
{
    $inactive = (int) getenv("SESSION_TIMEOUT");
    if (
        isset($_SESSION["last_activity"]) &&
        time() - $_SESSION["last_activity"] > $inactive
    ) {
        session_unset();
        session_destroy();
        http_response_code(401);
        echo json_encode(["error" => "Session expired"]);
        exit();
    }
    $_SESSION["last_activity"] = time();
}
