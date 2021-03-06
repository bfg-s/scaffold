<?php

namespace Bfg\Scaffold\LevyPipes\LevyObserverModel;

use Bfg\Scaffold\LevyModel\LevyConstModel;
use Bfg\Scaffold\LevyModel\LevyObserverModel;
use Closure;

/**
 * Class MakeInfoPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyObserverModel
 */
class MakeInfoPipe
{
    /**
     * @param  LevyObserverModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyObserverModel $model, Closure $next): mixed
    {
        if (isset($model->parent)) {
            $model->class_name = $model->parent->class_name.'Observer';
            $model->namespace = 'App\\Observers';
            $model->class = $model->namespace.'\\'.$model->class_name;
        }

        return $next($model);
    }
}
