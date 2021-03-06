<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRelatedTypeModel;
use Closure;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class DetectRelationsPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectRelationsPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (isset($model->syntax['relations']) && is_array($model->syntax['relations'])) {
            foreach ($model->syntax['relations'] as $relation_name => $relation_syntax) {
                $relation_syntax = (array) $relation_syntax;
                $relation_name = explode(':', $relation_name);
                $name = $relation_name[0];
                $nullable = false;
                $related = null;
                if (preg_match('/^\?(.*)$/', $name, $m)) {
                    $nullable = true;
                    $name = $m[1];
                }
                if (preg_match('/^[0-9]+_(.*)$/', $name, $m)) {
                    $name = $m[1];
                }
                unset($relation_name[0]);
                $model_name = $name;
                $method_params = [];

                $cascade_update = true;
                $cascade_delete = true;

                if (isset($relation_syntax['uncascade']) && $relation_syntax['uncascade']) {
                    $cascade_delete = false;
                    $cascade_update = false;
                    unset($relation_syntax['uncascade']);
                }

                if (isset($relation_syntax['cascade_update'])) {
                    $cascade_update = (bool) $relation_syntax['cascade_update'];
                    unset($relation_syntax['cascade_update']);
                }

                if (isset($relation_syntax['cascade_delete'])) {
                    $cascade_delete = (bool) $relation_syntax['cascade_delete'];
                    unset($relation_syntax['cascade_delete']);
                }

                if (isset($relation_syntax['method'])) {
                    $name = $relation_syntax['method'];
                    unset($relation_syntax['method']);
                }

                if (isset($relation_syntax['nullable'])) {
                    $nullable = (bool) $relation_syntax['nullable'];
                    unset($relation_syntax['nullable']);
                }

                if (isset($relation_syntax['related'])) {
                    $related = $relation_syntax['related'];
                    unset($relation_syntax['related']);
                }

                $with_model = ! LevyModel::has_model($model_name);

                $related_model = $model_name == $model->name ? $model : LevyModel::model($model_name, $relation_syntax);
                $type_name = $relation_syntax['type'] ?? ($relation_name[1] ?? (
                        $related_model->related_type->name ?:
                            config('scaffold.defaults.model.type', 'hasOne')
                        ));
                $type_name_explode = explode(':', $type_name);
                $type_name = $type_name_explode[0];
                unset($type_name_explode[0]);
                if (isset($relation_syntax['field'])) {
                    $related_model->inherited_field = $relation_syntax['field'];
                    unset($relation_syntax['field']);
                }
                if ($type_name) {
                    unset($relation_name[1]);
                }
                $params = array_merge(
                    array_values($relation_name), array_values($type_name_explode)
                );

                $type_name = LevyRelatedTypeModel::model($type_name, array_merge([
                    'name' => $type_name,
                    'relation_name' => \Str::camel($name),
                    'params' => $params,
                    'parent' => $model, //Who owns
                    'with_model' => $with_model,
                    'related' => $related_model, //With whom relationship
                    'nullable' => $nullable,
                    'cascade_update' => $cascade_update,
                    'cascade_delete' => $cascade_delete,
                    'related_background' => $related,
                ], $method_params));

                $model->relations->push($type_name);
            }
            unset($model->syntax['relations']);
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
