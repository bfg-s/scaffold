<?php

namespace Bfg\Scaffold\LevyPipes\LevyDependentTableModel;

use Bfg\Scaffold\LevyModel\LevyDependentTableModel;
use Closure;
use Illuminate\Support\Str;

/**
 * Class DetectInformationPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyDependentTableModel
 */
class DetectInformationPipe
{
    /**
     * @param  LevyDependentTableModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyDependentTableModel $model, Closure $next): mixed
    {
        $model->class_name = 'Create'.ucfirst(Str::camel($model->name)).'Table';

        $model->file = database_path('migrations/{prefix}_create_'.$model->name.'_table.php');

        return $next($model);
    }
}
