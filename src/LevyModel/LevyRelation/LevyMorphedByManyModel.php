<?php

namespace Bfg\Scaffold\LevyModel\LevyRelation;

/**
 * Class LevyMorphedByManyModel.
 * @package Bfg\Scaffold\LevyModel\LevyRelation
 */
class LevyMorphedByManyModel extends LevyRelationAbstract
{
    /**
     * @var string|null
     */
    public ?string $morph_name = null;

    /**
     * @var string|null
     */
    public ?string $morph_table = null;

    /**
     * @var string|null
     */
    public ?string $foreign_pivot_key = null;

    /**
     * @var string|null
     */
    public ?string $related_pivot_key = null;

    /**
     * @var string|null
     */
    public ?string $parent_key = null;

    /**
     * @var string|null
     */
    public ?string $related_key = null;

    /**
     * @var string
     */
    public string $relation_class = "Illuminate\Database\Eloquent\Relations\MorphedByMany";
}
