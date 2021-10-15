<?php

namespace Bfg\Scaffold\Commands;

use Bfg\Scaffold\ScaffoldCommands;
use Bfg\Scaffold\ScaffoldConstruct;
use Illuminate\Console\Command;

/**
 * Class ScaffoldGenerateCommand.
 * @package App\Console\Commands
 */
class ScaffoldGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold {name? : By default [scaffold]} 
                            {--f|force : Force update} 
                            {--r|require=* : Connect the blanks to the builder}
                            {--y|yaml : Make with yaml schema}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Scaffold rule generate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $name = $this->argument('name') ?? 'scaffold';

        $path = database_path("{$name}.json");

        $yaml = false;

        if (! is_file($path) || $this->option('yaml')) {
            $path = database_path("{$name}.yaml");

            $yaml = true;
        }

        if (is_file($path)) {
            $storage = \Scaffold::storage()->init();

            $diffs = $storage->diffs();

            foreach ($diffs as $key => $diff) {
                $f = str_replace(base_path(), '', $diff);
                if ($this->option('force') || $this->confirm("The [$f] file has been modified! Overwrite the file?", true)) {
                    unset($diffs[$key]);
                }
            }

            if ($diffs) {
                $this->line("Save your changes to these files:\n - ".implode("\n - ",
                        array_map(fn ($i) => str_replace(base_path(), '', $i), $diffs))."\n");

                return 0;
            }

            $storage->clear();

            foreach ($this->option('require') as $item) {
                if (isset(ScaffoldCommands::$blanc_list[$item])) {
                    ScaffoldConstruct::blanc(
                        ScaffoldCommands::$blanc_list[$item]
                    );
                }
            }

            $collect = $yaml ? \Scaffold::modelsFromYamlFile($path) : \Scaffold::modelsFromJsonFile($path);

            if ($collect->count()) {
                $collect->render(
                    config('scaffold.scaffolding_listeners', [])
                );
                $this->info('The scaffolding completed its work successfully!');
            } else {
                $this->error('Complete the scaffolding file!');
            }
        } else {
            if ($name === 'scaffold') {
                file_put_contents($path, "{\n}");
                $this->info('Scaffold file [database/scaffold.'.($yaml ? 'yaml' : 'json').'] created!');
            } else {
                $this->error('File [database/scaffold.'.($yaml ? 'yaml' : 'json').'] not found!');
            }
        }

        return 0;
    }
}
