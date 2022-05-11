<?php

namespace Bfg\Scaffold\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class ScaffoldInjectCommand.
 * @package App\Console\Commands
 */
class ScaffoldInjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:inject {package? : Select injected package}
                            {name? : Select inject name}
                            {--f|force : Force update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for injection data to Scaffold';

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
        $package = $this->argument('package');

        $name = $this->argument('name');

        $force = $this->option('force');

        $packages = File::directories(base_path('vendor'));

        $packages = collect($packages)->map(
            fn (string $p) => File::directories($p)
        )->collapse()->filter(
            fn (string $p) => is_dir($p . '/bfg-scaffold')
        )->values()->map(
            fn (string $p) => File::files($p . '/bfg-scaffold')
        )->collapse()->filter(
            fn (SplFileInfo $fileInfo) => Str::is('*.json', $fileInfo->getPathname())
        )->map(
            fn (SplFileInfo $fileInfo) => $fileInfo->getPathname()
        )->map(
            fn (string $p) => array_merge([
                'path' => $p,
                'models' => array_keys(json_decode(file_get_contents($p),1)),
                'package' => str_replace([
                    base_path('vendor/'),
                    '/bfg-scaffold/' . pathinfo($p, PATHINFO_BASENAME)
                ], '', $p),
            ], pathinfo($p))
        );

        if ($package && !$name) {

            $selected = $packages->where('package', $package);

        } else if ($package && $name) {

            $selected = $packages->where('package', $package)
                ->where('filename', $name);

        } else {

            $selected = collect();
        }

        if ($selected->isEmpty()) {

            $result = $this->choice(
                "Select collection for injection",
                $packages->map(
                    fn (array $i) => "<comment>[{$i['package']}]</comment> - "
                        . $i['filename']
                        . ": <comment>[" . implode(',', $i['models']) . ']</comment>'
                )->toArray()
            );

            if (
                preg_match(
                    '/<comment>\[(.*)]<\/comment>\s-\s(.*):\s<comment>\[(.*)]<\/comment>/',
                    $result,
                    $matches
                )
            ) {
                $selected = $packages->where('package', $matches[1])
                    ->where('filename', $matches[2]);
            }
        }

        if ($selected->isEmpty()) {

            $this->error('Found nothing in packages!');

            return 1;
        }

        $scaffold = json_decode(
            file_get_contents(base_path('database/scaffold.json')),true
        );

        $beforeCount = count($scaffold);

        foreach ($selected as $select) {

            $models = json_decode(
                file_get_contents($select['path']),true
            );

            foreach ($models as $name => $model) {

                if (!isset($scaffold[$name]) || $force) {

                    $scaffold[$name] = $model;

                    if ($force) {

                        $beforeCount--;
                    }
                }
            }
        }

        $nowCount = count($scaffold);

        if ($beforeCount != $nowCount) {

            $added = $nowCount - $beforeCount;

            file_put_contents(
                base_path('database/scaffold.json'),
                json_encode($scaffold, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
            );

            $this->info("Added [$added] models!");

        } else {

            $this->error("No added models!");
        }

        return 0;
    }
}
