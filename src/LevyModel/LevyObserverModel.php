<?php

namespace Bfg\Scaffold\LevyModel;

/**
 * Class LevyObserverModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyObserverModel extends LevyModelAbstract
{
    /**
     * @var LevyRelation\LevyRelationAbstract|LevyModelAbstract|LevyModel|mixed
     */
    public LevyRelation\LevyRelationAbstract|LevyModelAbstract $parent;

    /**
     * Observer events.
     * @var array
     */
    public array $events = [];

    /**
     * @var string
     */
    public string $class;

    /**
     * @var string
     */
    public string $class_name;

    /**
     * @var string
     */
    public string $namespace;
}
