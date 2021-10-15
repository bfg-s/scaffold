<?php

namespace Bfg\Scaffold\LevyModel\LevyRelation;

/**
 * Class LevyMorphToModel.
 * @package Bfg\Scaffold\LevyModel\LevyRelation
 */
class LevyMorphToModel extends LevyRelationAbstract
{
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
     * @var string
     */
    public string $relation_class = "Illuminate\Database\Eloquent\Relations\MorphTo";
}
