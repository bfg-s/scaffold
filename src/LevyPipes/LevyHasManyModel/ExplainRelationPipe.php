<?php

namespace Bfg\Scaffold\LevyPipes\LevyHasManyModel;

use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyModel;
use Bfg\Scaffold\LevyPipes\ExplainRelationPipeAbstract;
use Closure;

/**
 * Class ExplainRelationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyHasManyModel
 */
class ExplainRelationPipe extends ExplainRelationPipeAbstract
{
    /**
     * @var string
     */
    protected string $related_name_convert = 'singular';

    /**
     * @param  LevyHasManyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyHasManyModel $model, Closure $next): mixed
    {
        $this->related($model,
            function (LevyRelatedTypeModel $type, LevyModel $related, LevyModel $parent) use ($model) {
                $model->related_table = $related->table;
                $model->foreign_key = $type->params[0] ?? $parent->inherited_field;
                $model->local_key = $type->params[1] ?? $related->foreign;

                $model->relation_params = [
                    $model->related.'::class',
                    $model->foreign_key,
                    $model->local_key,
                ];

                $this->related_params = [
                    $model->foreign_key,
                    $model->local_key,
                ];

                if (! $type->background_addition) {
                    $related->fields->push(
                        LevyFieldModel::model($parent->inherited_field, [
                            'parent' => $related, 'parse' => [
                                $parent->inherited_field, 'foreignId',
                                $this->makeForeignDefaultParams($type, $parent->table),
                            ],
                        ])
                    );
                }

                $related->order = $parent->order + 2;
            });

        return $next($model);
    }
}
