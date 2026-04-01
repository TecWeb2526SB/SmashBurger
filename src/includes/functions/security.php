<?php
/**
 * Utility sicurezza: CSRF token.
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_is_valid(?string $token): bool
{
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        return false;
    }

    if (!is_string($token) || $token === '') {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function security_client_ip(): string
{
    $remoteAddr = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));

    return $remoteAddr !== '' ? $remoteAddr : 'unknown';
}

function login_rate_limit_normalize_identifier(string $identifier): string
{
    $normalized = mb_strtolower(trim($identifier));

    if ($normalized === '') {
        return 'anonymous';
    }

    return mb_substr($normalized, 0, 160);
}

function login_rate_limit_storage_dir(): string
{
    $path = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
        . DIRECTORY_SEPARATOR
        . 'smashburger_login_rate_limits';

    if (!is_dir($path)) {
        @mkdir($path, 0700, true);
    }

    return $path;
}

function login_rate_limit_bucket_path(string $key): string
{
    return login_rate_limit_storage_dir()
        . DIRECTORY_SEPARATOR
        . hash('sha256', $key)
        . '.json';
}

function login_rate_limit_default_bucket(): array
{
    return [
        'failures' => [],
        'blocked_until' => 0,
    ];
}

function login_rate_limit_normalize_bucket(array $bucket): array
{
    $now = time();
    $windowStart = $now - LOGIN_RATE_LIMIT_WINDOW_SECONDS;
    $failures = [];

    foreach ((array) ($bucket['failures'] ?? []) as $attemptAt) {
        $attemptAt = (int) $attemptAt;

        if ($attemptAt >= $windowStart && $attemptAt <= $now) {
            $failures[] = $attemptAt;
        }
    }

    $blockedUntil = max(0, (int) ($bucket['blocked_until'] ?? 0));
    if ($blockedUntil <= $now) {
        $blockedUntil = 0;
    }

    return [
        'failures' => $failures,
        'blocked_until' => $blockedUntil,
    ];
}

function login_rate_limit_with_bucket(string $key, callable $callback): array
{
    $path = login_rate_limit_bucket_path($key);
    $handle = @fopen($path, 'c+');

    if ($handle === false) {
        return login_rate_limit_default_bucket();
    }

    try {
        if (!flock($handle, LOCK_EX)) {
            return login_rate_limit_default_bucket();
        }

        rewind($handle);
        $rawContents = stream_get_contents($handle);
        $decoded = is_string($rawContents) && $rawContents !== ''
            ? json_decode($rawContents, true)
            : null;

        $bucket = is_array($decoded)
            ? login_rate_limit_normalize_bucket($decoded)
            : login_rate_limit_default_bucket();

        $updatedBucket = $callback($bucket);
        if (!is_array($updatedBucket)) {
            $updatedBucket = $bucket;
        }

        $updatedBucket = login_rate_limit_normalize_bucket($updatedBucket);
        $encodedBucket = json_encode($updatedBucket, JSON_UNESCAPED_SLASHES);

        rewind($handle);
        ftruncate($handle, 0);

        if ($encodedBucket !== false) {
            fwrite($handle, $encodedBucket);
            fflush($handle);
        }

        flock($handle, LOCK_UN);
    } finally {
        fclose($handle);
    }

    if ($updatedBucket['blocked_until'] === 0 && empty($updatedBucket['failures']) && file_exists($path)) {
        @unlink($path);
    }

    return $updatedBucket;
}

function login_rate_limit_get_keys(string $identifier): array
{
    $ip = security_client_ip();
    $normalizedIdentifier = login_rate_limit_normalize_identifier($identifier);

    return [
        [
            'key' => 'ip:' . $ip,
            'scope' => 'ip',
            'max_attempts' => LOGIN_RATE_LIMIT_MAX_ATTEMPTS_PER_IP,
        ],
        [
            'key' => 'identifier:' . $ip . '|' . $normalizedIdentifier,
            'scope' => 'identifier',
            'max_attempts' => LOGIN_RATE_LIMIT_MAX_ATTEMPTS_PER_IDENTIFIER,
        ],
    ];
}

function login_rate_limit_get_status(string $identifier): array
{
    $now = time();

    foreach (login_rate_limit_get_keys($identifier) as $bucketConfig) {
        $bucket = login_rate_limit_with_bucket(
            $bucketConfig['key'],
            static fn(array $currentBucket): array => $currentBucket
        );

        if ($bucket['blocked_until'] > $now) {
            $retryAfter = max(1, $bucket['blocked_until'] - $now);
            $message = $bucketConfig['scope'] === 'ip'
                ? 'Troppi tentativi di accesso da questa rete. Attendi ' . $retryAfter . ' secondi e riprova.'
                : 'Troppi tentativi di accesso con queste credenziali. Attendi ' . $retryAfter . ' secondi e riprova.';

            return [
                'allowed' => false,
                'retry_after' => $retryAfter,
                'message' => $message,
            ];
        }
    }

    return [
        'allowed' => true,
        'retry_after' => 0,
        'message' => '',
    ];
}

function login_rate_limit_register_failure(string $identifier): void
{
    $now = time();

    foreach (login_rate_limit_get_keys($identifier) as $bucketConfig) {
        login_rate_limit_with_bucket(
            $bucketConfig['key'],
            static function (array $bucket) use ($now, $bucketConfig): array {
                if ($bucket['blocked_until'] > $now) {
                    return $bucket;
                }

                $bucket['failures'][] = $now;

                if (count($bucket['failures']) >= $bucketConfig['max_attempts']) {
                    $bucket['failures'] = [];
                    $bucket['blocked_until'] = $now + LOGIN_RATE_LIMIT_BLOCK_SECONDS;
                }

                return $bucket;
            }
        );
    }
}

function login_rate_limit_clear_identifier(string $identifier): void
{
    if (trim($identifier) === '') {
        return;
    }

    $keys = login_rate_limit_get_keys($identifier);
    $identifierKey = $keys[1]['key'] ?? null;

    if ($identifierKey === null) {
        return;
    }

    login_rate_limit_with_bucket(
        $identifierKey,
        static fn(array $bucket): array => login_rate_limit_default_bucket()
    );
}
