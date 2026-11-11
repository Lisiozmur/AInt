<?php
namespace App\Model;

class Messages {
    private array $errors = [];
    public function addError(string $message): void { $this->errors[] = $message; }
    public function isError(): bool { return count($this->errors) > 0; }
    public function getErrors(): array { return $this->errors; }
}
