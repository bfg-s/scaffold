<?php

namespace Bfg\Scaffold\LevyPipes\LevyModel;

use Bfg\Scaffold\LevyModel\LevyFactoryModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevySeedModel;
use Bfg\Scaffold\LevyModel\LevyTraitModel;
use Closure;
use Illuminate\Support\Str;

/**
 * Class DetectSeedPipe.
 * @package Bfg\Scaffold\LevyPipes\LevyModel
 */
class DetectSeedPipe
{
    /**
     * @param  LevyModel  $model
     * @param  Closure  $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(LevyModel $model, Closure $next): mixed
    {
        if (isset($model->syntax['seed']) && $model->syntax['seed']) {
            $common = [
                'class' => 'Database\\Seeders\\'.Str::plural($model->class_name).'Seeder',
                'class_name' => Str::plural($model->class_name).'Seeder',
                'namespace' => 'Database\\Seeders',
            ];

            if (is_array($model->syntax['seed'])) {
                $array = $model->syntax['seed'];

                if (is_assoc($array)) {
                    $array = [$array];
                }

                $model->seed = LevySeedModel::model(uniqid(), array_merge($common, [
                    'parent' => $model,
                    'data' => $array,
                ]));
            } else {
                if (
                    is_string($model->syntax['seed']) &&
                    preg_match('/^factory(.*)/', $model->syntax['seed'], $m)
                ) {
                    $count = array_map(
                        fn ($i) => preg_replace('/[^0-9]/', '', $i),
                        array_map('trim', explode(',', $m[1]))
                    );
                    if (count($count) == 0) {
                        $count = 1;
                    } else {
                        if (count($count) == 1) {
                            $count = $count[0];
                        } else {
                            if (count($count) == 2) {
                                $count = [$count[0], $count[1]];
                            } else {
                                $count = 1;
                            }
                        }
                    }

                    $model->seed = LevySeedModel::model(uniqid(), array_merge($common, [
                        'parent' => $model,
                        'factory' => $model->class.'::factory()->count('.(
                            is_array($count) ? 'rand('.implode(',', $count).')' : ($count ?: 1)
                            ).')->create();',
                    ]));
                }
            }

            unset($model->syntax['seed']);
        }

        return $next($model);
    }
}
