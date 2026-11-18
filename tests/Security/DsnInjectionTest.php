<?php

namespace DreamFactory\Core\Hadoop\Tests\Security;

use DreamFactory\Core\Hadoop\Services\HiveService;
use PHPUnit\Framework\TestCase;

/**
 * Security: HiveService::adaptConfig builds an ODBC DSN by string
 * interpolation. Admin-supplied host/port/database/driver_path values must
 * be validated; otherwise an attacker can inject extra DSN options or load
 * an arbitrary shared library (RCE via dlopen).
 */
class DsnInjectionTest extends TestCase
{
    public function testRejectsSemicolonInHost(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HiveService::assertSafeDsnValue('localhost;EvilOption=1', 'host');
    }

    public function testRejectsBraceInDatabase(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HiveService::assertSafeDsnValue('db}', 'database');
    }

    public function testRejectsCrlfInHost(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HiveService::assertSafeDsnValue("localhost\r\nFile=/etc/passwd", 'host');
    }

    public function testRejectsEmptyHost(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HiveService::assertSafeDsnValue('', 'host');
    }

    public function testAcceptsPlainHost(): void
    {
        $this->assertSame('hive.internal', HiveService::assertSafeDsnValue('hive.internal', 'host'));
    }

    public function testRejectsNonNumericPort(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HiveService::assertSafePort('10000;EvilOption=1');
    }

    public function testRejectsOutOfRangePort(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HiveService::assertSafePort(70000);
    }

    public function testAcceptsValidPort(): void
    {
        $this->assertSame(10000, HiveService::assertSafePort('10000'));
    }

    public function testRejectsDriverPathOutsideAllowlist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HiveService::assertSafeDriverPath('/tmp/evil.so');
    }

    public function testRejectsDriverPathThatDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HiveService::assertSafeDriverPath('/opt/mapr/no-such-file.so');
    }
}
