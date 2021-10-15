<?php

namespace Bfg\Scaffold\LevyCollections;

use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;

/**
 * Class RelationCollection.
 * @package Bfg\Scaffold\LevyCollections
 */
class RelationCollection extends CollectionAbstract
{
    /**
     * @var LevyRelatedTypeModel[]
     */
    protected $items = [];
}
