<?php

namespace Bfg\Scaffold\LevyModel\LevyRelation;

/**
 * Class LevyHasManyModel.
 * @package Bfg\Scaffold\LevyModel\LevyRelation
 */
class LevyHasManyModel extends LevyRelationAbstract
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
    public ?string $local_key = null;

    /**
     * @var string
     */
    public string $relation_class = "Illuminate\Database\Eloquent\Relations\HasMany";
}
