<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Scaffold\FileStorage;

/**
 * Class ListenerControl.
 * @package Bfg\Scaffold\Listeners
 */
abstract class ListenerControl
{
    /**
     * @return \Bfg\Scaffold\FileStorage
     */
    public function storage(): FileStorage
    {
        return \Scaffold::storage();
    }

    /**
     * @param  mixed  $str
     * @return string
     */
    public function formatString(mixed $str): mixed
    {
        if ($str === true) {
            $str = 'true';
        } elseif ($str === false) {
            $str = 'false';
        } elseif ($str === null) {
            $str = 'null';
        }

        if (! is_string($str)) {
            return $str;
        }

        if (
            $str !== 'true' &&
            $str !== 'false' &&
            $str !== 'null' &&
            ! is_numeric($str) &&
            ! preg_match('/^[A-Za-z0-9_\\\\]+::[A-Za-z0-9_\\\\]+$/', trim($str))
        ) {
            $str = trim($str);

            return entity("'{$str}'");
        }

        return entity($str);
    }

    /**
     * @param  array  $array
     * @return array
     */
    public function formatArray(array $array): array
    {
        return array_map([$this, 'formatString'], $array);
    }

    /**
     * @param  mixed  $data
     * @return mixed
     */
    public function format(mixed $data): mixed
    {
        if (is_array($data)) {
            return $this->formatArray($data);
        }

        return $this->formatString($data);
    }
}
