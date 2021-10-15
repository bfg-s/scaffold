<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\FieldCollection;

/**
 * Class LevyFieldModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyFieldModel extends LevyModelAbstract
{
    /**
     * Parent Levy Model.
     *
     * @var LevyModel
     */
    public LevyModelAbstract $parent;

    /**
     * Type of field.
     * @var string|null
     */
    public ?string $type = null;

    /**
     * Cast of field.
     * @var string|null
     */
    public ?string $cast = null;

    /**
     * Class name of custom cast.
     * @var string|null
     */
    public ?string $cast_class_name = null;

    /**
     * Class of custom cast.
     * @var string|null
     */
    public ?string $cast_class = null;

    /**
     * Class namespace of custom cast.
     * @var string|null
     */
    public ?string $cast_namespace = null;

    /**
     * @var mixed|null
     */
    public mixed $default = null;

    /**
     * Type of field.
     * @var int
     */
    public int $order = 0;

    /**
     * Params for field.
     * @var array
     */
    public array $params = [];

    /**
     * Params for migration column.
     * @var array
     */
    public array $migration_params = [];

    /**
     * Params for type migration.
     * @var array
     */
    public array $migration_type_params = [];

    /**
     * @var string|null
     */
    public static ?string $collection = FieldCollection::class;

    /**
     * @param  string  $name
     * @param  array  $syntax
     * @param  bool  $collect
     * @return string
     */
    public static function modelName(string $name, array $syntax = [], bool $collect = false): string
    {
        if ($collect && isset($syntax['parent']) && isset($syntax['parent']['name'])) {
            return $syntax['parent']['name'].'.'.$name;
        }

        return $name;
    }
}
