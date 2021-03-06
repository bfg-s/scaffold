<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\TraitCollection;

/**
 * Class LevyTraitModel.
 * @package Bfg\Scaffold\LevyModel
 * @property LevyModel $parent
 */
class LevyTraitModel extends LevyModelAbstract
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
     * @var string|null
     */
    public ?string $path = null;

    /**
     * @var string|null
     */
    public static ?string $collection = TraitCollection::class;

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
