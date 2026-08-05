<?php

declare(strict_types=1);

namespace Mailofly;

final class MailoflyException extends \RuntimeException
{
    public function __construct(
        public readonly int $status,
        public readonly string $error,
        public readonly ?string $detailMessage = null,
        public readonly mixed $body = null,
    ) {
        parent::__construct(
            $detailMessage !== null ? "{$error}: {$detailMessage}" : $error,
            $status,
        );
    }
}
