<?php

namespace Bfg\Scaffold\LevyPipes;

use Bfg\Scaffold\LevyModel\LevyModelAbstract;
use Closure;

/**
 * Class SetPipe.
 * @package Bfg\Scaffold\LevyPipes
 */
class SetPipe
{
    /**
     * @param  LevyModelAbstract  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyModelAbstract $model, Closure $next): mixed
    {
        foreach ($model->syntax as $key => $syntax) {
            if (
                ! is_numeric($key)
                && $key !== 'relations'
            ) {
                if (is_string($syntax)) {
                    $syntax = trim(tag_replace(trim($syntax), $model), '\\');
                }

                $model->{$key} = $syntax;

                unset($model->syntax[$key]);
            }
        }

        return $next($model);
    }
}
