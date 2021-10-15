<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\TraitCollection;
use Bfg\Scaffold\LevyModel\LevyDependentTableModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyTraitModel;
use Closure;

/**
 * Class AddTablePipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class AddTablePipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->table_model) {
            $model->table_model = LevyDependentTableModel::model($model->name, [
                'parent' => $model, 'fields' => $model->fields,
            ]);
        }

        return $next($model);
    }
}
