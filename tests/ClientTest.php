<?php

declare(strict_types=1);

namespace Mailofly\Tests;

use Mailofly\Client;
use Mailofly\MailoflyException;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testRequiresApiKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Client('');
    }

    public function testExceptionMessage(): void
    {
        $e = new MailoflyException(401, 'unauthorized', 'Invalid key');
        $this->assertSame(401, $e->status);
        $this->assertStringContainsString('unauthorized', $e->getMessage());
        $this->assertSame('Invalid key', $e->detailMessage);
    }
}
