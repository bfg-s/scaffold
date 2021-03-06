<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\ResourceCollection;

/**
 * Class LevyRuleModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyResourceModel extends LevyModelAbstract
{
    /**
     * @var LevyRelation\LevyRelationAbstract|LevyModelAbstract|LevyModel|mixed
     */
    public LevyRelation\LevyRelationAbstract|LevyModelAbstract $parent;

    /**
     * Class of resource.
     * @var string
     */
    public string $class;

    /**
     * Class name of resource.
     * @var string
     */
    public string $class_name;

    /**
     * Namespace of resource.
     * @var string
     */
    public string $namespace;

    /**
     * @var string|null
     */
    public static ?string $collection = ResourceCollection::class;
}
