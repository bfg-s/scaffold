<?php

namespace Bfg\Scaffold\LevyPipes\LevyTraitModel;

use Bfg\Scaffold\LevyModel\LevyConstModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Bfg\Scaffold\LevyModel\LevyTraitModel;
use Closure;
use Illuminate\Support\Str;

/**
 * Class TraitParsePipe.
 * @package Bfg\Scaffold\LevyPipes\LevyTraitModel
 */
class TraitParsePipe
{
    /**
     * @param  LevyTraitModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyTraitModel $model, Closure $next): mixed
    {
        if ($model->name && trait_exists($model->name)) {
            $model->class = $model->name;
        }

        $associate = config('scaffold.defaults.trait.associate');

        if (
            ! $model->class &&
            isset($associate[$model->name]) &&
            $associate[$model->name] &&
            trait_exists($associate[$model->name])
        ) {
            $model->class = $associate[$model->name];
        }

        if (! $model->class) {
            $macros = config('scaffold.defaults.trait.macros');

            if (isset($macros[$model->name])) {
                $macro = $macros[$model->name];

                if (is_array($macro) && isset($macro['class']) && isset($macro['path'])) {
                    $model->class = tag_replace($macro['class'], $model->parent);
                    $model->path = base_path(tag_replace($macro['path'], $model->parent));
                }
            }
        }

        if (! $model->class) {
            $n = ucfirst(Str::camel($model->name));

            $model->class = tag_replace(
                config('scaffold.defaults.trait.class'),
                ['class_name' => $model->parent->class_name, 'name' => $n],
            );
            $model->path = base_path(tag_replace(
                config('scaffold.defaults.trait.path'),
                ['class_name' => $model->parent->class_name, 'name' => $n],
            ));
        }

        $model->class_name = str_replace('/', '\\', basename(str_replace('\\', '/', $model->class)));
        $model->namespace = str_replace('\\'.$model->class_name, '', $model->class);

        return $next($model);
    }
}
