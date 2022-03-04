<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Scaffold\LevyModel\LevyConstModel;
use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyPropertyModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MakeModelListen.
 * @package Bfg\Scaffold\Listeners
 */
class MakeModelListen extends ListenerControl
{
    /**
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        $this->storage()->store(
            ...$this->makeFile($model)
        )->save();
    }

    /**
     * @param  LevyModel  $model
     * @return array
     */
    protected function makeFile(LevyModel $model): array
    {
        $class = class_entity($model->class_name)
            ->namespace($model->namespace);

        if (! config('scaffold.doc_block.class.model')) {
            $class->doc(function () {
            });
        }

        if ($model->auth) {
            $class->use('Illuminate\Foundation\Auth\User as Authenticatable');
            $class->extend(entity('Authenticatable'));
        } else {
            $class->extend(Model::class);
        }

        if ($model->must_verify) {
            $class->implement(MustVerifyEmail::class);
        }

        foreach ($model->traits as $trait) {
            $class->addTrait($trait->class);
        }

        foreach ($model->constants as $constant) {
            $class->const(strtoupper($constant->name), $this->format($constant->value));
        }

        if ($model->created && ! $model->updated) {
            $class->const('CREATED_AT', 'null');
        } else {
            if (! $model->created && $model->updated) {
                $class->const('UPDATED_AT', 'null');
            }
        }

        $class->prop('protected:table', $this->format($model->table))->doc(function ($entity) {
            if (config('scaffold.doc_block.props.table')) {
                /** @var DocumentorEntity $entity */
                $entity->description('The table associated with the model.');
                $entity->tagReturn('string');
            }
        });

        if (! $model->created && ! $model->updated) {
            $class->prop('public:timestamps', false)->doc(function ($entity) {
                if (config('scaffold.doc_block.props.timestamps')) {
                    /** @var DocumentorEntity $entity */
                    $entity->description('Disable the timestamp.');
                    $entity->tagReturn('bool');
                }
            });
        }

        if ($model->foreign && $model->foreign != 'id') {
            $class->prop('protected:primaryKey', $this->format($model->foreign))->doc(function ($entity) {
                if (config('scaffold.doc_block.props.primaryKey')) {
                    /** @var DocumentorEntity $entity */
                    $entity->description('The primary key for the model.');
                    $entity->tagReturn('string|bool');
                }
            });
        }

        $class->prop(
            'protected:fillable',
            $this->format($model->fields->sortBy('order')->pluck('name')
                ->filter(fn ($k
                ) => $k !== 'id' && $k !== 'created_at' && $k !== 'updated_at' && $k !== 'deleted_at')->toArray())
        )->doc(function ($entity) {
            if (config('scaffold.doc_block.props.fillable')) {
                /** @var DocumentorEntity $entity */
                $entity->description('The attributes that are mass assignable.');
                $entity->tagReturn('array');
            }
        });

        $class->prop(
            'protected:casts',
            $this->format($model->fields->sortBy('order')->mapWithKeys(function (LevyFieldModel $model) {
                return $model->cast ? [$model->name => $this->makeCast($model)] : [];
            })->filter(fn (
                $i,
                $k
            ) => $k !== 'id' && $k !== 'created_at' && $k !== 'updated_at' && $k !== 'deleted_at')->toArray())
        )->doc(function ($entity) {
            if (config('scaffold.doc_block.props.casts')) {
                /** @var DocumentorEntity $entity */
                $entity->description('The attributes that should be cast.');
                $entity->tagReturn('array');
            }
        });

        $attrs = $model->fields
            ->sortBy('order')
            ->pluck('default', 'name')
            ->filter(fn ($i
            ) => ! is_null($i) && $i !== 'id' && $i !== 'created_at' && $i !== 'updated_at' && $i !== 'deleted_at')
            ->toArray();

        if (count($attrs)) {
            $class->prop('protected:attributes', $this->format($attrs))->doc(function ($entity) {
                if (config('scaffold.doc_block.props.attributes')) {
                    /** @var DocumentorEntity $entity */
                    $entity->description('The model\'s attributes.');
                    $entity->tagReturn('array');
                }
            });
        }

        if ($model->hidden) {
            $class->prop('protected:hidden', $this->format($model->hidden))->doc(function ($entity) {
                if (config('scaffold.doc_block.props.hidden')) {
                    /** @var DocumentorEntity $entity */
                    $entity->description('The attributes that should be hidden for serialization.');
                    $entity->tagReturn('array');
                }
            });
        }
        if ($model->appends) {
            $class->prop('protected:appends', $this->format($model->appends))->doc(function ($entity) {
                if (config('scaffold.doc_block.props.appends')) {
                    /** @var DocumentorEntity $entity */
                    $entity->description('The accessors to append to the model\'s array form.');
                    $entity->tagReturn('array');
                }
            });
        }
        if ($model->with) {
            $class->prop('protected:with', $this->format($model->with))->doc(function ($entity) {
                if (config('scaffold.doc_block.props.with')) {
                    /** @var DocumentorEntity $entity */
                    $entity->description('The relations to eager load on every query.');
                    $entity->tagReturn('array');
                }
            });
        }
        if ($model->with_count) {
            $class->prop('protected:withCount', $this->format($model->with_count))->doc(function ($entity) {
                if (config('scaffold.doc_block.props.withCount')) {
                    /** @var DocumentorEntity $entity */
                    $entity->description('The relationship counts that should be eager loaded on every query.');
                    $entity->tagReturn('array');
                }
            });
        }

        /** @var LevyPropertyModel $property */
        foreach ($model->properties as $property) {
            $prop = $class->prop($property->property_name, $this->format($property->value))
                ->doc(function () {
                });
            if (config('scaffold.doc_block.props.custom')) {
                $prop->autoDoc();
            }
        }

        if ($model->observer) {
            $method = $class->method('boot')->modifier('protected static');
            if (config('scaffold.doc_block.methods.boot')) {
                $method->doc(function ($entity) {
                    /** @var DocumentorEntity $entity */
                    $entity->description('Bootstrap the model and its traits.');
                    $entity->tagReturn('void');
                });
            } else {
                $method->noAutoDoc();
            }
            $method->line('parent::boot();')->line();
            $method->line("static::observe({$model->observer->class}::class);");
        }

        foreach ($model->relations as $relation) {
            $method = $class->method($relation->relation_name)->returnType($relation->relation->relation_class);
            if (config('scaffold.doc_block.methods.relation')) {
                $method->doc(function ($entity) use ($relation) {
                    /** @var DocumentorEntity $entity */
                    /** @var LevyConstModel $related_name */
                    $related_name = $relation->related->constants->where('name', 'title')->first();
                    $entity->description("The \"{$relation->name}\" relation".($related_name ? ' for "'.\Str::ascii($related_name->value,
                            ).'"' : '').' model');
                    $entity->tagReturn($relation->relation->relation_class.'|'.$relation->related->class);
                });
            } else {
                $method->noAutoDoc();
            }
            $method->line("return \$this->{$relation->name}(".implode(', ',
                    $this->formatArray($relation->relation->relation_params)).');');
        }


        if (config('scaffold.defaults.model.json_unescaped_unicode', true)) {

            $class->method('toJson')
                ->param('options', entity(0))
                ->returnType('string')
                ->line('return parent::toJson(JSON_UNESCAPED_UNICODE);');

            $class->method('asJson')
                ->param('value')
                ->returnType('string')
                ->line('return json_encode($value, JSON_UNESCAPED_UNICODE);');
        }


        return [
            $model->file,
            $class->wrap('php')->render(),
        ];
    }

