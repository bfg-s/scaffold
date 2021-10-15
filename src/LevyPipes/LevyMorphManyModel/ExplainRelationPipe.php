<?php

namespace Bfg\Scaffold\LevyPipes\LevyMorphManyModel;

use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphManyModel;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Bfg\Scaffold\LevyPipes\LevyMorphOneModel\ExplainRelationPipe as ExplainMorphManyRelationPipe;
use Closure;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyMorphManyModel
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @var string
     */
    protected string $related_name_convert = 'singular';

    /**
     * @param  LevyMorphManyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyMorphManyModel $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                ExplainMorphManyRelationPipe::makeRelationData($model, $type, $related, $parent);

                if (! $related->fields->where('name', $model->morph_type)->count()) {
                    $params = [];
                    if ($type->nullable) {
                        $params['nullable'] = [];
                    }
                    $related->fields->push(
                        LevyFieldModel::model($model->morph_type, [
                            'parent' => $parent, 'parse' => [$model->morph_type, 'string', $params],
                        ])
                    );
                }

                if (! $related->fields->where('name', $model->morph_id)->count()) {
                    $params = [];
                    if ($type->nullable) {
                        $params['nullable'] = [];
                    }
                    $related->fields->push(
                        LevyFieldModel::model($model->morph_id, [
                            'parent' => $parent, 'parse' => [$model->morph_id, 'bigInteger', $params],
                        ])
                    );
                }
            });

        return $next($model);
    }
}
