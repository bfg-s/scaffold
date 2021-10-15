<?php

namespace Bfg\Scaffold\LevyPipes\LevyMorphToModel;

use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToModel;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Closure;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyMorphToModel
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @param  LevyMorphToModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyMorphToModel $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                $model->morph_name = $type->params[0] ?? $parent->morph_field;
                $model->morph_type = $type->params[1] ?? $model->morph_name.'_type';
                $model->morph_id = $type->params[2] ?? $model->morph_name.'_id';

                $model->relation_params = [
                    $model->morph_name,
                    $model->morph_type,
                    $model->morph_id,
                ];
            });

        return $next($model);
    }
}
