<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Entity\Core\Entities\ClassMethodEntity;
use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Scaffold\LevyModel\LevyModel;

/**
 * Class MakeObserverListen
 * @package Bfg\Scaffold\Listeners
 */
class MakeObserverListen extends ListenerControl
{
    /**
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        if ($model->observer) {

            $class = class_entity($model->observer->class_name)
                ->namespace($model->observer->namespace);

            foreach ($model->observer->events as $event) {

                /** @var ClassMethodEntity $method */

                /**
                 * Handle the Director "created" event.
                 *
                 * @param  \App\Models\Director  $director
                 * @return void
                 */

                $method = $class->method($event);
                $param_name = \Str::singular($model->name);
                $method->doc(function (DocumentorEntity $entity) use ($event, $model, $param_name) {
                    $entity->description("Handle the {$model->class_name} \"{$event}\" event.");
                    $entity->tagParam($model->class, $param_name);
                });
                $method->param($param_name, null, $model->class);
                $method->line('//');
            }

            $this->storage()->store(
                app_path("Observers/{$model->observer->class_name}.php"),
                $class->wrap('php')->render(),
                false
            );
        }
    }
}
