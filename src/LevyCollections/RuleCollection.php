<?php

namespace Bfg\Scaffold\LevyCollections;

use Bfg\Scaffold\LevyModel\LevyRuleModel;

/**
 * Class RuleCollection.
 * @package Bfg\Scaffold\LevyCollections
 */
class RuleCollection extends CollectionAbstract
{
    /**
     * @var LevyRuleModel[]
     */
    protected $items = [];

    /**
     * Class of request.
     * @var string
     */
    public string $class;

    /**
     * Class name of request.
     * @var string
     */
    public string $class_name;

    /**
     * Namespace of request.
     * @var string
     */
    public string $namespace;
}
