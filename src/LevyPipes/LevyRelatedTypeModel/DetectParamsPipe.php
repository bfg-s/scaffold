<?php

namespace Bfg\Scaffold\LevyPipes\LevyRelatedTypeModel;

use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Closure;

/**
 * Class DetectParamsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyRelatedTypeModel
 */
class DetectParamsPipe
{
    /**
     * @param  LevyRelatedTypeModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyRelatedTypeModel $model, Closure $next): mixed
    {
        if (isset($model->syntax['params'])) {
            $model->params = $model->syntax['params'];
            unset($model->syntax['params']);
        }

        return $next($model);
    }
}
