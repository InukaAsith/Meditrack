<?php
declare(strict_types=1);

function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function money(int|float|string $amount): string
{
    $n = (float) $amount;
    return 'Rs. ' . number_format($n, ($n == (int) $n) ? 0 : 2);
}

function initials(string $fullName): string
{
    $parts = preg_split('/\s+/', trim($fullName)) ?: [];
    $parts = array_slice(array_filter($parts), 0, 2);
    return strtoupper(implode('', array_map(static fn ($p) => mb_substr($p, 0, 1), $parts)));
}

function greeting(): string
{
    $hour = (int) date('G');
    if ($hour < 12) {
        return 'Good morning';
    }
    if ($hour < 17) {
        return 'Good afternoon';
    }
    return 'Good evening';
}

function first_name(string $fullName): string
{
    foreach (preg_split('/\s+/', trim($fullName)) ?: [] as $word) {
        if ($word !== '' && !str_ends_with($word, '.')) {
            return $word;
        }
    }
    return $fullName;
}

function status_tone(string $status): string
{
    static $map = [
        'booked' => 'muted', 'confirmed' => 'info', 'checked_in' => 'success', 'ready' => 'primary',
        'in_consultation' => 'primary-strong', 'delayed' => 'warning', 'late' => 'warning',
        'no_show' => 'danger', 'not_arrived' => 'muted', 'emergency' => 'danger',
        'pending' => 'warning', 'paid' => 'success', 'void' => 'muted',
        'refund_queued' => 'info', 'refund_complete' => 'info',
        'cancelled' => 'danger', 'completed' => 'muted', 'rescheduled' => 'info',
    ];
    return $map[$status] ?? 'muted';
}

function status_label(string $status): string
{
    return ucwords(str_replace('_', ' ', $status));
}

function icon(string $name, int $size = 16, string $class = ''): string
{
    static $paths = [
        'home' => 'M3 11l9-8 9 8M5 10v10h5v-6h4v6h5V10',
        'calendar' => 'M4 5h16v16H4zM4 9h16M8 3v4M16 3v4',
        'queue' => 'M4 6h16M4 12h16M4 18h10',
        'records' => 'M6 3h9l5 5v13H6zM14 3v6h6',
        'billing' => 'M4 5h16v14H4zM4 10h16M8 15h4',
        'bell' => 'M6 8a6 6 0 1112 0c0 5 2 6 2 6H4s2-1 2-6zM10 20a2 2 0 004 0',
        'profile' => 'M12 12a4 4 0 100-8 4 4 0 000 8zM4 20c1.5-4 5-6 8-6s6.5 2 8 6',
        'health' => 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z',
        'plus' => 'M12 5v14M5 12h14',
        'chevronLeft' => 'M15 18l-6-6 6-6',
        'chevronRight' => 'M9 18l6-6-6-6',
        'close' => 'M6 6l12 12M18 6L6 18',
        'check' => 'M5 13l4 4L19 7',
        'upload' => 'M12 4v12M7 9l5-5 5 5M5 20h14',
        'search' => 'M11 4a7 7 0 100 14 7 7 0 000-14zM21 21l-4.3-4.3',
        'print' => 'M6 9V4h12v5M6 18h12v4H6zM4 9h16v7H4z',
        'clock' => 'M12 7v5l3 3M12 21a9 9 0 100-18 9 9 0 000 18z',
        'refresh' => 'M20 11a8 8 0 10-2.34 6.66M20 5v6h-6',
        'alert' => 'M12 8v5M12 17h.01M10.3 3.9L2.4 18a2 2 0 001.7 3h15.8a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z',
        'phone' => 'M4 4h4l2 5-3 2a12 12 0 006 6l2-3 5 2v4a2 2 0 01-2 2A16 16 0 014 6a2 2 0 010-2z',
        'pill' => 'M10.5 20.5a5 5 0 01-7-7l6-6a5 5 0 017 7l-6 6zM8 8l7 7',
        'edit' => 'M4 20h4L18 10l-4-4L4 16v4zM13 5l4 4',
        'trash' => 'M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13',
        'arrowRight' => 'M5 12h14M13 6l6 6-6 6',
        'file' => 'M6 3h9l5 5v13H6zM14 3v6h6',
        'map' => 'M9 4L3 6v14l6-2 6 2 6-2V4l-6 2-6-2zM9 4v14M15 6v14',
        'dollar' => 'M12 3v18M8 7a3 3 0 013-3h2a3 3 0 010 6h-2a3 3 0 000 6h2a3 3 0 003-3',
        'download' => 'M12 4v12M7 11l5 5 5-5M5 20h14',
        'lock' => 'M6 10V7a6 6 0 1112 0v3M5 10h14v11H5zM12 15v2',
        'settings' => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z',
        'users' => 'M17 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9.5 11a4 4 0 100-8 4 4 0 000 8M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75',
        'shield' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10zM9 12l2 2 4-4',
        'server' => 'M4 4h16v6H4zM4 14h16v6H4zM7 7h.01M7 17h.01',
        'activity' => 'M22 12h-4l-3 9L9 3l-3 9H2',
        'mail' => 'M4 5h16v14H4zM4 7l8 6 8-6',
        'message' => 'M21 15a2 2 0 01-2 2H8l-4 4V5a2 2 0 012-2h13a2 2 0 012 2z',
        'archive' => 'M3 4h18v4H3zM5 8v12h14V8M10 12h4',
        'key' => 'M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.78 7.78 5.5 5.5 0 0 1 7.78-7.78zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4',
        'filter' => 'M22 3H2l8 9.46V19l4 2v-8.54z',
        'eye' => 'M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7zM12 15a3 3 0 100-6 3 3 0 000 6z',
        'building' => 'M4 21V5a1 1 0 011-1h9a1 1 0 011 1v16M15 21V9h4a1 1 0 011 1v11M4 21h17M8 8h.01M8 12h.01M8 16h.01M11 8h.01M11 12h.01M11 16h.01',
        'grid' => 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z',
        'warning' => 'M12 8v5M12 17h.01M10.3 3.9L2.4 18a2 2 0 001.7 3h15.8a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z',
    ];

    $d = $paths[$name] ?? $paths['close'];
    $cls = 'icon' . ($class !== '' ? ' ' . htmlspecialchars($class, ENT_QUOTES) : '');

    return '<svg class="' . $cls . '" viewBox="0 0 24 24" width="' . $size . '" height="' . $size
        . '" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"'
        . ' stroke-linejoin="round" aria-hidden="true"><path d="' . $d . '"/></svg>';
}

function quickchart_qr_url(string $payload, int $size = 160, int $margin = 1, string $ecLevel = 'M'): string
{
    return 'https://quickchart.io/qr?' . http_build_query([
        'text' => $payload,
        'size' => $size,
        'margin' => $margin,
        'ecLevel' => $ecLevel,
    ], '', '&', PHP_QUERY_RFC3986);
}
