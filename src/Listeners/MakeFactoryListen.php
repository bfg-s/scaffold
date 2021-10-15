<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Scaffold\LevyModel\LevyModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class MakeFactoryListen.
 * @package Bfg\Scaffold\Listeners
 */
class MakeFactoryListen extends ListenerControl
{
    /**
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        if ($model->factory) {
            $class = class_entity($model->factory->class_name)
                ->namespace($model->factory->namespace)
                ->extend(Factory::class);

            if (! config('scaffold.doc_block.class.factory')) {
                $class->doc(function () {
                });
            }

            $prop = $class->prop('protected:model', entity($model->class.'::class'));

            $prop->doc(function ($doc) {
                /** @var DocumentorEntity $doc */
                if (config('scaffold.doc_block.props.factory_model')) {
                    $doc->description("The name of the factory's corresponding model.");
                    $doc->tagVar('string');
                }
            });

            $method = $class->method('definition');

            $method->dataReturn($model->factory->lines);

            $method->doc(function ($doc) {
                /** @var DocumentorEntity $doc */
                if (config('scaffold.doc_block.methods.factory_definition')) {
                    $doc->description("Define the model's default state.");
                    $doc->tagReturn('array');
                }
            });

            $this->storage()->store(
                database_path("factories/{$model->factory->class_name}.php"),
                $class->wrap('php')->render()
            );
        }

        $this->storage()->save();
    }
}
