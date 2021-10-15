<?php

namespace Bfg\Scaffold;

/**
 * Class ScaffoldConstruct.
 * @package Bfg\Scaffold
 */
class ScaffoldConstruct
{
    /**
     * @var array
     */
    protected static array $commands = [];

    /**
     * @var array
     */
    protected static array $blanc_merge = [];

    /**
     * Constructor constructor.
     * @param  array  $array
     */
    public function __construct(
      protected array $array = []
    ) {
        ScaffoldCommands::registerDefaultCommands();
    }

    /**
     * Force add scaffold "blanc" for merge.
     * @param  array  $array
     */
    public static function blanc(array $array)
    {
        static::$blanc_merge[] = $array;
    }

    /**
     * Add scaffold named "blanc" for merge.
     * @param  string  $name
     * @param  array  $array
     */
    public static function namedBlanc(string $name, array $array)
    {
        ScaffoldCommands::$blanc_list[$name] = $array;
    }

    /**
     * Add command to scaffold constructor.
     * @param  string  $name
     * @param  callable  $callable
     */
    public static function command(string $name, callable $callable)
    {
        static::$commands[$name] = $callable;
    }

    /**
     * @return $this
     */
    public function applyCommands(): static
    {
        foreach (static::$commands as $name => $handler) {
            if (isset($this->array[$name])) {
                if (is_callable($handler)) {
                    $result = call_user_func($handler, $this->array[$name]);
                    if (is_array($result)) {
                        $this->array = array_merge_recursive($this->array, $result);
                    }
                }

                unset($this->array[$name]);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function applyMerges(): static
    {
        foreach (static::$blanc_merge as $item) {
            $this->array = array_merge_recursive($this->array, $item);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function result(): array
    {
        return $this->array;
    }

    /**
     * @param  array  $array
     * @return array
     */
    public static function make(array $array): array
    {
        return (new static($array))
            ->applyCommands()
            ->applyMerges()
            ->result();
    }
}
