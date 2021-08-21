<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Scaffold\LevyModel\LevyModel;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;

/**
 * Class MakeSeedingListen
 * @package Bfg\Scaffold\Listeners
 */
class MakeSeedingListen extends ListenerControl
{
    /**
     * @var int
     */
    protected static int $seed_count = 0;

    /**
     * @var array
     */
    protected static array $lines = [];

    /**
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        if ($model->seed) {

            $class = class_entity($model->seed->class_name)
                ->namespace($model->seed->namespace)
                ->extend(Seeder::class);

            if (!config('scaffold.doc_block.class.seed')) {
                $class->doc(function () {
                });
            }

            $method = $class->method('run');

            if ($model->seed->factory) {

                $method->line($model->seed->factory);
            }

            if ($model->seed->data) {

                $class->method('data')
                    ->modifier('protected')
                    ->dataReturn($model->seed->data);

                $method->line("foreach (\$this->data() as \$item) {");
                    $method->tab($model->class . "::create(\$item);");
                $method->line("}");
            }

            $method->doc(function ($doc) {
                /** @var DocumentorEntity $doc */
                if (config('scaffold.doc_block.methods.seed_run')) {
                    $doc->description("Run the database seeds.");
                    $doc->tagReturn('void');
                }
            });

            $this->storage()->store(
                database_path("seeders/{$model->seed->class_name}.php"),
                $class->wrap('php')->render()
            );

            $this->insertInToDatabaseSeeder($model);
        }

        $this->storage()->save();
    }

    /**
     * @param  LevyModel  $model
     */
    protected function insertInToDatabaseSeeder(LevyModel $model)
    {
        $file = database_path('seeders/DatabaseSeeder.php');

        $file_content = file_get_contents($file);

        if (!preg_match("/{$model->seed->class_name}::class/", $file_content)) {

            static::$lines[] = "        \$this->call({$model->seed->class_name}::class);";
        }
    }

    public static function finish()
    {
        $file = database_path('seeders/DatabaseSeeder.php');

        $file_content = file_get_contents($file);

        if (static::$lines) {

            $ref = new \ReflectionClass(DatabaseSeeder::class);

            $method = $ref->getMethod('run');

            $method_text = file_lines_get_contents($file, $method->getEndLine(), $method->getStartLine());

            $exploded_method = array_slice(
                explode("\n", $method_text), 0, -2
            );

            $exploded_method = array_merge($exploded_method, array_reverse(static::$lines));

            $exploded_method[] = "    }";
            $exploded_method[] = "";

            $new_file_content = str_replace(
                $method_text, implode("\n", $exploded_method), $file_content
            );

            file_put_contents($file, $new_file_content);
        }
    }
}
