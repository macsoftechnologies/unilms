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
