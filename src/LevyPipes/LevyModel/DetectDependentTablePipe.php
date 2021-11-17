<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\DependentTablesCollection;
use Bfg\Scaffold\LevyModel\LevyDependentTableModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Closure;

/**
 * Class DetectDependentTablePipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectDependentTablePipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->dependent_tables) {
            $model->dependent_tables = new DependentTablesCollection();
        }

        if (
            isset($model->syntax['dependent_tables']) &&
            $model->syntax['dependent_tables'] &&
            is_array($model->syntax['dependent_tables']) &&
            is_assoc($model->syntax['dependent_tables'])
        ) {
            foreach ($model->syntax['dependent_tables'] as $name => $syntax) {
                if (! $model->dependent_tables->where('name', $name)->count()) {
                    $model->dependent_tables->push(
                        LevyDependentTableModel::model($name, ['fields' => $syntax])
                    );
                }
            }
            unset($model->syntax['dependent_tables']);
        }

        return $next($model);
    }
}
