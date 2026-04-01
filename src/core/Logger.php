<?php

namespace Src\core;

class Logger
{
    private static string $logFile = __DIR__ . '/../../logs/app.log';

    public static function info(string $message): void
    {
        self::write('INFO', $message);
    }

    public static function warning(string $message): void
    {
        self::write('WARNING', $message);
    }

    public static function error(string $message): void
    {
        self::write('ERROR', $message);
    }

    private static function write(string $level, string $message): void
    {
        $dir = dirname(self::$logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $line = "[{$timestamp}] {$level}: {$message}" . PHP_EOL;

        file_put_contents(self::$logFile, $line, FILE_APPEND);
    }
}
