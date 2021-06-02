<?php

if (! function_exists("is_assoc") ) {

    /**
     * @param  array  $arr
     * @return bool
     */
    function is_assoc(array $arr): bool
    {
        if ([] === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

if (!function_exists('tag_replace')) {
    /**
     * @param  string|mixed|T  $text
     * @param  array|object  $materials
     * @param  string  $pattern
     * @return array|string|mixed|T
     * @template T
     */
    function tag_replace (mixed $text, array|object $materials, string $pattern = "{*}"): array|string|null
    {
        if (!is_string($text)) return $text;
        $pattern = preg_quote($pattern);
        $pattern = str_replace('\*', '([a-zA-Z0-9\_\-\.]+)', $pattern);

        return preg_replace_callback("/{$pattern}/", function ($m) use ($materials) {
            return multi_dot_call($materials, $m[1]);
        }, $text);
    }
}
