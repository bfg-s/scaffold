<?php

namespace Bfg\Scaffold\Listeners;

use Bfg\Scaffold\LevyModel\LevyDependentTableModel;
use Bfg\Scaffold\LevyModel\LevyFieldModel;
use Bfg\Scaffold\LevyModel\LevyModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class MakeMigrationListen.
 * @package Bfg\Scaffold\Listeners
 */
class MakeMigrationListen extends ListenerControl
{
    /**
     * @var int|null
     */
    public static ?int $iterator = null;

    /**
     * @var int|null
     */
    public static ?int $iterator_add = null;

    public static array $files_name = [];

    /**
     * Handle the event.
     *
     * @param  LevyModel  $model
     * @return void
     */
    public function handle(LevyModel $model)
    {
        if (static::$iterator === null) {
            static::$iterator = 888888;
        }
        if (static::$iterator_add === null) {
            static::$iterator_add = 999999;
        }

        $files = [];

        foreach ($model->dependent_tables as $dependent_table) {
            if (! isset(static::$files_name[$dependent_table->name])) {
                $files[] = $this->makeFile($dependent_table, static::$iterator_add);
                static::$iterator_add--;
                static::$files_name[$dependent_table->name] = $dependent_table;
            }
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
        if (! config('scaffold.doc_block.class.migrations')) {
            $class->doc(function () {
            });
        }
        $class->extend(Migration::class)
            ->use(Blueprint::class);

        $method_up = $class->method('up');
        if (config('scaffold.doc_block.methods.migration_up')) {
            $method_up->docDescription('Run the migrations.');
            $method_up->docReturnType('void');
        } else {
            $method_up->noAutoDoc();
        }
        $method_up->line("Schema::create('{$model->name}', function (Blueprint \$table) {");
        $timestamps = [];
        /** @var LevyFieldModel $field */
        foreach ($model->fields->sortBy('order') as $field) {
            if ($field->name == 'created_at' || $field->name == 'updated_at') {
                $timestamps[] = $field;
                continue;
            }
            $cmtp = count($field->migration_type_params);

            $line = "\$table->{$field->type}('{$field->name}'".
                ($cmtp ? ', '.implode(', ',
                        $this->formatArray($field->migration_type_params)) : '').')';

            foreach ($field->migration_params as $func => $migration_param) {
                $line .= "->{$func}(".implode(',', $this->formatArray((array) $migration_param)).')';
            }

            $method_up->tab($line.';');
        }
        if (count($timestamps) == 2) {
            $method_up->tab('$table->timestamps();');
        } else {
            foreach ($timestamps as $field) {
                $cmtp = count($field->migration_type_params);

                $line = "\$table->{$field->type}('{$field->name}'".
                    ($cmtp ? ', '.implode(', ',
                            $this->formatArray($field->migration_type_params)) : '').')';

                foreach ($field->migration_params as $func => $migration_param) {
                    $line .= "->{$func}(".implode(',', $this->formatArray((array) $migration_param)).')';
                }

                $method_up->tab($line.';');
            }
        }
        $method_up->line('});');

        $method_down = $class->method('down');
        if (config('scaffold.doc_block.methods.migration_down')) {
            $method_down->docDescription('Reverse the migrations.');
            $method_down->docReturnType('void');
        } else {
            $method_down->noAutoDoc();
        }
        $method_down->line("Schema::dropIfExists('{$model->name}');");

        return [
            str_replace('{prefix}', $this->getDatePrefix($i), $model->file),
            $class->wrap('php')->render(),
        ];
    }

    /**
     * @param  int|null  $i
     * @return string
     */
    protected function getDatePrefix(int $i = null): string
    {
        $z = 6 - strlen((string) static::$iterator);
        $z = $z < 0 ? 0 : $z;

        return '2020_07_02_'.str_repeat('0', $z).($i === null ? static::$iterator : $i);
    }
}
