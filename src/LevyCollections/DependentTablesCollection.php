<?php

namespace Bfg\Scaffold\LevyCollections;

use Bfg\Scaffold\LevyModel\LevyDependentTableModel;

/**
 * Class DependentTablesCollection.
 * @package Bfg\Scaffold\LevyCollections
 */
class DependentTablesCollection extends CollectionAbstract
{
    /**
     * @var LevyDependentTableModel[]
     */
    protected $items = [];
}
