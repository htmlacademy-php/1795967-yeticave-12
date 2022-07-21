<?php
function error(int $code, string $message): void {
    http_response_code($code);
    exit($message);
}
