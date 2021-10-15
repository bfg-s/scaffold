<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\FieldCollection;
use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyModelAbstract;
use Closure;

/**
 * Class DetectFieldsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectFieldsPipe
{
    /**
     * @param  LevyModel|LevyModelAbstract  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModelAbstract $model, Closure $next): mixed
    {
        if (! $model->fields) {
            $model->fields = new FieldCollection();
        }

        if (
            isset($model->syntax['fields']) &&
            $model->syntax['fields'] &&
            is_array($model->syntax['fields'])
        ) {
            foreach ($model->syntax['fields'] as $syntax) {
                $syntax = (array) $syntax;
                if (! isset($syntax[0]) || ! is_string($syntax[0])) {
                    throw new \Exception('Undefined property [name]');
                }
                $model->fields->push(
                    LevyFieldModel::model(
                        $syntax[0], ['parent' => $model, 'parse' => $syntax]
                    )
                );
            }

            unset($model->syntax['fields']);
        }

        return $next($model);
    }
}
