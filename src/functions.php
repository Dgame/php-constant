<?php

namespace Dgame\Constant;

use Dgame\Ensurance\Exception\EnsuranceException;
use function Dgame\Ensurance\enforce;

/**
 * @param string $name
 *
 * @return Entry
 */
function let(string $name): Entry
{
    $tracer = new Tracer(__FUNCTION__, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, DEBUG_BACKTRACE_LEVEL));
    $entry  = new Entry($name, $tracer);

    enforce(!Registry::Instance()->exists($entry->hash))->orThrow('Constant "%s" already exists', $name);

    return $entry;
}

/**
 * @param string $name
 *
 * @return mixed
 * @throws EnsuranceException
 */
function get(string $name)
{
    // global
    $tracer = new Tracer(__FUNCTION__, []);
    $hash   = $tracer->getHash($name);

    if (Registry::Instance()->exists($hash)) {
        return Registry::Instance()->access($hash);
    }

    // local
    $tracer = new Tracer(__FUNCTION__, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, DEBUG_BACKTRACE_LEVEL));
    $hash   = $tracer->getHash($name);

    enforce(Registry::Instance()->exists($hash))->orThrow('Constant "%s" does not exists', $name);

    return Registry::Instance()->access($hash);
}

/**
 * @param array $args
 */
function bind(array $args)
{
    $tracer = new Tracer(__FUNCTION__, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, DEBUG_BACKTRACE_LEVEL));

    foreach ($args as $name => $value) {
        $entry = new Entry($name, $tracer);

        enforce(!Registry::Instance()->exists($entry->hash))->orThrow('Constant "%s" already exists', $name);

        $entry->be($value);
    }
}