    /**
     * @param  LevyFieldModel  $model
     * @return \Bfg\Entity\Core\EntityPhp|string|null
     */
    protected function makeCast(LevyFieldModel $model): \Bfg\Entity\Core\EntityPhp|string|null
    {
        if (! $model->cast_class) {
            return $model->cast;
        }

        $class = class_entity($model->cast_class_name)
            ->namespace($model->cast_namespace)
            ->implement(CastsAttributes::class);

        if (! config('scaffold.doc_block.class.cast')) {
            $class->doc(function () {
            });
        }

        $method_get = $class->method('get');
        $method_get->param('model')
            ->param('key')
            ->param('value')
            ->param('attributes');
        $method_get->line('return $value;');
        if (config('scaffold.doc_block.methods.cast_get')) {
            $method_get->doc(function ($entity) use ($model) {
                /** @var DocumentorEntity $entity */
                $entity->description('Cast the given value.');
                $entity->tagParam($model->parent->class, 'model');
                $entity->tagParam('string', 'key');
                $entity->tagParam('mixed', 'value');
                $entity->tagParam('array', 'attributes');
                $entity->tagReturn('mixed');
            });
        } else {
            $method_get->noAutoDoc();
        }

        $method_set = $class->method('set');
        $method_set->param('model')
            ->param('key')
            ->param('value')
            ->param('attributes');
        $method_set->line('return $value;');
        if (config('scaffold.doc_block.methods.cast_set')) {
            $method_set->doc(function ($entity) use ($model) {
                /** @var DocumentorEntity $entity */
                $entity->description('Prepare the given value for storage.');
                $entity->tagParam($model->parent->class, 'model');
                $entity->tagParam('string', 'key');
                $entity->tagParam('mixed', 'value');
                $entity->tagParam('array', 'attributes');
                $entity->tagReturn('mixed');
            });
        } else {
            $method_set->noAutoDoc();
        }

        $this->storage()->store(
            app_path("Casts/{$model->cast_class_name}.php"),
            $class->wrap('php')->render(),
            false
        )->save();

        return entity($model->cast_class.'::class');
    }
}
