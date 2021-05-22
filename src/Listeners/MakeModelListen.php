<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MakeModelListen
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
            ->namespace($model->namespace)
            ->extend(Model::class);

        foreach ($model->traits as $trait) {
            $class->addTrait($trait->class);
        }

        foreach ($model->constants as $constant) {
            $class->const(strtoupper($constant->name), $this->formatString($constant->value));
        }

        if ($model->created && !$model->updated) {
            $class->const('CREATED_AT', 'null');
        } else {
            if (!$model->created && $model->updated) {
                $class->const('UPDATED_AT', 'null');
            }
        }

        $class->prop('protected:table', $model->table);

        if (!$model->created && !$model->updated) {
            $class->prop('public:timestamps', false);
        }

        if ($model->foreign && $model->foreign != 'id') {
            $class->prop('protected:primaryKey', $model->foreign);
        }

        $attrs = $model->fields
            ->sortBy('order')
            ->pluck('default', 'name')
            ->filter(fn($i) => !is_null($i) && $i !== 'id' && $i !== 'created_at' && $i !== 'updated_at')
            ->toArray();

        if (count($attrs)) {
            $class->prop('protected:attributes', $attrs);
        }

        $class->prop(
            'protected:fillable',
            $model->fields->sortBy('order')->pluck('name')
                ->filter(fn($k) => $k !== 'id' && $k !== 'created_at' && $k !== 'updated_at')->toArray()
        );

        $class->prop(
            'protected:casts',
            $model->fields->sortBy('order')->mapWithKeys(function (LevyFieldModel $model) {
                return $model->cast ? [$model->name => $model->cast] : [];
            })->filter(fn($i, $k) => $k !== 'id' && $k !== 'created_at' && $k !== 'updated_at')->toArray()
        );

        if ($model->observer) {

            $method = $class->method('boot')->modifier('protected static');
            $method->doc(function (DocumentorEntity $entity) {
                $entity->description('Bootstrap the model and its traits.');
                $entity->tagReturn('void');
            });
            $method->line("parent::boot();")->line();
            $method->line("static::observe({$model->observer->class}::class);");
        }

        foreach ($model->relations as $relation) {
            $method = $class->method($relation->relation_name)->returnType($relation->relation->relation_class);
            $method->doc(function (DocumentorEntity $entity) use ($relation) {
                $entity->tagReturn($relation->relation->relation_class);
            });
            $method->line("return \$this->{$relation->name}(".implode(', ',
                    $this->formatArray($relation->relation->relation_params)).");");
        }

        return [
            $model->file,
            $class->wrap('php')->render()
        ];
    }
}
