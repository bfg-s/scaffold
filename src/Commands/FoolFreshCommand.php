<?php

namespace Bfg\Scaffold\Commands;

use Illuminate\Console\Command;

/**
 * Class ScaffoldGenerateCommand.
 * @package App\Console\Commands
 */
class FoolFreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'full:fresh {--c|composer : Composer dump-autoload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Global rebuild, forcibly generates through scaffolding and completely refreshes the database along with seeding.';

    /**
     * Command for call before fresh processing.
     *
     * @var array
     */
    public static array $before = [];

    /**
     *  Command for call after fresh processing.
     *
     * @var array
     */
    public static array $after = [];

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
        foreach (static::$before as $item) {
            if (is_array($item)) {
                $this->call(...$item);
            }
        }

        $this->call(ScaffoldGenerateCommand::class, [
            '--force' => true,
        ]);

        $this->call('migrate:fresh', [
            '--seed' => true,
        ]);

        if ($this->option('composer')) {
            system('composer dump-autoload');
        }

        foreach (static::$after as $item) {
            if (is_array($item)) {
                $this->call(...$item);
            }
        }

        return 0;
    }
}
