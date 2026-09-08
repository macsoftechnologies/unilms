<?php

/**
 * Global Common Procedural Helpers for UniLMS
 * Loaded during framework bootstrap process.
 */

if (!function_exists('format_date')) {
    /**
     * Formats any valid date string or timestamp to dd-mm-yyyy format
     * e.g. "2026-08-23" -> "23-08-2026"
     * e.g. "2026-08-23 19:35:00", true -> "23-08-2026 07:35 PM"
     *
     * @param string|int|null $date
     * @param bool $includeTime
     * @return string
     */
    function format_date($date, bool $includeTime = false): string
    {
        if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return '—';
        }
        $timestamp = is_numeric($date) ? (int)$date : strtotime((string)$date);
        if (!$timestamp) {
            return '—';
        }
        return $includeTime ? date('d-m-Y h:i A', $timestamp) : date('d-m-Y', $timestamp);
    }
}

if (!function_exists('format_time')) {
    /**
     * Formats a time string into 12-hour AM/PM format
     * e.g. "14:30:00" -> "02:30 PM"
     *
     * @param string|null $time
     * @return string
     */
    function format_time($time): string
    {
        if (empty($time)) {
            return '—';
        }
        $timestamp = strtotime((string)$time);
        return $timestamp ? date('h:i A', $timestamp) : (string)$time;
    }
}

if (!function_exists('parse_date_to_sql')) {
    /**
     * Converts a user-input dd-mm-yyyy or other date format into MySQL YYYY-MM-DD
     *
     * @param string|null $dateString
     * @return string|null
     */
    function parse_date_to_sql($dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }
        // Handle dd-mm-yyyy explicitly
        if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', trim($dateString), $m)) {
            return "{$m[3]}-{$m[2]}-{$m[1]}";
        }
        $timestamp = strtotime(trim($dateString));
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }
}

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
        $timeMs = (int) (microtime(true) * 1000);
        $timeHex = str_pad(dechex($timeMs), 12, '0', STR_PAD_LEFT);
        $rand = bin2hex(random_bytes(10));

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

