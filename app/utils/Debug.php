<?php

class Debug
{
    private static function getMicroTime()
    {
        $mt = microtime(true);
        $sec = floor($mt);
        $msec = floor(($mt - $sec) * 1000000);
        return date('Y-m-d H:i:s', $sec) . '.' . sprintf('%06d', $msec);
    }

    public static function logStackTrace($message = '')
    {
        $trace = debug_backtrace();
        $stackMsg = self::getMicroTime() . " Stack Trace";
        if ($message) {
            $stackMsg .= " ($message)";
        }
        $stackMsg .= ":\n";

        foreach ($trace as $i => $call) {
            $file = $call['file'] ?? 'unknown file';
            $line = $call['line'] ?? 'unknown line';
            $function = $call['function'] ?? 'unknown function';
            $class = $call['class'] ?? '';
            $type = $call['type'] ?? '';

            $stackMsg .= sprintf(
                "#%d %s(%d): %s%s%s()\n",
                $i,
                $file,
                $line,
                $class,
                $type,
                $function
            );

            // Log arguments if they exist and they're not sensitive
            if (!empty($call['args'])) {
                $stackMsg .= "    Arguments:\n";
                foreach ($call['args'] as $j => $arg) {
                    // Skip logging passwords
                    if (
                        is_string($arg) && (
                        stripos($function, 'password') !== false ||
                        stripos($call['class'], 'auth') !== false
                        )
                    ) {
                        $argStr = '******';
                    } else {
                        $argStr = is_scalar($arg) ? $arg : gettype($arg);
                    }
                    $stackMsg .= sprintf("        #%d: %s\n", $j, $argStr);
                }
            }
        }

        error_log($stackMsg);
    }

    public static function logRequest()
    {
        $msg = sprintf(
            "%s Request Info:\nMethod: %s\nURI: %s\nQuery String: %s\nSession ID: %s\nSession Data: %s\nHeaders:\n%s\n",
            self::getMicroTime(),
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['QUERY_STRING'] ?? 'none',
            session_id(),
            print_r($_SESSION, true),
            self::getRequestHeaders()
        );
        error_log($msg);
    }

    private static function getRequestHeaders()
    {
        $headers = '';
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('HTTP_', '', $key);
                $header = str_replace('_', '-', $header);
                $headers .= sprintf("%s: %s\n", $header, $value);
            }
        }
        return $headers;
    }
}
