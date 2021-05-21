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
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        if (static::$iterator === null) {

            static::$iterator = 9999;
        }

        $files = [];

        foreach ($model->dependent_tables as $dependent_table) {
            $files[] = $this->makeFile($dependent_table);
            static::$iterator--;
        }

        $files[] = $this->makeFile($model->table_model);
        static::$iterator--;

        foreach ($model->model_dependent_tables as $dependent_table) {
            $files[] = $this->makeFile($dependent_table);
            static::$iterator--;
        }

        $this->storage()->many_store($files);
    }

    /**
     * @param  LevyDependentTableModel  $model
     * @return array
     */
    protected function makeFile(
        LevyDependentTableModel $model
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
            str_replace('{prefix}', $this->getDatePrefix(), $model->file),
            $class->wrap('php')->render()
        ];
    }

    /**
     * @return string
     */
    protected function getDatePrefix(): string
    {
        $z = 4 - strlen((string)static::$iterator);
        $z = $z < 0 ? 0 : $z;
        return date('Y_m_d_H').str_repeat('0', $z).static::$iterator;//.(static::$iterator <= 9 ? "0".static::$iterator : static::$iterator);
    }
}
