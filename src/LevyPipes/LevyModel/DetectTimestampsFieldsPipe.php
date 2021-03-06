<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\DependentTablesCollection;
use Bfg\Scaffold\LevyModel\LevyDependentTableModel;
use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Closure;

/**
 * Class DetectTimestampsFieldsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectTimestampsFieldsPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if ($model->updated) {
            $model->fields->push(
                LevyFieldModel::model($model->updated, [
                    'parent' => $model, 'parse' => [$model->updated], 'order' => 999999998,
                ])
            );
        }

        if ($model->created) {
            $model->fields->push(
                LevyFieldModel::model($model->created, [
                    'parent' => $model, 'parse' => [$model->created], 'order' => 999999999,
                ])
            );
        }

        if ($model->traits->where('name', 'SoftDeletes')->count()) {
            $model->fields->push(
                LevyFieldModel::model('deleted_at', [
                    'parent' => $model, 'parse' => ['deleted_at'], 'order' => 999999999,
                ])
            );
        }

        return $next($model);
    }
}
