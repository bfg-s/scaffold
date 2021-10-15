<?php

namespace Bfg\Scaffold\LevyModel\LevyRelation;

/**
 * Class LevyBelongsToManyModel.
 * @package Bfg\Scaffold\LevyModel\LevyRelation
 */
class LevyBelongsToManyModel extends LevyRelationAbstract
{
    /**
     * @var string|null
     */
    public ?string $related_table = null;

    /**
     * @var string|null
     */
    public ?string $foreign_pivot_key = null;

    /**
     * @var string|null
     */
    public ?string $related_pivot_key = null;

    /**
     * @var string
     */
    public string $relation_class = "Illuminate\Database\Eloquent\Relations\BelongsToMany";
}
