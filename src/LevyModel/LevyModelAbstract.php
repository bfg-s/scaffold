<?php

namespace Bfg\Scaffold\LevyModel;

use ArrayAccess;
use Bfg\Scaffold\LevyCollections\CollectionAbstract;
use Bfg\Scaffold\LevyModel\LevyRelation\LevyRelationAbstract;
use Illuminate\Support\Traits\Macroable;

/**
 * Class LevyModelAbstract.
 * @package Bfg\Scaffold\LevyModel
 * @template C
 */
abstract class LevyModelAbstract implements ArrayAccess
{
    use Macroable;

    /**
     * @var int
     */
    public int $order = 0;

    /**
     * Data for model parse.
     * @var array
     */
    public array $syntax = [];

    /**
     * Name of levy.
     * @var string|null
     */
    public ?string $name;

    /**
     * Parent Levy Model.
     *
     * @var LevyModelAbstract|LevyRelationAbstract
     */
    public LevyModelAbstract|LevyRelationAbstract $parent;

    /**
     * @var LevyModel[][]
     */
    public static array $models = [];

    /**
     * Collection class.
     * @var string|null|C
     */
    public static ?string $collection = null;

    /**
     * LevyModel constructor.
     * @param  array  $syntax  Data for model parse
     */
    public function __construct(
        array $syntax = []
    ) {
        $this->syntax($syntax);
    }

    /**
     * @param  array  $syntax
     * @return $this
     */
    public function syntax(array $syntax = []): static
    {
        if (isset($syntax['name'])) {
            $this->name = $syntax['name'];
            unset($syntax['name']);
        }
        if (isset($syntax['parent'])) {
            $this->parent = $syntax['parent'];
            unset($syntax['parent']);
        }
        $this->syntax = array_merge($this->syntax ?? [], $syntax);
        \Scaffold::makePipe(static::class, $this);

        return $this;
    }

    /**
     * Array access.
     */

    /**
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return property_exists($this, $offset);
    }

    /**
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return property_exists($this, $offset) ? $this->{$offset} : null;
    }

    /**
     * @param  mixed  $offset
     * @param  mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        if (property_exists($this, $offset) && is_array($this->{$offset})) {
            $this->{$offset} = array_merge($this->{$offset}, $value);
        } else {
            $this->{$offset} = $value;
        }
    }

    /**
     * @param  mixed  $offset
     */
    public function offsetUnset($offset)
    {
        if (property_exists($this, $offset) && is_array($this->{$offset})) {
            $this->{$offset} = [];
        } else {
            if (property_exists($this, $offset)) {
                $this->{$offset} = null;
            }
        }
    }

    /**
     * Static methods.
     */

    /**
     * Model getter.
     * @param  string|null  $name
     * @param  array  $syntax
     * @return mixed|static
     * @throws \Exception
     */
    public static function model(string $name = null, array $syntax = []): mixed
    {
        if (! $name) {
            if (isset($syntax['name'])) {
                $name = $syntax['name'];
            } else {
                throw new \Exception('Enter a name of model!');
            }
        }

        $name = static::modelName($name, $syntax);
        $name_collect = static::modelName($name, $syntax, true);

        if (static::has_model($name_collect)) {
            return static::$models[static::$collection][$name_collect];
        }

        if (is_assoc($syntax)) {
            $syntax['name'] = $name;

            return static::$models[static::$collection][$name_collect] = static::create($syntax);
        }

        throw new \Exception("Model [{$name}] undefined!");
    }

    /**
     * @param  string  $name
     * @param  array  $syntax
     * @param  bool  $collect
     * @return string
     */
    public static function modelName(string $name, array $syntax = [], bool $collect = false): string
    {
        return $name;
    }

    /**
     * Has model.
     * @param  string  $name
     * @return bool
     */
    public static function has_model(string $name): bool
    {
        return isset(static::$models[static::$collection][static::modelName($name, [])]);
    }

    /**
     * @return int
     */
    public static function count(): int
    {
        return static::$collection && isset(static::$models[static::$collection])
            ? count(static::$models[static::$collection]) : 0;
    }

    /**
     * @return CollectionAbstract|C|null
     */
    public static function collect(): ?CollectionAbstract
    {
        return static::$collection ?
            new static::$collection(static::$models[static::$collection] ?? []) :
            null;
    }

    /**
     * Stackable methods.
     */

    /**
     * Create a new static instance.
     * @param  array  $syntax
     * @return static
     */
    public static function create(array $syntax = []): static
    {
        $result = app(static::class, [
            'syntax' => array_merge(['order' => (static::count() - 1) + 1], $syntax),
        ]);

        return $result;
    }
}
