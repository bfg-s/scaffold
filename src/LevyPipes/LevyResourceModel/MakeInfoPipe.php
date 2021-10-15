<?php

namespace Bfg\Scaffold\LevyPipes\LevyResourceModel;

use Bfg\Scaffold\LevyModel\LevyResourceModel;
use Closure;

/**
 * Class MakeInfoPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyResourceModel
 */
class MakeInfoPipe
{
    /**
     * @param  LevyResourceModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyResourceModel $model, Closure $next): mixed
    {
        $model->class_name = ucfirst(\Str::camel($model->name)).'Resource';
        $model->namespace = 'App\\Http\\Resources';
        $model->class = $model->namespace.'\\'.$model->class_name;

        return $next($model);
    }
}
