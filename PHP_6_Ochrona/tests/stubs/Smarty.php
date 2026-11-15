<?php

declare(strict_types=1);

namespace Smarty;

/** Zaślepka umożliwiająca testy logiki routera bez instalacji Composera. */
class Smarty
{
    /** @var array<string,mixed> */
    public array $assigned = [];
    public string $displayedTemplate = '';

    public function assign(string $name, mixed $value): void
    {
        $this->assigned[$name] = $value;
    }

    public function display(string $template): void
    {
        $this->displayedTemplate = $template;
    }
}
