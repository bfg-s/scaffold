<?php

namespace Bfg\Scaffold\LevyPipes\LevyBelongsToModel;

use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToModel;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Closure;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyBelongsToModel
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @var string
     */
    protected string $related_name_convert = 'singular';

    /**
     * @param  LevyBelongsToModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyBelongsToModel $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                $model->related_table = $related->table;
                $model->foreign_key = $type->params[0] ?? $parent->foreign;
                $model->owner_key = $type->params[1] ?? $related->inherited_field;
                $model->relation_params = [
                    $model->related.'::class',
                    $model->foreign_key,
                    $model->owner_key,
                ];
            });

        return $next($model);
    }
}
