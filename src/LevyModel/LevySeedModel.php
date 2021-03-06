<?php

namespace Bfg\Scaffold\LevyModel;

/**
 * Class LevySeedModel.
 * @package Bfg\Scaffold\LevyModel
 * @property LevyModel $parent
 */
class LevySeedModel extends LevyModelAbstract
{
    /**
     * Class of trait.
     * @var string|null
     */
    public ?string $class = null;

    /**
     * @var string|null
     */
    public ?string $class_name = null;

    /**
     * @var string|null
     */
    public ?string $namespace = null;

    /**
     * @var array
     */
    public array $data = [];

    /**
     * Factory line.
     *
     * @var string
     */
    public string $factory = '';

    /**
     * @param  string  $name
     * @param  array  $syntax
     * @param  bool  $collect
     * @return string
     */
    public static function modelName(string $name, array $syntax = [], bool $collect = false): string
    {
        if ($collect && isset($syntax['parent']) && isset($syntax['parent']['name'])) {
            $parent_name = (string) $syntax['parent']['name'];

            return $parent_name.'.'.$name;
        }

        return $name;
    }
}
