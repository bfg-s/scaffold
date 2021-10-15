<?php

namespace Bfg\Scaffold\LevyModel\LevyRelation;

use Bfg\Scaffold\LevyCollections\RelationCollection;
use Bfg\Scaffold\LevyModel\LevyModelAbstract;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;

/**
 * Class LevyRelationAbstract.
 * @package Bfg\Scaffold\LevyModel\LevyRelation
 */
abstract class LevyRelationAbstract extends LevyModelAbstract
{
    /**
     * Parent Levy Model.
     * @var LevyModelAbstract|LevyRelatedTypeModel|mixed
     */
    public LevyModelAbstract $parent;

    /**
     * @var string|null
     */
    public ?string $related = null;

    /**
     * @var array
     */
    public array $relation_params = [];

    /**
     * @var string
     */
    public string $relation_class;
}
