<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\PropertyCollection;

/**
 * Class LevyPropertyModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyPropertyModel extends LevyModelAbstract
{
    /**
     * Property name.
     * @var null|string
     */
    public ?string $property_name = null;

    /**
     * Property value.
     * @var mixed
     */
    public mixed $value = null;

    /**
     * @var string|null
     */
    public static ?string $collection = PropertyCollection::class;

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
