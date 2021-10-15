<?php

namespace Bfg\Scaffold\LevyModel\LevyRelation;

/**
 * Class LevyMorphManyModel.
 * @package Bfg\Scaffold\LevyModel\LevyRelation
 */
class LevyMorphManyModel extends LevyRelationAbstract
{
    /**
     * @var string|null
     */
    public ?string $related_table = null;

    /**
     * @var string|null
     */
    public ?string $morph_name = null;

    /**
     * @var string|null
     */
    public ?string $morph_type = null;

    /**
     * @var string|null
     */
    public ?string $morph_id = null;

    /**
     * @var string|null
     */
    public ?string $local_key = null;

    /**
     * @var string
     */
    public string $relation_class = "Illuminate\Database\Eloquent\Relations\MorphMany";
}
