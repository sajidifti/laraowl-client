<?php

namespace Laraowl\Client\Tests\Unit;

use Illuminate\Http\Request;
use Laraowl\Client\Support\IgnoredRequests;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class IgnoredRequestsTest extends TestCase
{
    public function test_it_ignores_laraowl_uptime_checks_on_any_path(): void
    {
        $request = Request::create('/dashboard', server: ['HTTP_USER_AGENT' => 'LaraOwl-Uptime/1.0']);

        $this->assertTrue(IgnoredRequests::matches($request));
    }

    public function test_it_records_ordinary_requests_by_default(): void
    {
        $request = Request::create('/up', server: ['HTTP_USER_AGENT' => 'Mozilla/5.0']);

        $this->assertFalse(IgnoredRequests::matches($request));
    }

    #[DataProvider('paths')]
    public function test_it_ignores_configured_paths(string $path, bool $ignored): void
    {
        $this->assertSame($ignored, IgnoredRequests::matches(Request::create($path), ['up', 'health/*']));
    }

    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function paths(): iterable
    {
        yield 'exact path' => ['/up', true];
        yield 'wildcard path' => ['/health/db', true];
        yield 'other path' => ['/users', false];
        yield 'prefix only' => ['/upload', false];
    }
}
