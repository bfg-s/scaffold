<?php

namespace Bfg\Scaffold;

use Bfg\Scaffold\Exceptions\RequiredNotFound;
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
        foreach (ScaffoldConstruct::make($syntax) as $name => $data) {
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
