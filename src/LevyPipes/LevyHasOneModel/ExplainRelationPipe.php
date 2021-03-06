<?php

namespace Bfg\Scaffold\LevyPipes\LevyHasOneModel;

use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyRelationAbstract;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Closure;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyHasOneModel
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @var string
     */
    protected string $related_name_convert = 'plural';

    /**
     * @param  LevyRelationAbstract|LevyHasOneModel|mixed  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyRelationAbstract $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                $model->related_table = $related->table;
                $model->foreign_key = $type->params[0] ?? $parent->foreign;
                $model->local_key = $type->params[1] ?? $related->inherited_field;

                $model->relation_params = [
                    $model->related.'::class',
                    $model->foreign_key,
                    $model->local_key,
                ];

                $this->related_params = [
                    $model->local_key,
                    $model->foreign_key,
                ];

                if (! $type->background_addition) {
                    $parent->fields->push(
                        LevyFieldModel::model($model->local_key, [
                            'parent' => $parent, 'parse' => [
                                $model->local_key, 'foreignId', $this->makeForeignDefaultParams($type, $related->table),
                            ],
                        ])
                    );
                }
            });

        return $next($model);
    }
}
