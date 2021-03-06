<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\ConstantCollection;
use Bfg\Scaffold\LevyCollections\DependentTablesCollection;
use Bfg\Scaffold\LevyCollections\FieldCollection;
use Bfg\Scaffold\LevyCollections\ModelCollection;
use Bfg\Scaffold\LevyCollections\PropertyCollection;
use Bfg\Scaffold\LevyCollections\RelationCollection;
use Bfg\Scaffold\LevyCollections\ResourceCollection;
use Bfg\Scaffold\LevyCollections\RuleCollection;
use Bfg\Scaffold\LevyCollections\TraitCollection;

/**
 * Class LevyModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyModel extends LevyModelAbstract
{
    /**
     * @var string|null
     */
    public ?string $file = null;

    /**
     * Model table.
     * @var string|null
     */
    public ?string $table = null;

    /**
     * @var bool
     */
    public bool $auth = false;

    /**
     * @var bool
     */
    public bool $must_verify = false;

    /**
     * Model table levy.
     * @var LevyDependentTableModel|null
     */
    public ?LevyDependentTableModel $table_model = null;

    /**
     * Model foreign field name or false for switch off.
     * @var false|string
     */
    public false|string $foreign = false;

    /**
     * Model created timestamp field or false.
     * @var string|false
     */
    public string|false $created = false;

    /**
     * Model updated timestamp field or false.
     * @var string|false
     */
    public string|false $updated = false;

    /**
     * Model class full namespace name.
     * @var string|null
     */
    public ?string $class = null;

    /**
     * Model class inherited field for foreign.
     * @var string|null
     */
    public ?string $inherited_field = null;

    /**
     * Model class morph field for foreign.
     * @var string|null
     */
    public ?string $morph_field = null;

    /**
     * Model class name.
     * @var string|null
     */
    public ?string $class_name = null;
    /**
     * Model class namespace.
     * @var string|null
     */
    public ?string $namespace = null;
    /**
     * Model class file path.
     * @var string|null
     */
    public ?string $path = null;

    /**
     * Hidden fields.
     * @var array
     */
    public array $hidden = [];

    /**
     * Append relations.
     * @var array
     */
    public array $appends = [];

    /**
     * With relations.
     * @var array
     */
    public array $with = [];

    /**
     * With count relations.
     * @var array
     */
    public array $with_count = [];

    /**
     * Model constant collection.
     * @var LevyConstModel[]|ConstantCollection
     */
    public ConstantCollection|array $constants = [];

    /**
     * Model traits collection.
     * @var LevyTraitModel[]|TraitCollection
     */
    public TraitCollection|array $traits = [];

    /**
     * Model fields collection.
     * @var LevyFieldModel[]|FieldCollection
     */
    public FieldCollection|array $fields = [];

    /**
     * Model relations collection.
     * @var LevyRelatedTypeModel[]|RelationCollection
     */
    public RelationCollection|array $relations = [];

    /**
     * Additional model dependent tables.
     * @var LevyDependentTableModel[]|DependentTablesCollection|RelationCollection
     */
    public DependentTablesCollection|array $dependent_tables = [];

    /**
     * Collection of rules.
     *
     * @var LevyRuleModel[]|RuleCollection
     */
    public RuleCollection|array $rules = [];

    /**
     * Collection of resources.
     *
     * @var LevyResourceModel[]|ResourceCollection
     */
    public ResourceCollection|array $resources = [];

    /**
     * Collection of properties.
     *
     * @var LevyPropertyModel[]|PropertyCollection
     */
    public PropertyCollection|array $properties = [];

    /**
     * Model related type.
     * @var LevyRelatedTypeModel|null
     */
    public ?LevyRelatedTypeModel $related_type = null;

    /**
     * Model observer.
     * @var LevyObserverModel|null
     */
    public ?LevyObserverModel $observer = null;

    /**
     * Factory model.
     * @var LevyFactoryModel|null
     */
    public ?LevyFactoryModel $factory = null;

    /**
     * Model seed.
     * @var LevySeedModel|null
     */
    public ?LevySeedModel $seed = null;

    /**
     * @var string|null
     */
    public static ?string $collection = ModelCollection::class;

    /**
     * @param  string  $name
     * @param  array  $syntax
     * @param  bool  $collect
     * @return string
     */
    public static function modelName(string $name, array $syntax = [], bool $collect = false): string
    {
        return \Str::singular($name);
    }
}
