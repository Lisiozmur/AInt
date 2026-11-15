<?php

declare(strict_types=1);

namespace Core;

use Smarty\Smarty;

/** Obiekty współdzielone przez kontrolery, tworzone tylko w init.php. */
final class AppContext
{
    public function __construct(
        public readonly Config $config,
        public readonly Messages $messages,
        public readonly Smarty $smarty
    ) {
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = []): void
    {
        $this->smarty->assign('messages', $this->messages->all());
        $this->smarty->assign('is_logged_in', Auth::loggedIn());
        $this->smarty->assign('current_user', Auth::user()['login'] ?? '');
        $this->smarty->assign('current_role', Auth::user()['role'] ?? '');
        $this->smarty->assign('csrf_token', csrf_token());
        foreach ($data as $name => $value) {
            $this->smarty->assign($name, $value);
        }
        $this->smarty->display($template);
    }
}
