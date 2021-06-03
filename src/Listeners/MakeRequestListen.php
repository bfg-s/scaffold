<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyRuleModel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MakeRequestListen
 * @package Bfg\Scaffold\Listeners
 */
class MakeRequestListen extends ListenerControl
{
    /**
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        if ($model->rules && $model->rules->count()) {

            $class = class_entity($model->rules->class_name)
                ->namespace($model->rules->namespace)
                ->extend(FormRequest::class);

            $method = $class->method('authorize');
            $method->line("return true;");
            $method->doc(function ($entity) {
                /** @var DocumentorEntity $entity */
                $entity->description('Determine if the user is authorized to make this request.');
                $entity->tagReturn('bool');
            });

            $rules = [];

            foreach ($model->rules as $rule) {
                if ($rule->default) {
                    $rules[$rule->field][] = $rule->rule;
                } else {
                    $this->makeRule($rule);
                    $rules[$rule->field][] = entity("new {$rule->class}");
                }
            }

            $method = $class->method('rules');
            $method->line("return ".array_entity($rules).";");
            $method->doc(function ($entity) {
                /** @var DocumentorEntity $entity */
                $entity->description('Transform and get a request validated result');
                $entity->tagReturn('array');
            });

            $this->storage()->store(
                app_path("Http/Requests/{$model->rules->class_name}.php"),
                $class->wrap('php')->render()
            );
        }

        $this->storage()->save();
    }

    /**
     * @param  LevyRuleModel  $model
     */
    protected function makeRule(LevyRuleModel $model)
    {
        $class = class_entity($model->class_name)
            ->implement(Rule::class)
            ->namespace($model->namespace);

        $method = $class->method('passes');
        $method->doc(function ($entity) {
            /** @var DocumentorEntity $entity */
            $entity->description('Determine if the validation rule passes.');
            $entity->tagParam('string', 'attribute');
            $entity->tagParam('mixed', 'value');
            $entity->tagReturn('bool');
        });
        $method->param('attribute')
            ->param('value');
        $method->line('return (string)$value === $value;');

        $method = $class->method('message');
        $method->doc(function ($entity) {
            /** @var DocumentorEntity $entity */
            $entity->description('Get the validation error message.');
            $entity->tagReturn('string');
        });
        $method->line("return 'The validation error message.';");

        $this->storage()->store(
            app_path("Rules/{$model->class_name}.php"),
            $class->wrap('php')->render(),
            false
        );
    }
}
