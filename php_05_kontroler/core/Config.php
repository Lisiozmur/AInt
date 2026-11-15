<?php

declare(strict_types=1);

namespace Core;

final class Config
{
    public function __construct(
        public readonly string $appName,
        public readonly string $currency
    ) {
    }
}
