<?php

namespace Bfg\Scaffold\LevyCollections;

use Bfg\Scaffold\LevyModel\LevyResourceModel;

/**
 * Class ResourceCollection.
 * @package Bfg\Scaffold\LevyCollections
 */
class ResourceCollection extends CollectionAbstract
{
    /**
     * @var LevyResourceModel[]
     */
    protected $items = [];
}
