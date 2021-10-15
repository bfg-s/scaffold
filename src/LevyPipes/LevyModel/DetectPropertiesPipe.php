<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\ConstantCollection;
use Bfg\Scaffold\LevyCollections\PropertyCollection;
use Bfg\Scaffold\LevyModel\LevyConstModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyPropertyModel;
use Closure;

/**
 * Class DetectPropertiesPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectPropertiesPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->properties) {
            $model->properties = new PropertyCollection();
        }

        foreach ($model->syntax as $syntax_key => $syntax_value) {
            if (preg_match('/^prop:([A-Za-z0-9_\s:]*)$/', $syntax_key, $m)) {
                $model->properties->push(
                    LevyPropertyModel::model($m[1], [
                        'value' => ! is_array($syntax_value) ? tag_replace($syntax_value, $model) : array_map(fn ($sv
                        ) => ! is_array($sv) ? tag_replace($sv, $model) : $sv, $syntax_value),
                        'parent' => $model,
                        'property_name' => $m[1],
                    ])
                );
                unset($model->syntax[$syntax_key]);
            } else {
                if ($syntax_key === 'properties' && is_array($syntax_value) && is_assoc($syntax_value)) {
                    foreach ($syntax_value as $key => $item) {
                        $model->properties->push(
                            LevyPropertyModel::model($key, [
                                'value' => ! is_array($item) ? tag_replace($item, $model) : array_map(fn ($sv
                                ) => ! is_array($sv) ? tag_replace($sv, $model) : $sv, $item),
                                'parent' => $model,
                                'property_name' => $key,
                            ])
                        );
                    }
                    unset($model->syntax[$syntax_key]);
                }
            }
        }

        return $next($model);
    }
}
