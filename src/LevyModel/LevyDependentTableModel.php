<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\DependentTablesCollection;
use Bfg\Scaffold\LevyCollections\FieldCollection;

/**
 * Class LevyDependentTableModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyDependentTableModel extends LevyModelAbstract
{
    /**
     * Dependent table fields collection.
     *
     * @var LevyFieldModel[]|FieldCollection
     */
    public FieldCollection|array $fields = [];

    /**
     * @var string|null
     */
    public ?string $class_name = null;

    /**
     * @var string|null
     */
    public ?string $file = null;

    /**
     * @var string|null
     */
    public static ?string $collection = DependentTablesCollection::class;

    /**
     * @param  string  $name
     * @param  array  $syntax
     * @param  bool  $collect
     * @return string
     */
    public static function modelName(string $name, array $syntax = [], bool $collect = false): string
    {
        return \Str::plural($name);
    }
}
