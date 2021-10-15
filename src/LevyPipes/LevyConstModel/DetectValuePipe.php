<?php

namespace Bfg\Scaffold\LevyPipes\LevyConstModel;

use Bfg\Scaffold\LevyModel\LevyConstModel;
use Closure;

/**
 * Class DetectConstantsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectValuePipe
{
    /**
     * @param  LevyConstModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyConstModel $model, Closure $next): mixed
    {
        if (isset($model->syntax['value'])) {
            $model->value = $model->syntax['value'];
            unset($model->syntax['value']);
        }

        return $next($model);
    }
}
