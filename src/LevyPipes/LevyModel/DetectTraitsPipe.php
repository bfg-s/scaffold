<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\TraitCollection;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyTraitModel;
use Closure;

/**
 * Class DetectTraitsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectTraitsPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->traits) {
            $model->traits = new TraitCollection();
        }

        if (isset($model->syntax['traits']) && $model->syntax['traits'] && is_array($model->syntax['traits'])) {
            foreach ($model->syntax['traits'] as $trait) {
                $model->traits->push(
                    LevyTraitModel::model($trait, [
                        'parent' => $model,
                    ])
                );
            }

            unset($model->syntax['traits']);
        }

        foreach (config('scaffold.defaults.trait.to_each', []) as $item) {
            $model->traits->push(
                LevyTraitModel::model($item, [
                    'parent' => $model,
                ])
            );
        }

        return $next($model);
    }
}
