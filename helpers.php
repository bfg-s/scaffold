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
