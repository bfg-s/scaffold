<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\RuleCollection;

/**
 * Class LevyRuleModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyRuleModel extends LevyModelAbstract
{
    /**
     * @var LevyRelation\LevyRelationAbstract|LevyModelAbstract|LevyModel|mixed
     */
    public LevyRelation\LevyRelationAbstract|LevyModelAbstract $parent;

    /**
     * Is default laravel rule.
     * @var bool
     */
    public bool $default = true;

    /**
     * Rule field.
     * @var string|null
     */
    public ?string $field;

    /**
     * Some rule for insert.
     * @var string|null
     */
    public ?string $rule;

    /**
     * Class of custom rule.
     * @var string
     */
    public string $class;

    /**
     * Class name of custom rule.
     * @var string
     */
    public string $class_name;

    /**
     * Namespace of custom rule.
     * @var string
     */
    public string $namespace;

    /**
     * @var string|null
     */
    public static ?string $collection = RuleCollection::class;
}
