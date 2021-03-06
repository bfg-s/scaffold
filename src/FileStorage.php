<?php

namespace Bfg\Scaffold;

use Illuminate\Console\Command;

/**
 * Class FileStorage.
 * @package Bfg\Scaffold
 */
class FileStorage
{
    /**
     * @var string
     */
    protected string $cache_file;

    /**
     * @var array
     */
    protected array $files = [];

    /**
     * @var Command|null
     */
    public static ?Command $command = null;

    /**
     * @return $this
     */
    public function init(): static
    {
        $this->cache_file =
            (string) config('scaffold.cache.file_list', app()->bootstrapPath('cache/scaffold_files.php'));

        if (is_file($this->cache_file)) {
            $this->files = include $this->cache_file;
        }

        return $this;
    }

    /**
     * @param  string  $file_path
     * @param  string  $data
     * @param  bool  $removeable
     * @param  bool  $save
     * @return FileStorage
     */
    public function store(string $file_path, string $data, bool $removeable = true, bool $save = false): static
    {
        $file_path = str_replace(['/','\\'], '/', $file_path);

        if (! is_file($file_path)) {
            $dir = dirname($file_path);

            if (! is_dir($dir)) {
                mkdir($dir, 0777, 1);

                $this->files[$dir] = [
                    'sha1' => sha1($dir),
                    'remove' => $removeable,
                    'type' => 'folder',
                ];
            }

            file_put_contents(str_replace(['/','\\'], '/', $file_path), $data);
        }

        if (! isset($this->files[$file_path])) {
            $this->files[$file_path] = [
                'sha1' => sha1(preg_replace("/\n|\s|\t/", '', $data)),
                'remove' => $removeable,
                'type' => 'file',
            ];
        }

        if ($save) {
            $this->save();
        }

        return $this;
    }

    /**
     * @param  array  $files
     * @param  bool  $save
     * @return $this
     */
    public function many_store(array $files, bool $save = true): static
    {
        foreach ($files as $file) {
            if (count($file)) {
                $this->store(...$file);
            }
        }

        if ($save) {
            $this->save();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function save(): static
    {
        file_put_contents(
            str_replace(['/','\\'], '/', $this->cache_file),
            array_entity($this->files)->wrap('php', 'return')
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function clear(bool $remove_all = false): static
    {
        foreach ($this->files as $file => $info) {
            if (($info['remove'] || $remove_all) && $info['type'] == 'file') {
                $file = str_replace(['/','\\'], '/', $file);
                if (is_file($file)) {
                    \File::delete($file);
                }

                unset($this->files[$file]);
            }
        }
        foreach ($this->files as $file => $info) {
            if ($info['type'] == 'file' && is_file($file)) {
                unset($this->files[$file]);
            }
        }

        $this->save();

        return $this;
    }

    /**
     * @param  bool  $test_all
     * @return array
     */
    public function diffs(bool $test_all = false): array
    {
        $result = [];

        foreach ($this->files as $file => $info) {
            if (($info['remove'] || $test_all) && $info['type'] == 'file' && is_file($file)) {
                if ($info['sha1'] !== sha1(preg_replace("/\n|\s|\t/", '', file_get_contents($file)))) {
                    $result[] = $file;
                }
            }
        }

        return $result;
    }
}
