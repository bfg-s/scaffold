<?php

namespace Bfg\Scaffold\LevyPipes\LevyMorphOneModel;

use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphManyModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphOneModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyRelationAbstract;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Closure;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyMorphOneModel
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @var string
     */
    protected string $related_name_convert = 'singular';

    /**
     * @param  LevyMorphOneModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyMorphOneModel $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                self::makeRelationData($model, $type, $related, $parent);
            });

        return $next($model);
    }

    /**
     * @param  LevyRelationAbstract|LevyMorphOneModel|LevyMorphManyModel|mixed  $model
     * @param  LevyRelatedTypeModel  $type
     * @param  LevyModel  $related
     * @param  LevyModel  $parent
     */
    public static function makeRelationData(
        LevyRelationAbstract $model,
        LevyRelatedTypeModel $type,
        LevyModel $related,
        LevyModel $parent
    ) {
        $model->related_table = $related->table;

        $model->morph_name = $type->params[0] ?? $related->morph_field;
        $model->morph_type = $type->params[1] ?? $model->morph_name.'_type';
        $model->morph_id = $type->params[2] ?? $model->morph_name.'_id';
        $model->local_key = $type->params[3] ?? $parent->foreign;

        $model->relation_params = [
            $model->related.'::class',
            $model->morph_name,
            $model->morph_type,
            $model->morph_id,
            $model->local_key,
        ];
    }
}
