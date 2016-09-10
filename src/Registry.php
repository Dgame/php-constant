<?php

namespace Dgame\Constant;

use function Dgame\Ensurance\enforce;

/**
 * Class Registry
 * @package Dgame\Constant
 */
final class Registry
{
    /**
     * @var Registry
     */
    private static $instance;
    /**
     * @var array
     */
    private $entries = [];

    /**
     * Registry constructor.
     */
    private function __construct()
    {
    }

    /**
     *
     */
    private function __clone()
    {
    }

    /**
     * @return Registry
     */
    public static function Instance(): Registry
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    public function exists(string $hash): bool
    {
        return array_key_exists($hash, $this->entries);
    }

    /**
     * @param Entry $entry
     */
    public function register(Entry $entry)
    {
        $this->entries[$entry->hash] = $entry->value;
    }

    /**
     * @param string $hash
     *
     * @return mixed
     */
    public function access(string $hash)
    {
        enforce($this->exists($hash))->orThrow('No Entry for Hash "%s"', $hash);

        return $this->entries[$hash];
    }
}

/**
 * @param string $name
 *
 * @return Entry
 */
function let(string $name): Entry
{
    $tracer = new Tracer(__FUNCTION__, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5));
    $entry  = new Entry($name, $tracer);

    enforce(!Registry::Instance()->exists($entry->hash))->orThrow('Constant "%s" already exists', $name);

    return $entry;
}

/**
 * @param string $name
 *
 * @return mixed
 */
function get(string $name)
{
    $tracer = new Tracer(__FUNCTION__, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5));
    $hash   = $name . $tracer->getHash();

    enforce(Registry::Instance()->exists($hash))->orThrow('Constant "%s" does not exists', $name);

    return Registry::Instance()->access($hash);
}

/**
 * @param array $args
 */
function bind(array $args)
{
    $tracer = new Tracer(__FUNCTION__, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5));

    foreach ($args as $name => $value) {
        $entry = new Entry($name, $tracer);

        enforce(!Registry::Instance()->exists($entry->hash))->orThrow('Constant "%s" already exists', $name);

        $entry->be($value);
    }
}