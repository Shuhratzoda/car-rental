<?php

declare(strict_types=1);

namespace App\Core;

final class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $message = 'Майдон ҳатмист'): self
    {
        $value = $this->data[$field] ?? null;
        if ($value === null || trim((string) $value) === '') {
            $this->addError($field, $message);
        }
        return $this;
    }

    public function maxLength(string $field, int $max, string $message = null): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if (mb_strlen($value) > $max) {
            $this->addError($field, $message ?? "На зиёда аз {$max} аломат");
        }
        return $this;
    }

    public function email(string $field, string $message = 'E-mail нодуруст аст'): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $message);
        }
        return $this;
    }

    public function date(string $field, string $message = 'Сана нодуруст аст (СССС-ММ-РР)'): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value !== '') {
            $d = \DateTime::createFromFormat('Y-m-d', $value);
            if (!$d || $d->format('Y-m-d') !== $value) {
                $this->addError($field, $message);
            }
        }
        return $this;
    }

    public function in(string $field, array $allowed, string $message = 'Қимати нодуруст'): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && $value !== '' && !in_array($value, $allowed, true)) {
            $this->addError($field, $message);
        }
        return $this;
    }

    public function numeric(string $field, string $message = 'Рақам бояд бошад'): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && $value !== '' && !is_numeric($value)) {
            $this->addError($field, $message);
        }
        return $this;
    }

    public function min(string $field, float $min, string $message = null): self
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && $value !== '' && (float) $value < $min) {
            $this->addError($field, $message ?? "На камтар аз {$min}");
        }
        return $this;
    }

    public function addError(string $field, string $message): self
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function data(): array
    {
        return $this->data;
    }
}
