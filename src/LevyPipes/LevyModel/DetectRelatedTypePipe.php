<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Closure;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class DetectRelatedTypePipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectRelatedTypePipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (isset($model->syntax['type']) && is_string($model->syntax['type'])) {
            $props = explode(':', $model->syntax['type']);

            $p = $this->props(...$props);
            $model->related_type = LevyRelatedTypeModel::create(
                array_merge($p, [
                        'parent' => $model,
                    ]
                )
            );

            unset($model->syntax['type']);
        } else {
            $model->related_type = LevyRelatedTypeModel::create([
                'parent' => $model,
                'name' => config('scaffold.defaults.model.type'),
            ]);
        }

        return $next($model);
    }

    /**
     * @param  string  $name
     * @param ...$params
     * @return array
     */
    #[ArrayShape(['name' => 'string', 'params' => 'array'])]

    protected function props(
        string $name,
        ...$params
    ): array {
        return [
            'name' => $name,
            'params' => $params,
        ];
    }
}
