<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Scaffold\LevyModel\LevyDependentTableModel;
use Illuminate\Database\Migrations\Migration;
use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Illuminate\Database\Schema\Blueprint;
use Bfg\Scaffold\LevyModel\LevyModel;

/**
 * Class MakeMigrationListen
 * @package Bfg\Scaffold\Listeners
 */
class MakeMigrationListen extends ListenerControl
{
    /**
     * @var int|null
     */
    static ?int $iterator = null;

    /**
     * @var int|null
     */
    static ?int $iterator_add = null;

    /**
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        if (static::$iterator === null) {

            static::$iterator = 5555;
        }
        if (static::$iterator_add === null) {

            static::$iterator_add = 9999;
        }

        $files = [];

        foreach ($model->dependent_tables as $dependent_table) {
            $files[] = $this->makeFile($dependent_table, static::$iterator_add);
            static::$iterator_add--;
        }

        $files[] = $this->makeFile($model->table_model);
        static::$iterator--;

        $this->storage()->many_store($files);
    }

    /**
     * @param  LevyDependentTableModel  $model
     * @param  int|null  $i
     * @return array
     */
    protected function makeFile(
        LevyDependentTableModel $model,
        int $i = null
    ): array {
        $class = class_entity($model->class_name);
        $class->extend(Migration::class)
            ->use(Blueprint::class);

        $method_up = $class->method('up');
        $method_up->docDescription('Run the migrations.');
        $method_up->docReturnType('void');
        $method_up->line("Schema::create('{$model->name}', function (Blueprint \$table) {");
        /** @var LevyFieldModel $field */
        foreach ($model->fields->sortBy('order') as $field) {
            $cmtp = count($field->migration_type_params);

            $line = "\$table->{$field->type}('{$field->name}'".
                ($cmtp ? ', '.implode(', ',
                        $this->formatArray($field->migration_type_params)) : '').")";

            foreach ($field->migration_params as $func => $migration_param) {
                $line .= "->{$func}(".implode(",", $this->formatArray((array)$migration_param)).")";
            }

            $method_up->tab($line.';');
        }
        $method_up->line("});");

        $method_down = $class->method('down');
        $method_down->docDescription('Reverse the migrations.');
        $method_down->docReturnType('void');
        $method_down->line("Schema::dropIfExists('{$model->name}');");

        return [
            str_replace('{prefix}', $this->getDatePrefix($i), $model->file),
            $class->wrap('php')->render()
        ];
    }

    /**
     * @param  int|null  $i
     * @return string
     */
    protected function getDatePrefix(int $i = null): string
    {
        $z = 4 - strlen((string)static::$iterator);
        $z = $z < 0 ? 0 : $z;
        return "2020_07_02_00".str_repeat('0', $z).($i === null ? static::$iterator : $i);
        //return date('Y_m_d_H').str_repeat('0', $z).($i === null ? static::$iterator : $i);
    }
}
