<?php

namespace Bfg\Scaffold\LevyPipes\LevyBelongsToManyModel;

use Bfg\Scaffold\LevyModel\LevyDependentTableModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToManyModel;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Closure;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyBelongsToManyModel
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @param  LevyBelongsToManyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyBelongsToManyModel $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                $model->foreign_pivot_key = $type->params[1] ?? $parent->inherited_field;
                $model->related_pivot_key = $type->params[2] ?? $related->inherited_field;

                if (
                    $model->foreign_pivot_key == $model->related_pivot_key
                    && $model->parent->name == 'belongsToMany'
                ) {
                    $type->background_addition = true;

                    $model->related_pivot_key = \Str::singular($model->parent->relation_name).'_id';

                    $model->related_table =
                        \Str::plural($type->params[0] ?? \Str::singular($parent->table).'_'.\Str::singular($model->parent->relation_name));
                } else {
                    $model->related_table =
                        \Str::plural($type->params[0] ?? \Str::singular($parent->table).'_'.\Str::singular($related->table));
                }

                $model->relation_params = [
                    $model->related.'::class',
                    $model->related_table,
                    $model->foreign_pivot_key,
                    $model->related_pivot_key,
                ];

                $this->related_params = [
                    $model->related_table,
                    $model->related_pivot_key,
                    $model->foreign_pivot_key,
                ];

                if (! $type->background_addition || $model->parent->name == 'belongsToMany') {
                    $params2 = [];
                    if ($type->nullable) {
                        $params2['nullable'] = [];
                    }
                    $params = [];
                    if ($type->cascade_update) {
                        $params['cascadeOnUpdate'] = [];
                    }
                    if ($type->cascade_delete) {
                        $params['cascadeOnDelete'] = [];
                    }
                    if (! $parent->dependent_tables->where('name', $model->related_table)->count()) {
                        $parent->dependent_tables->push(
                            LevyDependentTableModel::model($model->related_table, [
                                'parent' => $parent, 'fields' => [
                                    [
                                        $model->foreign_pivot_key, 'foreignId',
                                        array_merge($params2, ['constrained' => $parent->table], $params),
                                    ],
                                    [
                                        $model->related_pivot_key, 'foreignId',
                                        array_merge($params2, ['constrained' => $related->table], $params),
                                    ],
                                ],
                            ])
                        );
                    }
                }
            });

        return $next($model);
    }
}
