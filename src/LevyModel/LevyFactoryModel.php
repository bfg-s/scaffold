<?php

namespace Bfg\Scaffold\LevyModel;

/**
 * Class LevyFactoryModel.
 * @package Bfg\Scaffold\LevyModel
 * @property LevyModel $parent
 */
class LevyFactoryModel extends LevyModelAbstract
{
    /**
     * Class of factory.
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
    public array $lines = [];

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
