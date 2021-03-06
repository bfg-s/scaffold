<?php

namespace Bfg\Scaffold\LevyModel\LevyRelation;

/**
 * Class LevyBelongsToModel.
 * @package Bfg\Scaffold\LevyModel\LevyRelation
 */
class LevyBelongsToModel extends LevyRelationAbstract
{
    /**
     * @var string|null
     */
    public ?string $related_table = null;

    /**
     * @var string|null
     */
    public ?string $foreign_key = null;

    /**
     * @var string|null
     */
    public ?string $owner_key = null;

    /**
     * @var string
     */
    public string $relation_class = "Illuminate\Database\Eloquent\Relations\BelongsTo";
}
