<?php

namespace Bfg\Scaffold\LevyPipes\LevyObserverModel;

use Bfg\Scaffold\LevyModel\LevyConstModel;
use Bfg\Scaffold\LevyModel\LevyObserverModel;
use Closure;

/**
 * Class DetectEventsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyObserverModel
 */
class DetectEventsPipe
{
    /**
     * @param  LevyObserverModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyObserverModel $model, Closure $next): mixed
    {
        if (isset($model->syntax['events'])) {
            $model->events = array_values((array) $model->syntax['events']);

            if (! $model->events) {
                $model->events = [
                    'retrieved', 'creating', 'created', 'updating', 'updated',
                    'saving', 'saved', 'restoring', 'restored', 'replicating',
                    'deleting', 'deleted', 'forceDeleted',
                ];
            }

            unset($model->syntax['events']);
        }

        return $next($model);
    }
}
