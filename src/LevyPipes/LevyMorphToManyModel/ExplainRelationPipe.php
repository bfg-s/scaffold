<?php

namespace Bfg\Scaffold\LevyPipes\LevyMorphToManyModel;

use Bfg\Scaffold\LevyModel\LevyDependentTableModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToManyModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyRelationAbstract;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Closure;
use Illuminate\Support\Str;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyMorphToManyModel
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @param  LevyMorphToManyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyMorphToManyModel $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                static::makeRelationData($model, $type, $related, $parent);
            });

        return $next($model);
    }

    /**
     * @param  LevyRelationAbstract  $model
     * @param  LevyRelatedTypeModel  $type
     * @param  LevyModel  $related
     * @param  LevyModel  $parent
     * @throws \Exception
     */
    public static function makeRelationData(
        LevyRelationAbstract $model,
        LevyRelatedTypeModel $type,
        LevyModel $related,
        LevyModel $parent
    ) {
        $model->morph_name = $type->params[0] ?? $related->morph_field;
        $model->morph_table = $type->params[1] ?? Str::plural($model->morph_name);
        $model->foreign_pivot_key = $type->params[2] ?? $model->morph_name.'_id';
        $model->related_pivot_key = $type->params[3] ?? $related->inherited_field;
        $model->parent_key = $type->params[4] ?? $parent->foreign;
        $model->related_key = $type->params[5] ?? $related->foreign;

        $model->relation_params = [
            $model->related.'::class',
            $model->morph_name,
            $model->morph_table,
            $model->foreign_pivot_key,
            $model->related_pivot_key,
            $model->parent_key,
            $model->related_key,
        ];

        if (! $type->background_addition) {
            if (! $related->dependent_tables->where('name', $model->morph_table)->count()) {
                $related->dependent_tables->push(
                    LevyDependentTableModel::model($model->morph_table, [
                        'parent' => $parent, 'fields' => [
                            [$model->related_pivot_key, 'bigIncrements'],
                            [$model->foreign_pivot_key, 'bigInteger'],
                            [$model->morph_name.'_type', 'string'],
                        ],
                    ])
                );
            }
        }
    }
}
