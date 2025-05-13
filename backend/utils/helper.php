<?php
function getJsonInput()
{
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON input");
    }

    return $data;
}

function jsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);
    header("Content-Type: application/json");
    echo json_encode($data);
    exit();
}

function validateRequiredFields($data, $requiredFields)
{
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Field '$field' is required");
        }
    }
}

function extractIdFromUri()
{
    $uri = $_SERVER["REQUEST_URI"];
    $parts = explode("/", $uri);
    $id = end($parts);

    if (!is_numeric($id)) {
        throw new Exception("Invalid ID");
    }

    return (int) $id;
}
?>
