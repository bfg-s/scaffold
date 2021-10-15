<?php

namespace Bfg\Scaffold\LevyCollections;

use Bfg\Scaffold\LevyModel\LevyModel;

/**
 * Class ModelCollection.
 * @package Bfg\Scaffold\LevyCollections
 */
class ModelCollection extends CollectionAbstract
{
    /**
     * @var LevyModel[]
     */
    protected $items = [];
}
