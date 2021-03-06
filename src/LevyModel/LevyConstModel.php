<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\ConstantCollection;

/**
 * Class LevyConstModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyConstModel extends LevyModelAbstract
{
    /**
     * Constant value.
     * @var mixed
     */
    public mixed $value;

    /**
     * @var string|null
     */
    public static ?string $collection = ConstantCollection::class;

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
