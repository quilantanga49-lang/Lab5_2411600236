<?php

/*
 * XAMPP's PHP on this machine has mbstring blocked by Windows Application Control.
 * symfony/polyfill-mbstring covers most mb_* functions but not mb_strimwidth(),
 * which Termwind (used by Artisan) needs. Provide a small fallback.
 */
if (! function_exists('mb_strimwidth')) {
    function mb_strimwidth(
        string $string,
        int $start,
        int $width,
        string $trim = '',
        ?string $encoding = null
    ): string {
        $encoding = $encoding ?: mb_internal_encoding();
        $part = mb_substr($string, $start, $width, $encoding);

        if ($trim !== '' && mb_strwidth($string, $encoding) > $width) {
            $part = mb_substr($part, 0, max(0, $width - mb_strwidth($trim, $encoding)), $encoding).$trim;
        }

        return $part;
    }
}
