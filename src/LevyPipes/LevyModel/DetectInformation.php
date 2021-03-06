<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\RelationCollection;
use Bfg\Scaffold\LevyModel\LevyModel;
use Closure;

/**
 * Class DetectInformation.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectInformation
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (! $model->relations) {
            $model->relations = new RelationCollection();
        }

        if (isset($model->syntax['order']) && $model->syntax['order']) {
            $model->order = (int) trim($model->syntax['order']);
            unset($model->syntax['order']);
        }

        if (isset($model->syntax['class_name']) && $model->syntax['class_name']) {
            $model->class_name = trim($model->syntax['class_name']);
            unset($model->syntax['class_name']);
        } else {
            $model->class_name = ucfirst(\Str::camel($model->name));
        }

        $inline_default_fields = ['created', 'updated', 'foreign', 'namespace', 'path'];

        foreach ($inline_default_fields as $inline_default_field) {
            if (isset($model->syntax[$inline_default_field])) {
                $def = $model->syntax[$inline_default_field];
                unset($model->syntax[$inline_default_field]);
            } else {
                $def = config("scaffold.defaults.model.{$inline_default_field}");
            }

            if (is_string($def)) {
                $def = trim(tag_replace(trim($def), $model), '\\');
            }

            $model->{$inline_default_field} = $def;
        }

        if (isset($model->syntax['class']) && $model->syntax['class']) {
            $model->class = trim($model->syntax['class'], '\\');
            unset($model->syntax['class']);
        } else {
            $model->class = "{$model->namespace}\\{$model->class_name}";
        }

        if (isset($model->syntax['table']) && $model->syntax['table']) {
            $model->table = strtolower(trim($model->syntax['table']));
            unset($model->syntax['table']);
        } else {
            $model->table = strtolower(\Str::plural($model->name));
        }

        if (isset($model->syntax['inherited_field']) && $model->syntax['inherited_field']) {
            $model->inherited_field = trim($model->syntax['inherited_field']);
            unset($model->syntax['inherited_field']);
        } else {
            $model->inherited_field = $model->name.'_'.($model->foreign ?: 'id');
        }

        if (isset($model->syntax['morph_field']) && $model->syntax['morph_field']) {
            $model->morph_field = trim($model->syntax['morph_field']);
            unset($model->syntax['morph_field']);
        } else {
            $model->morph_field = $model->name.'able';
        }

        $model->file = app_path("Models/{$model->class_name}.php");

        return $next($model);
    }
}
