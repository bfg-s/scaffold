<?php

namespace Bfg\Scaffold\LevyModel\LevyRelation;

use Bfg\Scaffold\LevyModel\LevyModel;

/**
 * Class LevyHasOneThroughModel.
 * @package Bfg\Scaffold\LevyModel\LevyRelation
 */
class LevyHasOneThroughModel extends LevyRelationAbstract
{
    /**
     * @var LevyModel|null
     */
    public ?LevyModel $through_model = null;

    /**
     * @var string|null
     */
    public ?string $related_table = null;

    /**
     * @var string|null
     */
    public ?string $through = null;

    /**
     * @var string|null
     */
    public ?string $first_key = null;

    /**
     * @var string|null
     */
    public ?string $second_key = null;

    /**
     * @var string|null
     */
    public ?string $local_key = null;

    /**
     * @var string|null
     */
    public ?string $second_local_key = null;

    /**
     * @var string
     */
    public string $relation_class = "Illuminate\Database\Eloquent\Relations\HasOneThrough";
}
