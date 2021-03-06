<?php

namespace Bfg\Scaffold\LevyCollections;

use Illuminate\Support\Collection;

/**
 * Class CollectionAbstract.
 * @package Bfg\Scaffold\LevyCollections
 */
abstract class CollectionAbstract extends Collection
{
    /**
     * The render a collection.
     * @param  array  $events
     * @return $this
     */
    public function render(array $events = []): static
    {
        $first = $this->first();

        if ($first && is_object($first)) {
            $class = get_class($first);

            foreach ($events as $item) {
                \Event::listen($class, $item);
            }
        }

        $this->sortBy('order')->reverse()
            ->map(fn ($model) => \Scaffold::makeFinishPipe($model))
            ->map(fn ($model) => event($model));

        foreach ($events as $event) {
            if (method_exists($event, 'finish')) {
                call_user_func([$event, 'finish']);
            }
        }

        return $this;
    }
}
