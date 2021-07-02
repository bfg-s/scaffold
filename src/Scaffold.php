<?php

namespace Bfg\Scaffold;

use Bfg\Scaffold\LevyModel\LevyModel;
use Bfg\Scaffold\LevyModel\LevyModelAbstract;
use Bfg\Scaffold\LevyPipes\SetPipe;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Scaffold
 * @package Bfg\Scaffold
 */
class Scaffold
{
    /**
     * Force required models in to scaffold
     *
     * @var array
     */
    protected array $force_required = [];

    /**
     * Possible required models in to scaffold
     *
     * @var array
     */
    protected array $required = [];

    /**
     * BlessModel constructor.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @param  FileStorage  $storage
     */
    public function __construct(
        protected Container $container,
        protected FileStorage $storage,
    ) {
    }

    /**
     * Add model to force required scaffold
     *
     * @param  array  $model Associated array
     * @return $this
     */
    public function forceRequired(array $model): static
    {
        $this->force_required = array_merge_recursive($this->force_required, $model);

        return $this;
    }

    /**
     * Add required model to possible model list
     *
     * @param  string  $name
     * @param  array  $model Associated array
     * @return $this
     */
    public function required(string $name, array $model): static
    {
        $this->required[$name] = $model;

        return $this;
    }

    /**
     * @return FileStorage
     */
    public function storage(): FileStorage
    {
        return $this->storage;
    }

    /**
     * @param  string  $file
     * @return \Bfg\Scaffold\LevyCollections\ModelCollection|LevyModel[]|null
     * @throws \Exception
     */
    public function modelsFromJsonFile(string $file): \Bfg\Scaffold\LevyCollections\ModelCollection|array|null
    {
        return $this->modelsFromJson(
            is_file($file) ? file_get_contents($file) : "[]"
        );
    }

    /**
     * @param  string  $file
     * @return LevyCollections\ModelCollection|array|null
     * @throws \Exception
     */
    public function modelsFromYamlFile(string $file): \Bfg\Scaffold\LevyCollections\ModelCollection|array|null
    {
        return $this->modelsFromYaml(
            is_file($file) ? file_get_contents($file) : "[]"
        );
    }

    /**
     * @param  string  $json
     * @return \Bfg\Scaffold\LevyCollections\ModelCollection|LevyModel[]|null
     * @throws \Exception
     */
    public function modelsFromJson(string $json = "[]"): \Bfg\Scaffold\LevyCollections\ModelCollection|array|null
    {
        return $this->modelsFromArray(
            json_decode($json, 1)
        );
    }

    /**
     * @param  string  $json
     * @return \Bfg\Scaffold\LevyCollections\ModelCollection|LevyModel[]|null
     * @throws \Exception
     */
    public function modelsFromYaml(string $yaml = "[]"): \Bfg\Scaffold\LevyCollections\ModelCollection|array|null
    {
        return $this->modelsFromArray(
            Yaml::parse($yaml)
        );
    }

    /**
     * @param  array  $syntax
     * @return \Bfg\Scaffold\LevyCollections\ModelCollection|LevyModel[]|null
     * @throws \Exception
     */
    public function modelsFromArray(array $syntax): \Bfg\Scaffold\LevyCollections\ModelCollection|array|null
    {
        $syntax = array_merge_recursive($this->force_required, $syntax);

        if (isset($syntax['required'])) {
            if (is_array($syntax['required']) && !is_assoc($syntax['required'])) {
                foreach ($syntax['required'] as $require) {
                    if (
                        isset($this->required[$require]) &&
                        is_array($this->required[$require])
                    ) {

                        $syntax = array_merge_recursive($this->required[$require], $syntax);
                    }
                }
            }

            unset($syntax['required']);
        }

        foreach ($syntax as $name => $data) {
            LevyModel::model($name, $data);
        }

        return LevyModel::collect();
    }

    /**
     * @return \Bfg\Scaffold\LevyCollections\ModelCollection|LevyModel[]|null
     */
    public function models(): \Bfg\Scaffold\LevyCollections\ModelCollection|array|null
    {
        return LevyModel::collect();
    }

    /**
     * @param  string  $class
     * @param  LevyModelAbstract  $model
     */
    public function makePipe(string $class, LevyModelAbstract $model)
    {
        (new Pipeline($this->container))
            ->send($model)
            ->through(array_merge((array) config('scaffold.parse_pipes.'.$class, []), [
                SetPipe::class
            ]))->thenReturn();
    }
}
