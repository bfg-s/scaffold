<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\TraitCollection;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyObserverModel;
use Bfg\Scaffold\LevyModel\LevyTraitModel;
use Closure;

/**
 * Class DetectObserverPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectObserverPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (isset($model->syntax['observer'])) {
            $model->observer = LevyObserverModel::create([
                'parent' => $model,
                'events' => $model->syntax['observer'],
            ]);

            unset($model->syntax['observer']);
        }

        return $next($model);
    }
}
