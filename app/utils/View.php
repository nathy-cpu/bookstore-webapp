<?php

class View
{
    private static $layout = 'base';
    private static $data = [];

    public static function render($view, $data = [])
    {
        // Extract data to make variables available in view
        self::$data = array_merge(self::$data, $data);
        extract(self::$data);

        // Start output buffering
        ob_start();

        // Include the view file
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new Exception("View file not found: {$viewPath}");
        }
        include $viewPath;

        // Get the view content
        $content = ob_get_clean();

        // If no layout is specified, return the content directly
        if (!self::$layout) {
            return $content;
        }

        // Otherwise, render the layout with the content
        $layoutPath = __DIR__ . '/../views/layouts/' . self::$layout . '.php';
        if (!file_exists($layoutPath)) {
            throw new Exception("Layout file not found: {$layoutPath}");
        }
        include $layoutPath;
    }

    public static function setLayout($layout)
    {
        self::$layout = $layout;
    }

    public static function addData($key, $value)
    {
        self::$data[$key] = $value;
    }
}
