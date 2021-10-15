<?php

namespace Bfg\Scaffold\LevyPipes\LevyHasOneThroughModel;

use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneThroughModel;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Closure;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\HasOneThrough
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @param  LevyHasOneThroughModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyHasOneThroughModel $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                if (! isset($type->params[0])) {
                    throw new \Exception("Enter a through model for [{$type->name}] relation!");
                }

                $model->through_model = LevyModel::model($type->params[0], []);

                $model->related_table = $related->table;

                $model->through = $model->through_model->class;

                $model->first_key = $type->params[1] ?? $parent->inherited_field;
                if ($model->first_key !== $model->through_model->foreign && ! $related->fields->where('name',
                        $model->first_key)->count()) {
                    $model->through_model->fields->push(
                        LevyFieldModel::model($model->first_key, [
                            'parent' => $model->through_model, 'parse' => [
                                $model->first_key, 'foreignId', $this->makeForeignDefaultParams($type, $parent->table),
                            ],
                        ])
                    );
                }

                $model->second_key = $type->params[2] ?? $model->through_model->inherited_field;
                if ($model->second_key !== $related->foreign && ! $related->fields->where('name',
                        $model->second_key)->count()) {
                    $related->fields->push(
                        LevyFieldModel::model($model->second_key, [
                            'parent' => $related, 'parse' => [
                                $model->second_key, 'foreignId', $this->makeForeignDefaultParams($type, $related->table),
                            ],
                        ])
                    );
                }

                $model->local_key = $type->params[3] ?? $parent->foreign;

                $model->second_local_key = $type->params[4] ?? $model->through_model->foreign;

                $model->relation_params = [
                    $model->related.'::class',
                    $model->through,
                    $model->first_key,
                    $model->second_key,
                    $model->local_key,
                    $model->second_local_key,
                ];
            });

        return $next($model);
    }
}
