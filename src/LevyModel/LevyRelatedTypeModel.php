<?php

namespace Bfg\Scaffold\LevyModel;

use Bfg\Scaffold\LevyCollections\RelationCollection;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyRelationAbstract;

/**
 * Class LevyRelatedTypeModel.
 * @package Bfg\Scaffold\LevyModel
 */
class LevyRelatedTypeModel extends LevyModelAbstract
{
    /**
     * Params of related.
     *
     * @var array
     */
    public array $params = [];

    /**
     * Parent Levy Model.
     *
     * @var LevyModel
     */
    public LevyModelAbstract $parent;

    /**
     * With whom relationship.
     *
     * @var LevyModel|null
     */
    public ?LevyModel $related = null;

    /**
     * The name of the relationship.
     *
     * @var string|null
     */
    public ?string $relation_name = null;

    /**
     * @var bool
     */
    public bool $nullable = true;

    /**
     * @var bool
     */
    public bool $cascade_update = true;

    /**
     * @var bool
     */
    public bool $cascade_delete = true;

    /**
     * Whether the model was created when the
     * relationship was added.
     *
     * @var bool|null
     */
    public ?bool $with_model = null;

    /**
     * Was adding a relationship from under
     * another relationship.
     *
     * @var bool
     */
    public bool $background_addition = false;

    /**
     * Background relationship.
     *
     * @var bool
     */
    public ?string $related_background = null;

    /**
     * @var LevyRelationAbstract|null
     */
    public ?LevyRelationAbstract $relation = null;

    /**
     * @var string|null
     */
    public static ?string $collection = RelationCollection::class;

    /**
     * @param  array  $syntax
     * @return $this
     * @throws \Exception
     */
    public function syntax(array $syntax = []): static
    {
        if (! isset($syntax['name']) || ! $syntax['name']) {
            throw new \Exception('Enter name of type relation');
        }

        parent::syntax($syntax);

        /** @var LevyRelationAbstract|null $generator */
        $generator = config('scaffold.relation_types.'.$this->name);

        if (! $generator) {
            throw new \Exception("Enter correct name of type relation, name [{$this->name}] is incorrect!");
        }

        $this->relation = $generator::create([
            'parent' => $this,
        ]);

        return $this;
    }

    /**
     * @param  string  $name
     * @param  array  $syntax
     * @param  bool  $collect
     * @return string
     */
    public static function modelName(string $name, array $syntax = [], bool $collect = false): string
    {
        if ($collect && isset($syntax['parent']) && isset($syntax['parent']['name'])) {
            $parent_name = (string) $syntax['parent']['name'];
            $prep_name = $syntax['relation_name'] ?? '';

            return $parent_name.($prep_name ? '.'.$prep_name : '');
        }

        return $name;
    }
}
