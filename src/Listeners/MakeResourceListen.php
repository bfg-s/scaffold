<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyResourceModel;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MakeResourceListen.
 * @package Bfg\Scaffold\Listeners
 */
class MakeResourceListen extends ListenerControl
{
    /**
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        $files = [];

        foreach ($model->resources as $resource) {
            $files[] = $this->makeFile($resource);
        }

        $this->storage()->many_store($files);
    }

    /**
     * @param  LevyResourceModel  $model
     * @return array
     */
    protected function makeFile(LevyResourceModel $model): array
    {
        $class = class_entity($model->class_name)
            ->namespace($model->namespace)
            ->extend(JsonResource::class);

        if (! config('scaffold.doc_block.class.resource')) {
            $class->doc(function () {
            });
        }

        $method = $class->method('toArray');
        $method->param('request');
        $method->line('return parent::toArray($request);');
        if (config('scaffold.doc_block.methods.resource_to_array')) {
            $method->doc(function ($entity) {
                /** @var DocumentorEntity $entity */
                $entity->description('Transform the resource into an array.');
                $entity->tagParam(\Illuminate\Http\Request::class, 'request');
                $entity->tagReturn('array');
            });
        } else {
            $method->noAutoDoc();
        }

        return [
            app_path("Http/Resources/{$model->class_name}.php"),
            $class->wrap('php')->render(),
            false,
        ];
    }
}
