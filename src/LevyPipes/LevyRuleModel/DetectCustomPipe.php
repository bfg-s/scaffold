<?php

namespace Bfg\Scaffold\LevyPipes\LevyRuleModel;

use Bfg\Scaffold\LevyModel\LevyRuleModel;
use Closure;

/**
 * Class DetectCustomPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyRuleModel
 */
class DetectCustomPipe
{
    /**
     * @param  LevyRuleModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyRuleModel $model, Closure $next): mixed
    {
        $clear_name = preg_replace('/[^a-zA-Z0-9]/', '', $model->name);

        if ($model->name == ucfirst($clear_name)) {
            $model->default = false;
            $model->class_name = ucfirst(\Str::camel($model->name)).'Rule';
            $model->namespace = 'App\\Rules';
            $model->class = $model->namespace.'\\'.$model->class_name;
            $model->rule = $model->class;
        } else {
            $model->rule = $model->name;
        }

        return $next($model);
    }
}
