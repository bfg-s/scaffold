<?php

namespace Bfg\Scaffold\LevyPipes;

use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyRelationAbstract;

/**
 * Class ExplainRelationPipeAbstract.
 * @package Bfg\Scaffold\LevyPipes
 */
abstract class ExplainRelationPipeAbstract
{
    /**
     * @var string
     */
    protected string $related_name_convert = 'plural';

    /**
     * @var array
     */
    protected array $related_params = [];

    /**
     * @param  LevyRelationAbstract  $model
     * @param  callable  $callable
     * @throws \Exception
     */
    protected function related(LevyRelationAbstract $model, callable $callable)
    {
        /** @var LevyRelatedTypeModel $field */
        $type = $model->parent;
        $related = $type->related;
        $parent = $type->parent;

        if ($related) {
            $model->related = $related->class_name;

            call_user_func($callable, $type, $related, $parent);

            $related_background =
                $type->related_background ?: config("scaffold.relation_reversals_types.{$type->name}");

            $related_name_convert = config("scaffold.related_name_convert.{$related_background}", 'singular');
            if (
                ! $type->background_addition &&
                $related_background
            ) {
                $name = call_user_func([\Str::class, $related_name_convert], $parent->name);
                if (! $related->relations->where('relation_name', \Str::camel($name))->first()) {
                    $related->relations->push(
                        LevyRelatedTypeModel::model($related_background, [
                            'name' => $related_background,
                            'relation_name' => \Str::camel($name),
                            'parent' => $related,
                            'with_model' => false,
                            'related' => $parent,
                            'nullable' => $type->nullable,
                            'cascade_update' => $type->cascade_update,
                            'cascade_delete' => $type->cascade_delete,
                            'background_addition' => true,
                            'params' => $this->related_params,
                        ])
                    );
                }
            }
        }
    }

    /**
     * @param  LevyRelatedTypeModel  $type
     * @param  string  $table
     * @return array
     */
    protected function makeForeignDefaultParams(LevyRelatedTypeModel $type, string $table): array
    {
        $params = [];

        if ($type->nullable) {
            $params['nullable'] = [];
        }
        $params['constrained'] = $table;
        if ($type->cascade_update) {
            $params['cascadeOnUpdate'] = [];
        }
        if ($type->cascade_delete) {
            $params['cascadeOnDelete'] = [];
        }

        return $params;
    }
}
