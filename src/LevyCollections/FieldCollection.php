<?php

namespace Bfg\Scaffold\LevyCollections;

use Bfg\Scaffold\LevyModel\LevyFieldModel;

/**
 * Class FieldCollection.
 * @package Bfg\Scaffold\LevyCollections
 */
class FieldCollection extends CollectionAbstract
{
    /**
     * @var LevyFieldModel[]
     */
    protected $items = [];
}
