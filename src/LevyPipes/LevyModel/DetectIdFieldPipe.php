<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\FieldCollection;
use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Closure;

/**
 * Class DetectIdFieldsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectIdFieldPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->fields) {
            $model->fields = new FieldCollection();
        }

        if ($model->foreign) {
            $model->fields->push(
                LevyFieldModel::model($model->foreign, [
                    'parent' => $model, 'parse' => [$model->foreign, 'bigIncrements'],
                ])
            );
        }

        return $next($model);
    }
}
