<?phpnamespace Bfg\Scaffold\Listeners;use Bfg\Scaffold\FileStorage;/** * Class ListenerControl * @package Bfg\Scaffold\Listeners */abstract class ListenerControl{    /**     * @return \Bfg\Scaffold\FileStorage     */    public function storage(): FileStorage    {        return \Scaffold::storage();    }    /**     * @param  mixed  $str     * @return string     */    public function formatString(mixed $str): string    {        if (!is_string($str)) {            return $str;        }        if (            $str !== 'true' &&            $str !== 'false' &&            $str !== 'null' &&            !is_numeric($str) &&            !str_ends_with(trim($str), "::class")        ) {            $str = trim($str);            return "'{$str}'";        }        return $str;    }    /**     * @param  array  $array     * @return array     */    public function formatArray(array $array): array    {        return array_map([$this, 'formatString'], $array);    }}