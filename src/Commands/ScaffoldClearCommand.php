<?php

namespace Bfg\Scaffold\Commands;

use Illuminate\Console\Command;

/**
 * Class ScaffoldClearCommand
 * @package Bfg\Scaffold\Commands
 */
class ScaffoldClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:clear {--a|all : Delete all generated files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for clear Scaffolded data';

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
        $storage = \Scaffold::storage()->init();

        $diffs = $storage->diffs(
            $this->option('all')
        );

        foreach ($diffs as $key => $diff) {
            $f = str_replace(base_path(), '', $diff);
            if ($this->confirm("The [$f] file has been modified! Ignore file changes?")) {
                unset($diffs[$key]);
            }
        }

        if ($diffs) {
            $this->line("Save your changes to these files:\n - ".implode("\n - ",
                    array_map(fn($i) => str_replace(base_path(), '', $i), $diffs))."\n");
            return 0;
        }

        $storage->clear(
            $this->option('all')
        );

        $this->info("Done!");

        return 0;
    }
}
