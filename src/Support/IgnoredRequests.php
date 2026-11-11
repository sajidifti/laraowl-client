<?php

namespace Laraowl\Client\Support;

use Illuminate\Http\Request;

use function str_starts_with;

/**
 * @internal
 */
final class IgnoredRequests
{
    /**
     * User agent the LaraOwl server sends with its uptime checks.
     */
    public const UPTIME_USER_AGENT = 'LaraOwl-Uptime';

    /**
     * Whether a request should not be recorded: LaraOwl's own uptime checks
     * and any path matching the configured patterns (e.g. "up", "health/*").
     *
     * @param  list<string>  $paths
     */
    public static function matches(Request $request, array $paths = []): bool
    {
        if (str_starts_with((string) $request->userAgent(), self::UPTIME_USER_AGENT)) {
            return true;
        }

        return $paths !== [] && $request->is(...$paths);
    }
}
