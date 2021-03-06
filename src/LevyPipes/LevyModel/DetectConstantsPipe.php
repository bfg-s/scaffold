<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\ConstantCollection;
use Bfg\Scaffold\LevyModel\LevyConstModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Closure;

/**
 * Class DetectConstantsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectConstantsPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->constants) {
            $model->constants = new ConstantCollection();
        }

        foreach ($model->syntax as $syntax_key => $syntax_value) {
            if (preg_match('/^const:([A-Za-z0-9_]*)$/', $syntax_key, $m)) {
                $model->constants->push(
                    LevyConstModel::model($m[1], [
                        'value' => ! is_array($syntax_value) ? tag_replace($syntax_value, $model) : array_map(fn ($sv
                        ) => ! is_array($sv) ? tag_replace($sv, $model) : $sv, $syntax_value),
                        'parent' => $model,
                    ])
                );
                unset($model->syntax[$syntax_key]);
            } else {
                if ($syntax_key === 'const' && is_array($syntax_value) && is_assoc($syntax_value)) {
                    foreach ($syntax_value as $key => $item) {
                        $model->constants->push(
                            LevyConstModel::model($key, [
                                'value' => ! is_array($item) ? tag_replace($item, $model) : array_map(fn ($sv
                                ) => ! is_array($sv) ? tag_replace($sv, $model) : $sv, $item),
                                'parent' => $model,
                            ])
                        );
                    }
                    unset($model->syntax[$syntax_key]);
                }
            }
        }

        foreach (config('scaffold.defaults.const') as $name => $item) {
            if (! $model->constants->where('name', $name)->first()) {
                $model->constants->push(
                    LevyConstModel::model($name, [
                        'value' => ! is_array($item) ? tag_replace($item, $model) : array_map(fn ($sv
                        ) => ! is_array($sv) ? tag_replace($sv, $model) : $sv, $item),
                        'parent' => $model,
                    ])
                );
            }
        }

        return $next($model);
    }
}
