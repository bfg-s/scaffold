<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyCollections\TraitCollection;
use Bfg\Scaffold\LevyModel\LevyFactoryModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyTraitModel;
use Closure;

/**
 * Class DetectFactoryPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectFactoryPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (isset($model->syntax['factory']) && $model->syntax['factory']) {
            if (is_array($model->syntax['factory'])) {
                $model->factory = LevyFactoryModel::model(time(), [
                    'parent' => $model,
                    'class' => 'Database\\Factories\\'.$model->class_name.'Factory',
                    'class_name' => $model->class_name.'Factory',
                    'namespace' => 'Database\\Factories',
                    'lines' => collect($model->syntax['factory'])->map(function ($syntax) {
                        return is_array($syntax) ? $syntax : entity($this->parseSyntax($syntax));
                    })->toArray(),
                ]);

                $model->traits->push(
                    LevyTraitModel::model('Illuminate\Database\Eloquent\Factories\HasFactory', [
                        'parent' => $model,
                    ])
                );
            }

            unset($model->syntax['factory']);
        }

        return $next($model);
    }

    /**
     * @param  string  $syntax
     * @return string
     */
    protected function parseSyntax(string $syntax): string
    {
        if (preg_match("/^([\\\]+)(.*)/", $syntax, $m)) {
            $code = $m[2];

            $phpable = preg_replace('/([a-zA-Z|)|_])(\.)([a-zA-Z|_])/', '$1->$3', $code);

            $phpable = preg_replace('/(faker)(->)/', '\$this->faker$2', $phpable);

            return $phpable;
        }

        return is_numeric($syntax) ? $syntax : "\"{$syntax}\"";
    }
}
