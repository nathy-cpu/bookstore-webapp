<?php
class TestRequest {
    public static function capture(): void {
        // Parse the actual request URL
        $url = parse_url($_SERVER['TEST_REQUEST_URI'] ?? '/');
        $_SERVER['REQUEST_URI'] = $url['path'] ?? '/';
        $_SERVER['QUERY_STRING'] = $url['query'] ?? '';
        
        // Get method from override header or default to GET
        $_SERVER['REQUEST_METHOD'] = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] 
            ?? $_SERVER['REQUEST_METHOD'] 
            ?? 'GET';
            
        // Set content headers
        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        $_SERVER['CONTENT_TYPE'] = 'application/json';
    }
}
