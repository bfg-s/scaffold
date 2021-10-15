<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\ResourceCollection;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyResourceModel;
use Closure;

/**
 * Class DetectResourcesPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectResourcesPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->resources) {
            $model->resources = new ResourceCollection();
        }

        if (isset($model->syntax['resource'])) {
            $model->resources->push(LevyResourceModel::create([
                'name' => $model->class_name,
                'parent' => $model,
            ]));

            $resources = (array) $model->syntax['resource'];

            foreach ($resources as $resource) {
                $model->resources->push(LevyResourceModel::create([
                    'name' => $resource,
                    'parent' => $model,
                ]));
            }

            unset($model->syntax['resource']);
        }

        return $next($model);
    }
}
