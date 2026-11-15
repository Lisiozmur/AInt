<?php

declare(strict_types=1);

namespace Core;

/** Komunikaty dla użytkownika widoczne w szablonach. */
final class Messages
{
    /** @var list<array{type: string, text: string}> */
    private array $items = [];

    public function addError(string $text): void
    {
        $this->items[] = ['type' => 'error', 'text' => $text];
    }

    public function addInfo(string $text): void
    {
        $this->items[] = ['type' => 'info', 'text' => $text];
    }

    /** @return list<array{type: string, text: string}> */
    public function all(): array
    {
        return $this->items;
    }
}
