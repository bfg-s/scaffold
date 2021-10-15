<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyTraitModel;

/**
 * Class MakeTraitListen.
 * @package Bfg\Scaffold\Listeners
 */
class MakeTraitListen extends ListenerControl
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

        foreach ($model->traits as $trait) {
            $files[] = $this->makeFile($trait);
        }

        $this->storage()->many_store($files);
    }

    /**
     * @param  LevyTraitModel  $model
     * @return array
     */
    protected function makeFile(LevyTraitModel $model): array
    {
        if (! trait_exists($model->class) && $model->path) {
            $class = class_entity($model->class_name)
                ->traitObject()
                ->namespace($model->namespace);

            if (! config('scaffold.doc_block.class.trait')) {
                $class->doc(function () {
                });
            }

            return [
                $model->path,
                $class->wrap('php')->render(),
                false,
            ];
        }

        return [];
    }
}
