<?php

namespace WillyFramework\pkg;

class Validator {
    private array $data;
    private array $rules;
    private array $errors = [];

    public function __construct(array $data, array $rules) {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate(): bool {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);

            foreach ($rules as $rule) {
                $params = [];
                if (strpos($rule, ':') !== false) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                $method = "validate".ucfirst($rule); 
                if (method_exists($this, $method)) {
                    $this->$method($field, ...$params); 
                }
            }
        }

        return empty($this->errors);
    }

    private function validateRequired(string $field) {
        if (!isset($this->data[$field]) || $this->data[$field] === '') {
            $this->errors[$field][] = "$field is required";
        }
    }

    private function validateEmail(string $field) {
        if (!filter_var($this->data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "$field must be valid email";
        }
    }

    private function validateMin(string $field, int $min) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field][] = "$field must be at least $min characters";
        }
    }

    private function validateMax(string $field, int $max) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field][] = "$field must not exceed $max characters";
        }
    }

    private function validateNotNull(string $field) {
        if (!array_key_exists($field, $this->data) || is_null($this->data[$field])) {
            $this->errors[$field][] = "$field cannot be null";
        }
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function passes(): bool {
        return empty($this->errors);
    }
}
