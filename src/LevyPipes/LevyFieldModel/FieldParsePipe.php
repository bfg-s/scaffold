<?php

namespace Bfg\Scaffold\LevyPipes\LevyFieldModel;

use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Closure;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class FieldParsePipe.
 * @package Bfg\Scaffold\LevyPipes\LevyFieldModel
 */
class FieldParsePipe
{
    /**
     * @var array
     */
    protected array $default_type = [];

    /**
     * @var array
     */
    protected array $fields = [];

    /**
     * @var array
     */
    protected array $migration_props = [
        'after', 'always', 'autoIncrement', 'charset', 'collation', 'comment', 'constrained',
        'default', 'first', 'index', 'nullable', 'persisted', 'primary', 'spatialIndex',
        'storedAs', 'type', 'unique', 'unsigned', 'useCurrent', 'useCurrentOnUpdate', 'virtualAs',
        'cascadeOnDelete', 'cascadeOnUpdate',
    ];

    /**
     * @param  LevyFieldModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyFieldModel $model, Closure $next): mixed
    {
        $this->default_type = array_values((array) config('scaffold.defaults.field.type', []));

        $parsed = [null, null, [], []];

        if (isset($model->syntax['parse'])) {
            $parsed = $this->parse($model->syntax['parse']);
            unset($model->syntax['parse']);
        }

        list($name, $type, $migration_params, $migration_type_params) = $parsed;

        if (! $name || ! is_string($name)) {
            throw new \Exception('Undefined property [name]');
        } else {
            $model->name = $name;
        }

        if (! $type || ! is_string($type)) {
            throw new \Exception('Undefined property [type]');
        } else {
            $model->type = $type;
        }

        if (! method_exists(Blueprint::class, $model->type)) {
            throw new \Exception("[{$model->type}] - Undefined type of field");
        }

        $model->migration_params = $migration_params;

        $model->migration_type_params = $migration_type_params;

        foreach ($model->migration_params as $migration_param_name => $migration_param) {
            if (! in_array($migration_param_name, $this->migration_props)) {
                $model->params[$migration_param_name] = $migration_param;

                unset($model->migration_params[$migration_param_name]);
            }
        }

        $model->cast = $model->params['cast'] ?? config("scaffold.type_associate.{$model->type}");
        $model->cast = $this->custom_cast($model, $model->cast);

        if (isset($model->params['order']) && ! $model->order) {
            $model->order = $model->params['order'];
        }

        if (isset($model->syntax['order']) && ! $model->order) {
            $model->order = $model->syntax['order'];
        }

        if (! $model->order) {
            $model->order = $model::count();
        }

        if (isset($model->migration_params['default'])) {
            $model->default = $model->migration_params['default'];
        }

        return $next($model);
    }

    /**
     * @param  LevyFieldModel  $model
     * @param  string|null  $cast
     * @return string|null
     */
    protected function custom_cast(LevyFieldModel $model, string $cast = null): ?string
    {
        $custom_class_name = ucfirst(\Str::camel($cast));

        if ($cast == $custom_class_name) {
            $model->cast_class_name = $custom_class_name.'Cast';
            $model->cast_namespace = 'App\\Casts';
            $model->cast_class = $model->cast_namespace.'\\'.$model->cast_class_name;
        }

        return $cast;
    }

    /**
     * @param  array  $syntax
     * @return array
     */
    protected function parse(array $syntax): array
    {
        $syntax = array_values($syntax);

        $name = null;
        $type = null;
        $migration_params = [];
        $migration_type_params = [];

        foreach ($syntax as $i => $val) {
            if ($i === 0 && is_string($val)) {
                $name = $val;
            } else {
                if ($i === 1 && is_string($val)) {
                    $type = $val;
                } else {
                    if (is_array($val)) {
                        foreach ($val as $n => $v) {
                            if (is_int($n)) {
                                $migration_params[$v] = [];
                            } else {
                                $migration_params[$n] = $this->replace_typed_string($v);
                            }
                        }
                    } else {
                        $migration_type_params[] = $this->replace_typed_string($val);
                    }
                }
            }
        }

        $associate = (array) config('scaffold.defaults.field.associate', []);
        $masks = (array) config('scaffold.defaults.field.masks', []);

        $migration_type_params_mask = [];
        $migration_params_mask = [];
        $migration_type_params_field = [];
        $migration_params_field = [];
        $migration_type_params_def = [];
        $migration_params_def = [];

        foreach ($masks as $i_mask => $item_mask) {
            if (\Str::is($i_mask, $name)) {
                $this->each_format(
                    (array) $item_mask,
                    $name,
                    $type,
                    $migration_params_mask,
                    $migration_type_params_field
                );
                break;
            }
        }

        if ($migration_params_mask && ! $migration_params) {
            $migration_params = $migration_params_mask;
        }

        if ($migration_type_params_mask && ! $migration_type_params) {
            $migration_type_params = $migration_type_params_mask;
        }

        if ($name && isset($associate[$name]) && $associate[$name]) {
            $def = (array) $associate[$name];

            $this->each_format(
                $def,
                $name,
                $type,
                $migration_params_field,
                $migration_type_params_field
            );
        }

        if ($migration_params_field && ! $migration_params) {
            $migration_params = $migration_params_field;
        }

        if ($migration_type_params_field && ! $migration_type_params) {
            $migration_type_params = $migration_type_params_field;
        }

        $this->each_format(
            $this->default_type,
            $name,
            $type,
            $migration_params_def,
            $migration_type_params_def
        );

        if ($type === $this->default_type[0]) {
            if (
                $migration_params_def &&
                ! $migration_params
            ) {
                $migration_params = $migration_params_def;
            }

            if (
                $migration_type_params_def &&
                ! $migration_type_params
            ) {
                $migration_type_params = $migration_type_params_def;
            }
        }

        return [$name, $type, $migration_params, $migration_type_params];
    }

    /**
     * @param  array  $data
     * @param $name
     * @param $type
     * @param $migration_params
     * @param $migration_type_params
     */
    protected function each_format(
        array $data,
        &$name,
        &$type,
        &$migration_params,
        &$migration_type_params,
    ) {
        foreach (array_values($data) as $i_def => $item_def) {
            if ($i_def === 0 && is_string($item_def)) {
                $type = ! $type && $item_def ? $item_def : $type;
            } else {
                if (is_array($item_def)) {
                    foreach ($item_def as $n => $v) {
                        if (is_int($n)) {
                            $migration_params[$v] = [];
                        } else {
                            $migration_params[$n] = $this->replace_typed_string($v);
                        }
                    }
                } else {
                    $migration_type_params[] = $this->replace_typed_string($item_def);
                }
            }
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function replace_typed_string($data): mixed
    {
        return $data;
    }
}
