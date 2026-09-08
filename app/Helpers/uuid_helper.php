<?php

if (!function_exists('uuid_v7')) {
    /**
     * Generate an RFC 9562 compliant UUID version 7.
     * Combines a 48-bit millisecond timestamp with 74 cryptographically secure random bits.
     * Naturally sortable, B-Tree index friendly, and impossible to guess.
     *
     * @return string
     */
    function uuid_v7(): string
    {
        // 48-bit millisecond timestamp
        $timeMs = (int) (microtime(true) * 1000);
        $timeHex = str_pad(dechex($timeMs), 12, '0', STR_PAD_LEFT);

        // 10 bytes of cryptographic randomness
        $rand = bin2hex(random_bytes(10));

        // Format: 8-4-4-4-12
        $part1 = substr($timeHex, 0, 8);
        $part2 = substr($timeHex, 8, 4);
        $ver = '7' . substr($rand, 0, 3);
        $varByte = dechex((hexdec(substr($rand, 3, 2)) & 0x3f) | 0x80);
        $var = str_pad($varByte, 2, '0', STR_PAD_LEFT) . substr($rand, 5, 2);
        $part5 = substr($rand, 7, 12);

        return sprintf('%s-%s-%s-%s-%s', $part1, $part2, $ver, $var, $part5);
    }
}

if (!function_exists('is_uuid')) {
    /**
     * Validate if a string conforms to UUID format (v1-v7)
     *
     * @param string|null $uuid
     * @return bool
     */
    function is_uuid(?string $uuid): bool
    {
        if (empty($uuid) || !is_string($uuid)) {
            return false;
        }
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-7][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid);
    }
}
