<?php
// Prosta klasa komunikatow (odpowiednik bibliotecznej klasy Messages).
class Messages {
    private $errors = [];
    public function addError($message) { $this->errors[] = $message; }
    public function isError() { return count($this->errors) > 0; }
    public function getErrors() { return $this->errors; }
}
