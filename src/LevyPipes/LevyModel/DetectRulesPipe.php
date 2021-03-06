<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\RuleCollection;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRuleModel;
use Closure;

/**
 * Class DetectRulesPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectRulesPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->rules) {
            $model->rules = new RuleCollection();
        }

        if (isset($model->syntax['rules']) && $model->syntax['rules'] && is_array($model->syntax['rules'])) {
            $model->rules->class_name = $model->class_name.'Request';
            $model->rules->namespace = 'App\\Http\\Requests';
            $model->rules->class = $model->rules->namespace.'\\'.$model->rules->class_name;

            foreach ($model->syntax['rules'] as $field => $rules) {
                $rules = is_string($rules) ? explode('|', $rules) : $rules;
                if ($rules && is_array($rules)) {
                    foreach ($rules as $item) {
                        if ($item) {
                            $model->rules->push(
                                LevyRuleModel::create([
                                    'name' => $item,
                                    'parent' => $model,
                                    'field' => $field,
                                ])
                            );
                        }
                    }
                }
            }

            unset($model->syntax['rules']);
        }

        return $next($model);
    }
}
