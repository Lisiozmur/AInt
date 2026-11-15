<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Konta WYŁĄCZNIE demonstracyjne. Do produkcji: baza danych, bez kont demo.
 * W kodzie przechowujemy tylko hashe (password_hash), nigdy hasła jawne.
 */
final class DemoUserRepository
{
    private const USERS = [
        'user@example.test' => [
            'password_hash' => '$2y$12$qofj7B4XnGsp3TbBjjHoxeM/i2AgRyVGeHYaN002n7F6czFw2FR3K',
            'role' => 'user',
        ],
        'admin@example.test' => [
            'password_hash' => '$2y$12$eatZKTj2dz/6u48VgWLNseA.r105ROFg4jadRsOZsLzfvtD7tvFXe',
            'role' => 'admin',
        ],
    ];

    /** @return array{login:string,role:string}|null */
    public function verify(string $login, string $password): ?array
    {
        $login = strtolower(trim($login));
        $account = self::USERS[$login] ?? null;
        // Używamy tej samej operacji także dla nieznanego loginu.
        $hash = $account['password_hash'] ?? self::USERS['user@example.test']['password_hash'];
        $valid = password_verify($password, $hash);
        if (!$valid || $account === null) {
            return null;
        }
        return ['login' => $login, 'role' => $account['role']];
    }
}
