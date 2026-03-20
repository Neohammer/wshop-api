<?php

namespace App\Infrastructure\Logging;

class FileLogger implements LoggerInterface
{
    public function __construct(private string $logFilePath) {}

    public function info(string $message, array $context = []): void
    {
        $this->write('INFO', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->write('ERROR', $message, $context);
    }

    private function write(string $level, string $message, array $context = []): void
    {
        $directory = dirname($this->logFilePath);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $line = sprintf(
            "[%s] %s %s %s%s",
            date(DATE_ATOM),
            $level,
            $message,
            $context !== [] ? json_encode($context, JSON_UNESCAPED_UNICODE) : '',
            PHP_EOL
        );

        file_put_contents($this->logFilePath, $line, FILE_APPEND);
    }
}