<?php

namespace Bfg\Scaffold;

use Bfg\Scaffold\Exceptions\InvalidRequiredFormat;
use Bfg\Scaffold\Exceptions\RequiredNotFound;

/**
 * Class ScaffoldCommands.
 * @package Bfg\Scaffold
 */
class ScaffoldCommands
{
    /**
     * Global blanc list for require.
     * @var array
     */
    public static array $blanc_list = [];

    /**
     * Registration all default commands.
     */
    public static function registerDefaultCommands()
    {
        ScaffoldConstruct::command('required', [static::class, 'required']);
    }

    /**
     * Required command
     * Usage:
     *  Json:
     *      "required": ["name of blanc",]
     *  Yaml:
     *      required:
     *          - name of blanc.
     * @param $data
     * @return array
     * @throws InvalidRequiredFormat
     * @throws RequiredNotFound
     */
    public static function required($data): array
    {
        $result = [];

        if (is_array($data) && ! is_assoc($data)) {
            foreach ($data as $require) {
                if (
                    isset(static::$blanc_list[$require])
                ) {
                    if (
                        is_array(static::$blanc_list[$require])
                    ) {
                        $result = array_merge_recursive($result, static::$blanc_list[$require]);
                    }
                } else {
                    throw new RequiredNotFound("Required blanc [{$require}] not found!");
                }
            }
        } else {
            throw new InvalidRequiredFormat('Required format must be associative array with string "blanc" names!');
        }

        return $result;
    }
}
